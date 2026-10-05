<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\DropOut;
use App\Models\Grading;
use App\Models\GradingResult;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\SubjectDetail;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * The four record-keeping pages that hang off a student: drop outs, certificates,
 * the grade bands, and the marks filed against them.
 *
 * These assert the rules rather than "the form saves", because each of those rules
 * is the reason the page exists and each is easy to lose to a passing smoke test:
 *
 *  - a drop-out is an event with a date, so the date is required and the list is
 *    ordered by it;
 *
 *  - a certificate starts life as *not received*. Issuing one is a decision, the
 *    handover is a later one, and collapsing the two would tell the office a
 *    student collected something they never came for;
 *
 *  - a band with marks filed under it cannot be deleted, because the column would
 *    quietly null those marks out rather than report what it is about to lose;
 *
 *  - a mark may exist with no band. The mark comes off the script on the day and
 *    the grade is settled later, so an ungraded mark is a state, not a gap.
 *
 * Fixtures are discovered rather than created, for the reason the admin exam tests
 * give: the assertions are about the admin's choices, not about a whole schema's
 * NOT NULL columns, and a database with no courses is not a bug in this admin.
 *
 * These run against the database named in .env rather than the throwaway one
 * phpunit.xml hands the rest of the suite, because they roll each test back
 * instead of migrating.
 */
class AdminStudentRecordsTest extends TestCase
{
    use DatabaseTransactions;

    private ?User $admin = null;

    private ?Student $student = null;

    private ?int $courseId = null;

    private ?int $subjectId = null;

    /**
     * Point this test at the database named in .env rather than the one
     * phpunit.xml hands the rest of the suite, before DatabaseTransactions opens
     * its transaction.
     */
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql.database', $this->developmentDatabase());

        return $app;
    }

    private function developmentDatabase(): string
    {
        foreach (file(base_path('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            if (preg_match('/^\s*DB_DATABASE\s*=\s*(.+?)\s*$/', $line, $matches)) {
                return trim($matches[1], "\"'");
            }
        }

        return 'yhaproject';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture();
    }

    /**
     * The rows every test needs: an admin to sign in as, a student to record, and
     * a course that teaches a subject.
     *
     * The course and subject come from subject_detail rather than being invented,
     * because both the marks form and its validation refuse a subject the course
     * does not teach, so a made-up pair would be a pair the form never accepts.
     */
    private function fixture(): void
    {
        $this->admin = User::where('role', '!=', 'user')->first();

        if ($this->admin === null) {
            $this->markTestSkipped('No admin user to sign in as.');
        }

        $this->student = Student::orderBy('id')->first();

        if ($this->student === null) {
            $this->markTestSkipped('No student to record anything against.');
        }

        $pair = SubjectDetail::whereIn('course_id', Course::pluck('id'))->first(['course_id', 'subject_id']);

        if ($pair === null) {
            $this->markTestSkipped('No course that teaches a subject, so there is nothing to mark.');
        }

        $this->courseId = (int) $pair->course_id;
        $this->subjectId = (int) $pair->subject_id;
    }

    private function asAdmin(): self
    {
        $this->actingAs($this->admin);

        return $this;
    }

    // ------------------------------------------------------------- drop outs

    public function test_a_drop_out_is_recorded_with_its_date(): void
    {
        $this->asAdmin()
            ->post(route('dropOut.create'), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'drop_out_date' => '2026-07-14',
                'remark' => 'Moved abroad',
            ])
            ->assertRedirect(route('dropOut.index'));

        $this->assertDatabaseHas('drop_outs', [
            'student_id' => $this->student->id,
            'course_id' => $this->courseId,
            'remark' => 'Moved abroad',
        ]);

        $this->assertSame('2026-07-14', DropOut::latest('id')->first()->drop_out_date->toDateString());
    }

    /**
     * The point of the record: a student who has dropped out is not still counted
     * in the class they walked out of.
     *
     * Attendance, the class list and the student's own courses page all read the
     * enrollment, so leaving it active would make this whole page a note to self.
     */
    public function test_recording_a_drop_out_closes_the_enrollment(): void
    {
        $enrollment = $this->createEnrollment(null, StudentEnrollment::STATUS_ACTIVE);

        $this->assertSame(StudentEnrollment::STATUS_ACTIVE, $enrollment->status);
        $this->assertNull($enrollment->complete_date);

        $this->asAdmin()
            ->post(route('dropOut.create'), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'drop_out_date' => '2026-07-14',
            ])
            ->assertRedirect(route('dropOut.index'));

        $closed = StudentEnrollment::findOrFail($enrollment->id);

        $this->assertSame(StudentEnrollment::STATUS_DROPPED, $closed->status);
        // complete_date is where an enrollment records the day it ended, whatever
        // ended it - and it is the date a certificate would read back
        $this->assertSame('2026-07-14', $closed->complete_date->toDateString());
    }

    /**
     * Only the named course is closed. A student sits several courses at once and
     * dropping out of one says nothing about the others.
     */
    public function test_the_student_stays_enrolled_in_their_other_courses(): void
    {
        $this->createEnrollment();

        $otherCourseId = Course::where('id', '!=', $this->courseId)->value('id');

        if ($otherCourseId === null) {
            $this->markTestSkipped('Only one course exists, so there is no other course to stay enrolled in.');
        }

        StudentEnrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $otherCourseId,
            'section_id' => null,
            'enroll_date' => now()->subMonth()->toDateString(),
            'status' => StudentEnrollment::STATUS_ACTIVE,
        ]);

        $this->asAdmin()
            ->post(route('dropOut.create'), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'drop_out_date' => '2026-07-14',
            ]);

        $this->assertSame(StudentEnrollment::STATUS_DROPPED, StudentEnrollment::where(
            'student_id', $this->student->id
        )->where('course_id', $this->courseId)->value('status'));

        $this->assertSame(StudentEnrollment::STATUS_ACTIVE, StudentEnrollment::where(
            'student_id', $this->student->id
        )->where('course_id', $otherCourseId)->value('status'));
    }

    /**
     * A corrected date has to be corrected on the enrollment too, or the class
     * record and the drop-out record would say two different days.
     */
    public function test_correcting_the_date_moves_the_enrollments_complete_date(): void
    {
        $enrollment = $this->createEnrollment(null, StudentEnrollment::STATUS_ACTIVE);
        $dropOut = $this->createDropOut();

        $dropOut->closeEnrollments();

        $this->asAdmin()
            ->post(route('dropOut.update', ['id' => $dropOut->id]), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'drop_out_date' => '2026-08-01',
            ])
            ->assertRedirect(route('dropOut.index'));

        $this->assertSame(
            '2026-08-01',
            StudentEnrollment::findOrFail($enrollment->id)->complete_date->toDateString()
        );
    }

    /**
     * Pointing a record at the wrong course is the correction this page most needs
     * to get right: it has to let go of the class it wrongly closed as well as
     * close the right one, or a student is left shut out of a class they attend.
     */
    public function test_changing_the_course_reopens_the_old_one_and_closes_the_new_one(): void
    {
        $wrong = $this->createEnrollment(null, StudentEnrollment::STATUS_ACTIVE);

        $otherCourseId = Course::where('id', '!=', $this->courseId)->value('id');

        if ($otherCourseId === null) {
            $this->markTestSkipped('Only one course exists, so there is no other course to move the drop-out to.');
        }

        StudentEnrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $otherCourseId,
            'section_id' => null,
            'enroll_date' => now()->subMonth()->toDateString(),
            'status' => StudentEnrollment::STATUS_ACTIVE,
        ]);

        $dropOut = $this->createDropOut();
        $dropOut->closeEnrollments();

        $this->asAdmin()
            ->post(route('dropOut.update', ['id' => $dropOut->id]), [
                'student_id' => $this->student->id,
                'course_id' => $otherCourseId,
                'drop_out_date' => '2026-07-14',
            ])
            ->assertRedirect(route('dropOut.index'));

        $reopened = StudentEnrollment::findOrFail($wrong->id);

        $this->assertSame(StudentEnrollment::STATUS_ACTIVE, $reopened->status);
        $this->assertNull($reopened->complete_date);

        $this->assertSame(StudentEnrollment::STATUS_DROPPED, StudentEnrollment::where(
            'student_id', $this->student->id
        )->where('course_id', $otherCourseId)->value('status'));
    }

    /**
     * A drop-out recorded in error must not go on excluding the student from a
     * class they are still in.
     */
    public function test_deleting_a_drop_out_puts_the_student_back_in_the_class(): void
    {
        $enrollment = $this->createEnrollment(null, StudentEnrollment::STATUS_ACTIVE);
        $dropOut = $this->createDropOut();

        $dropOut->closeEnrollments();

        $this->asAdmin()
            ->get(route('dropOut.delete', ['id' => $dropOut->id]))
            ->assertRedirect(route('dropOut.index'));

        $reopened = StudentEnrollment::findOrFail($enrollment->id);

        $this->assertSame(StudentEnrollment::STATUS_ACTIVE, $reopened->status);
        $this->assertNull($reopened->complete_date);
        $this->assertDatabaseMissing('drop_outs', ['id' => $dropOut->id]);
    }

    /**
     * Reopening is scoped to what that record closed. An enrollment already
     * dropped for some other reason - on some other day - is not something
     * deleting an unrelated record should quietly undo.
     */
    public function test_deleting_a_drop_out_leaves_an_enrollment_dropped_for_another_reason(): void
    {
        $enrollment = StudentEnrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->courseId,
            'section_id' => null,
            'enroll_date' => now()->subMonths(2)->toDateString(),
            'status' => StudentEnrollment::STATUS_DROPPED,
            // a different day from the record the test deletes
            'complete_date' => '2026-05-02 00:00:00',
        ]);

        $this->asAdmin()
            ->get(route('dropOut.delete', ['id' => $this->createDropOut()->id]))
            ->assertRedirect(route('dropOut.index'));

        $this->assertSame(StudentEnrollment::STATUS_DROPPED, StudentEnrollment::findOrFail($enrollment->id)->status);
    }

    /**
     * The date is the one field a drop-out cannot be without: the list is ordered
     * by it, so a row with none would be a row nobody can find. The course is
     * required for the same reason in the other direction - it is what the record
     * acts on.
     */
    public function test_a_drop_out_needs_a_student_a_course_and_a_date(): void
    {
        $base = [
            'student_id' => $this->student->id,
            'course_id' => $this->courseId,
            'drop_out_date' => '2026-07-14',
        ];

        $this->asAdmin()
            ->post(route('dropOut.create'), ['student_id' => $this->student->id, 'course_id' => $this->courseId])
            ->assertSessionHasErrors('drop_out_date');

        $this->asAdmin()
            ->post(route('dropOut.create'), ['course_id' => $this->courseId, 'drop_out_date' => '2026-07-14'])
            ->assertSessionHasErrors('student_id');

        $this->asAdmin()
            ->post(route('dropOut.create'), ['student_id' => $this->student->id, 'drop_out_date' => '2026-07-14'])
            ->assertSessionHasErrors('course_id');

        $this->asAdmin()
            ->post(route('dropOut.create'), array_merge($base, ['course_id' => 999999]))
            ->assertSessionHasErrors('course_id');

        $this->assertDatabaseCount('drop_outs', 0);
    }

    public function test_the_drop_out_list_narrows_by_course_and_by_student(): void
    {
        $dropOut = $this->createDropOut();

        $this->asAdmin()
            ->get(route('dropOut.index'))
            ->assertOk()
            ->assertViewIs('admin.dropOut.index')
            ->assertViewHas('dropOuts', fn ($rows) => $rows->contains('id', $dropOut->id));

        $this->asAdmin()
            ->get(route('dropOut.index', ['course_id' => $this->courseId]))
            ->assertOk()
            ->assertViewHas('dropOuts', fn ($rows) => $rows->contains('id', $dropOut->id));

        $otherCourseId = Course::where('id', '!=', $this->courseId)->value('id');

        if ($otherCourseId !== null) {
            $this->asAdmin()
                ->get(route('dropOut.index', ['course_id' => $otherCourseId]))
                ->assertOk()
                ->assertViewHas('dropOuts', fn ($rows) => $rows->isEmpty());
        }

        $this->asAdmin()
            ->get(route('dropOut.index', ['q' => 'no-such-student-anywhere']))
            ->assertOk()
            ->assertViewHas('dropOuts', fn ($rows) => $rows->isEmpty());

        // a date from the future cannot match a drop-out that already happened
        $this->asAdmin()
            ->get(route('dropOut.index', ['from' => Carbon::now()->addYear()->toDateString()]))
            ->assertOk()
            ->assertViewHas('dropOuts', fn ($rows) => $rows->isEmpty());
    }

    public function test_a_drop_out_is_edited_and_deleted(): void
    {
        $dropOut = $this->createDropOut();

        $this->asAdmin()
            ->get(route('dropOut.edit', ['id' => $dropOut->id]))
            ->assertOk()
            ->assertViewIs('admin.dropOut.edit');

        $this->asAdmin()
            ->post(route('dropOut.update', ['id' => $dropOut->id]), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'drop_out_date' => '2026-08-01',
                'remark' => 'Corrected date',
            ])
            ->assertRedirect(route('dropOut.index'));

        $this->assertDatabaseHas('drop_outs', [
            'id' => $dropOut->id,
            'remark' => 'Corrected date',
        ]);

        $this->asAdmin()
            ->get(route('dropOut.delete', ['id' => $dropOut->id]))
            ->assertRedirect(route('dropOut.index'));

        $this->assertDatabaseMissing('drop_outs', ['id' => $dropOut->id]);
    }

    // ---------------------------------------------------------- certificates

    /**
     * A certificate that is issued has not been collected. This is the whole point
     * of the state: recording that a student finished must not tell the office a
     * student walked out with the certificate.
     */
    public function test_a_new_certificate_starts_as_not_received(): void
    {
        $this->asAdmin()
            ->post(route('certificate.create'), [
                'student_id' => $this->student->id,
                'complete_date' => '2026-06-30',
            ])
            ->assertRedirect(route('certificate.index'));

        $this->assertDatabaseHas('certificates', [
            'student_id' => $this->student->id,
            'remark' => Certificate::NOT_RECEIVED,
        ]);

        $this->assertFalse(Certificate::latest('id')->first()->isReceived());
    }

    public function test_the_handover_state_is_one_of_the_two_and_can_be_changed(): void
    {
        $certificate = $this->createCertificate();

        $this->asAdmin()
            ->post(route('certificate.update', ['id' => $certificate->id]), [
                'student_id' => $this->student->id,
                'complete_date' => '2026-06-30',
                'remark' => Certificate::RECEIVED,
            ])
            ->assertRedirect(route('certificate.index'));

        $this->assertTrue(Certificate::findOrFail($certificate->id)->isReceived());

        // anything else is refused rather than stored, so a row always says which
        // side of the handover it is on
        $this->asAdmin()
            ->post(route('certificate.update', ['id' => $certificate->id]), [
                'student_id' => $this->student->id,
                'complete_date' => '2026-06-30',
                'remark' => 'maybe',
            ])
            ->assertSessionHasErrors('remark');

        $this->assertTrue(Certificate::findOrFail($certificate->id)->isReceived());
    }

/**
 * Leaving the date empty falls back to the student's own completed
 * enrollment, which is where the date the certificate is for actually lives.
 */
public function test_an_empty_complete_date_is_taken_from_a_completed_enrollment(): void
    {
        $this->clearCompletions();
        $this->createEnrollment('2026-06-30');

        $this->asAdmin()
            ->post(route('certificate.create'), ['student_id' => $this->student->id])
            ->assertRedirect(route('certificate.index'));

        $this->assertSame(
            '2026-06-30',
            Certificate::latest('id')->first()->complete_date->toDateString()
        );
    }

    /**
     * ...but a date that was typed in is the admin's call, not a suggestion to be
     * overridden: a certificate is sometimes dated later than the class ended.
     */
    public function test_a_typed_complete_date_is_kept_as_it_is(): void
    {
        $this->clearCompletions();
        $this->createEnrollment('2026-06-30');

        $this->asAdmin()
            ->post(route('certificate.create'), [
                'student_id' => $this->student->id,
                'complete_date' => '2026-07-20',
            ])
            ->assertRedirect(route('certificate.index'));

        $this->assertSame('2026-07-20', Certificate::latest('id')->first()->complete_date->toDateString());
    }

/**
 * Nothing finished, nothing to borrow: the date stays empty and visible rather
 * than becoming the day they enrolled.
 */
public function test_an_unfinished_student_gets_no_complete_date(): void
    {
        // the fallback reads *any* finished enrollment this student has, in any
        // course, so the precondition is stated rather than hoped for
        $this->clearCompletions();

        $this->asAdmin()
            ->post(route('certificate.create'), ['student_id' => $this->student->id])
            ->assertRedirect(route('certificate.index'));

        $this->assertNull(Certificate::latest('id')->first()->complete_date);
    }

    public function test_the_certificate_list_narrows_by_handover_state_and_student(): void
    {
        $certificate = $this->createCertificate(Certificate::NOT_RECEIVED);

        $this->asAdmin()
            ->get(route('certificate.index'))
            ->assertOk()
            ->assertViewIs('admin.certificate.index')
            ->assertViewHas('certificates', fn ($rows) => $rows->contains('id', $certificate->id));

        $this->asAdmin()
            ->get(route('certificate.index', ['status' => Certificate::RECEIVED]))
            ->assertOk()
            ->assertViewHas('certificates', fn ($rows) => $rows->isEmpty());

        $this->asAdmin()
            ->get(route('certificate.index', ['status' => Certificate::NOT_RECEIVED]))
            ->assertOk()
            ->assertViewHas('certificates', fn ($rows) => $rows->contains('id', $certificate->id));

        // a state that is not one of the two means "no filter" rather than
        // silently blanking the list
        $this->asAdmin()
            ->get(route('certificate.index', ['status' => 'lost']))
            ->assertOk()
            ->assertViewHas('certificates', fn ($rows) => $rows->contains('id', $certificate->id));

        $this->asAdmin()
            ->get(route('certificate.index', ['q' => 'no-such-student-anywhere']))
            ->assertOk()
            ->assertViewHas('certificates', fn ($rows) => $rows->isEmpty());
    }

    public function test_a_certificate_is_edited_and_deleted(): void
    {
        $certificate = $this->createCertificate();

        $this->asAdmin()
            ->get(route('certificate.edit', ['id' => $certificate->id]))
            ->assertOk()
            ->assertViewIs('admin.certificate.edit');

        $this->asAdmin()
            ->get(route('certificate.delete', ['id' => $certificate->id]))
            ->assertRedirect(route('certificate.index'));

        $this->assertDatabaseMissing('certificates', ['id' => $certificate->id]);
    }

    // --------------------------------------------------------- grade bands

    public function test_a_grade_band_is_added_and_edited(): void
    {
        $this->asAdmin()
            ->post(route('grading.create'), ['name' => 'Distinction', 'score' => '100'])
            ->assertRedirect(route('grading.index'));

        $grading = Grading::where('name', 'Distinction')->firstOrFail();

        $this->assertSame('100.00', $grading->score);
        $this->assertSame('Distinction (up to 100)', $grading->label());

        $this->asAdmin()
            ->post(route('grading.update', ['id' => $grading->id]), ['name' => 'Distinction', 'score' => '95'])
            ->assertRedirect(route('grading.index'));

        $this->assertSame('95.00', Grading::findOrFail($grading->id)->score);
    }

    /**
     * A band is picked by name when a mark is graded, so two bands with one name
     * would make that pick ambiguous - and saving an unchanged form would fail on
     * the band's own name if uniqueness were not ignored against itself.
     */
    public function test_band_names_are_unique_but_a_band_may_keep_its_own_name(): void
    {
        $this->asAdmin()->post(route('grading.create'), ['name' => 'Pass', 'score' => '50']);

        $pass = Grading::where('name', 'Pass')->firstOrFail();

        $this->asAdmin()
            ->post(route('grading.create'), ['name' => 'Pass', 'score' => '60'])
            ->assertSessionHasErrors('name');

        $this->asAdmin()
            ->post(route('grading.update', ['id' => $pass->id]), ['name' => 'Pass', 'score' => '55'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('grading.index'));

        $this->asAdmin()
            ->post(route('grading.update', ['id' => $pass->id]), ['name' => 'Merit', 'score' => '55'])
            ->assertSessionHasNoErrors();

        $this->assertSame('Merit', Grading::findOrFail($pass->id)->name);
    }

    /**
     * Deleting a band in use would null out every mark filed under it, which is
     * not something anyone asked for on this page. The delete is refused instead,
     * and says what is in the way.
     */
    public function test_a_band_with_marks_cannot_be_deleted(): void
    {
        $band = $this->createBand('A', '100');
        $this->createResult($band);

        $this->asAdmin()
            ->get(route('grading.delete', ['id' => $band->id]))
            ->assertRedirect(route('grading.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('gradings', ['id' => $band->id]);
    }

    public function test_an_unused_band_is_deleted(): void
    {
        $band = $this->createBand('A', '100');

        $this->asAdmin()
            ->get(route('grading.delete', ['id' => $band->id]))
            ->assertRedirect(route('grading.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('gradings', ['id' => $band->id]);
    }

    public function test_the_band_list_counts_the_marks_filed_under_each_band(): void
    {
        $used = $this->createBand('A', '100');
        $this->createBand('B', '80');
        $this->createResult($used);

        $this->asAdmin()
            ->get(route('grading.index'))
            ->assertOk()
            ->assertViewIs('admin.grading.index')
            ->assertViewHas('gradings', function ($bands) use ($used) {
                $counts = $bands->keyBy('id')->map(fn ($band) => $band->results_count);

                return $counts[$used->id] === 1
                    && $bands->first()->score >= $bands->last()->score;
            });
    }

    // ---------------------------------------------------------------- marks

    public function test_a_mark_is_recorded_without_a_band(): void
    {
        $this->asAdmin()
            ->post(route('gradingResult.create'), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'subject_id' => $this->subjectId,
                'score' => '72',
                'date' => '2026-09-01',
            ])
            ->assertRedirect(route('gradingResult.index'));

        $result = GradingResult::latest('id')->firstOrFail();

        // a mark off the script on the day is not yet graded, and null is what
        // says so - the band is settled later against this same row
        $this->assertNull($result->grade_id);
        $this->assertFalse($result->isGraded());
        $this->assertSame('72.00', $result->score);
        $this->assertSame('2026-09-01', $result->date->toDateString());
        $this->assertSame('72', $result->scoreLabel());
    }

    public function test_a_mark_can_be_put_into_a_band_afterwards(): void
    {
        $result = $this->createResult();
        $band = $this->createBand('B', '80');

        $this->asAdmin()
            ->post(route('gradingResult.update', ['id' => $result->id]), [
                'student_id' => $this->student->id,
                'course_id' => $this->courseId,
                'subject_id' => $this->subjectId,
                'grade_id' => $band->id,
                'score' => '75',
                'date' => '2026-09-01',
            ])
            ->assertRedirect(route('gradingResult.index'));

        $graded = GradingResult::with('grade')->findOrFail($result->id);

        $this->assertTrue($graded->isGraded());
        $this->assertSame($band->name, $graded->grade->name);
        $this->assertSame('75.00', $graded->score);
    }

    /**
     * A mark has to name a course and a subject, and the subject has to be one
     * that course teaches - otherwise the mark is filed against a class that never
     * had the subject and nothing can find it again.
     */
    public function test_a_mark_needs_a_course_and_a_subject_that_course_teaches(): void
    {
        $foreignSubjectId = Subject::whereNotIn('id', function ($query) {
            $query->select('subject_id')->from('subject_detail')->where('course_id', $this->courseId);
        })->value('id');

        if ($foreignSubjectId === null) {
            $this->markTestSkipped('Every subject is linked to this course, so there is no foreign subject to try.');
        }

        $base = [
            'student_id' => $this->student->id,
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'score' => '72',
            'date' => '2026-09-01',
        ];

        $this->asAdmin()
            ->post(route('gradingResult.create'), array_merge($base, ['course_id' => null]))
            ->assertSessionHasErrors('course_id');

        $this->asAdmin()
            ->post(route('gradingResult.create'), array_merge($base, ['subject_id' => $foreignSubjectId]))
            ->assertSessionHasErrors('subject_id');

        $this->asAdmin()
            ->post(route('gradingResult.create'), array_merge($base, ['score' => null, 'date' => null]))
            ->assertSessionHasErrors(['score', 'date']);

        // none of those submissions saved anything. Counted for this student
        // rather than for the table: the assertions are about what a refused
        // submission did not write, not about the dev database being empty.
        $this->assertSame(0, GradingResult::where('student_id', $this->student->id)->count());
    }

    public function test_the_mark_list_narrows_by_course_subject_band_and_grading_state(): void
    {
        $band = $this->createBand('A', '100');
        $graded = $this->createResult($band);
        $ungraded = $this->createResult();

        $this->asAdmin()
            ->get(route('gradingResult.index'))
            ->assertOk()
            ->assertViewIs('admin.gradingResult.index')
            ->assertViewHas('results', fn ($rows) => $rows->contains('id', $graded->id)
                && $rows->contains('id', $ungraded->id));

        $this->asAdmin()
            ->get(route('gradingResult.index', ['course_id' => $this->courseId]))
            ->assertOk()
            ->assertViewHas('results', fn ($rows) => $rows->every(
                fn ($row) => $row->course_id === $this->courseId
            ));

        $this->asAdmin()
            ->get(route('gradingResult.index', ['subject_id' => $this->subjectId]))
            ->assertOk()
            ->assertViewHas('results', fn ($rows) => $rows->every(
                fn ($row) => $row->subject_id === $this->subjectId
            ));

        $this->asAdmin()
            ->get(route('gradingResult.index', ['grade_id' => $band->id]))
            ->assertOk()
            ->assertViewHas('results', fn ($rows) => $rows->every(
                fn ($row) => $row->grade_id === $band->id
            ) && $rows->contains('id', $graded->id));

        // the state a teacher actually works from: what still has to be graded
        $this->asAdmin()
            ->get(route('gradingResult.index', ['ungraded' => 1]))
            ->assertOk()
            ->assertViewHas('results', fn ($rows) => $rows->every(
                fn ($row) => $row->grade_id === null
            ) && $rows->contains('id', $ungraded->id));

        $this->asAdmin()
            ->get(route('gradingResult.index', ['q' => 'no-such-student-anywhere']))
            ->assertOk()
            ->assertViewHas('results', fn ($rows) => $rows->isEmpty());
    }

    public function test_a_mark_is_edited_and_deleted(): void
    {
        $result = $this->createResult();

        $this->asAdmin()
            ->get(route('gradingResult.edit', ['id' => $result->id]))
            ->assertOk()
            ->assertViewIs('admin.gradingResult.edit');

        $this->asAdmin()
            ->get(route('gradingResult.delete', ['id' => $result->id]))
            ->assertRedirect(route('gradingResult.index'));

        $this->assertDatabaseMissing('grading_results', ['id' => $result->id]);
    }

    /**
     * Every create and edit page renders for a signed-in admin.
     *
     * One test for all eight: the thing that breaks is the same every time - a
     * view name, a variable the template expects and the controller stopped
     * sending, or a partial that moved - and eight near-identical tests would
     * only say it eight times.
     */
    public function test_every_form_page_renders(): void
    {
        $enrollment = $this->createEnrollment('2026-06-30');
        $band = $this->createBand('A', '100');

        $dropOut = $this->createDropOut();
        $certificate = $this->createCertificate();
        $result = $this->createResult($band);

        $pages = [
            ['dropOut.createPage', []],
            ['dropOut.edit', ['id' => $dropOut->id]],
            ['certificate.createPage', []],
            ['certificate.edit', ['id' => $certificate->id]],
            ['grading.createPage', []],
            ['grading.edit', ['id' => $band->id]],
            ['gradingResult.createPage', []],
            ['gradingResult.edit', ['id' => $result->id]],
        ];

        foreach ($pages as [$route, $params]) {
            $this->asAdmin()
                ->get(route($route, $params))
                ->assertOk();
        }

        // the pages that were opened are the four lists too
        foreach (['dropOut.index', 'certificate.index', 'grading.index', 'gradingResult.index'] as $route) {
            $this->asAdmin()->get(route($route))->assertOk();
        }

        $this->assertNotNull($enrollment);
    }

    /**
 * The flash arrives as a top-right toast and the delete prompt as a modal, and
 * neither is a browser dialog any more.
 *
 * Asserted over the whole admin view tree rather than one page, because the way
 * this can rot is quietly: a page adds its own inline alert again, or an old
 * `onclick="return confirm(...)"` survives, and every page it touches is fine on
 * its own. One test over every file catches either.
 */
public function test_the_admin_pages_notify_at_the_top_right_instead_of_a_browser_alert(): void
{
    $layout = file_get_contents(base_path('resources/views/admin/master/master.blade.php'));

    $this->assertStringContainsString('admin/notifications.css', $layout);
    $this->assertStringContainsString('admin/toast.js', $layout);
    $this->assertStringContainsString('admin/confirm.js', $layout);
    // rendered once, by the layout, so no page has to remember to show its flash
    $this->assertStringContainsString("admin.partials.flash", $layout);

    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator(base_path('resources/views/admin'))
    );

    $browserDialogs = [];
    $inlineAlerts = [];

    foreach ($files as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        // normalised so the comparison below holds on Windows, where the
        // iterator hands back backslashes
        $path = str_replace('\\', '/', $file->getPathname());
        $contents = file_get_contents($file->getPathname());

        // the flash partial and the layout that includes it are where the message
        // is *meant* to live; every other view is a page that must not render it
        $isWhereTheFlashLives = str_ends_with($path, 'partials/flash.blade.php')
            || str_ends_with($path, 'master/master.blade.php');

        if (str_contains($contents, 'return confirm(')) {
            $browserDialogs[] = $path;
        }

        if (! $isWhereTheFlashLives
            // matched on the interpolation rather than the word, so a note about
            // the arrangement in a comment is not a hit
            && preg_match('/\{\{\s*session\(\s*[\'"]success[\'"]\s*\)\s*\}\}/', $contents)) {
            $inlineAlerts[] = $path;
        }
    }

    $this->assertSame([], $browserDialogs, 'A view still uses a browser confirm() dialog.');
    $this->assertSame([], $inlineAlerts, 'A view still renders its own flash instead of leaving it to the layout.');

    // and the page really does carry the styled prompt on its delete control
    $this->createDropOut();

    $page = $this->asAdmin()->get(route('dropOut.index', ['course_id' => $this->courseId]));

    $page->assertOk()
        ->assertSee('data-confirm="Delete the drop-out for', escape: false)
        ->assertDontSee('return confirm(', escape: false);

    // the prompt is a modal, not a corner box: a delete takes something away, so
    // the page behind it has to be locked out until the question is answered
    $script = file_get_contents(base_path('public/admin/confirm.js'));

    $this->assertStringContainsString('aria-modal', $script);
    $this->assertStringContainsString('yha-modal-backdrop', $script);
    $this->assertStringContainsString('yha-modal-open', $script);

    // both halves of the answer, and only Delete is the destructive one
    $this->assertStringContainsString('Cancel', $script);
    $this->assertStringContainsString('Delete', $script);
}

/**
     * The admin runs on one Bootstrap, and it is the one the pages are written for.
     *
     * This is the one that bit, and it bit silently: the theme's core.css bundles
     * Bootstrap 5, the pages are written in Bootstrap 5 (`form-select`, `text-bg-*`,
     * `gap-*`, `data-bs-*`), and a Bootstrap 4.3.1 CDN copy was also linked. Being
     * last, it won every tie - so the selects stopped looking like selects, the
     * status badges lost their colours, the `me-*` spacing collapsed and the
     * `data-bs-toggle` dropdowns and modals stopped opening, while the page still
     * returned 200 and the markup still looked correct. Only rendering it shows the
     * clash, so it is asserted as a fact about the loaded stylesheets.
     *
     * The two layout asks that came out of the same review are pinned here too: the
     * floating back button sits on the left, and a table wrapper scrolls instead of
     * clipping, because the tables are wrapped in `text-nowrap`.
     */
    public function test_the_admin_runs_on_the_bootstrap_the_pages_are_written_for(): void
    {
        $layout = file_get_contents(base_path('resources/views/admin/master/master.blade.php'));
        $core = file_get_contents(base_path('public/admin/assets/vendor/css/core.css'));

        // core.css carries Bootstrap 5: these utilities only exist there
        $this->assertStringContainsString('.form-select', $core);
        $this->assertStringContainsString('.btn-close', $core);
        $this->assertStringContainsString('text-bg-success', $core);
        $this->assertStringNotContainsString('.custom-select', $core, 'core.css looks like Bootstrap 4.');

        // ...so the layout must not stack a second, older Bootstrap on top of it
        $this->assertStringNotContainsString('bootstrap.min.css', $layout);
        $this->assertStringNotContainsString('bootstrapcdn.com', $layout);
        $this->assertStringNotContainsString('bootstrap@4', $layout);

        // and the JS has to be the matching build, loaded before the pages run their
        // own inline jQuery: they sit inside @yield('content'), not a pushed stack
        $this->assertStringContainsString('vendor/js/bootstrap.js', $layout);
        $this->assertLessThan(
            strpos($layout, "@yield('content')"),
            strpos($layout, 'vendor/js/bootstrap.js'),
            'Bootstrap loads after the page content, so inline page scripts run without it.'
        );
        $this->assertLessThan(
            strpos($layout, "@yield('content')"),
            strpos($layout, 'libs/jquery/jquery.js'),
            'jQuery loads after the page content, so inline page scripts run without it.'
        );

        // no page inside the layout brings its own Bootstrap or jQuery along and
        // re-loads it mid-page; the standalone invoice and print pages are their
        // own documents, so they are not in scope
        $secondLoad = [];

        foreach ($this->adminViews() as $path => $contents) {
            if (! str_contains($contents, "@extends('admin.master.master')")) {
                continue;
            }

            if (preg_match('/bootstrap(min)?\.(css|js)/', $contents)
                || preg_match('/bootstrap@\d/', $contents)
                || preg_match('/jquery[-\d]|code\.jquery\.com/', $contents)) {
                $secondLoad[] = $path;
            }
        }

        $this->assertSame([], $secondLoad, 'An admin page loads its own copy of Bootstrap or jQuery.');

        // and the page that comes out says so, not just the layout on disk
        $page = $this->asAdmin()->get(route('admin.home'));

        $page->assertOk()
            ->assertSee('admin/assets/vendor/js/bootstrap.js', escape: false)
            ->assertDontSee('bootstrapcdn.com', escape: false)
            ->assertDontSee('bootstrap.min.css', escape: false);

        $tables = file_get_contents(base_path('public/css/admin-tables.css'));

        $this->assertMatchesRegularExpression('/\.btn-back\s*\{[^}]*left:/', $tables);
        $this->assertDoesNotMatchRegularExpression('/\.btn-back\s*\{[^}]*\bright:/', $tables);

        // from 1200px the rail is position:fixed and reserves a 16.25rem gutter, so
        // a button pinned to the left edge of the viewport ends up behind it; it has
        // to track the rail, including the rail collapsing
        $this->assertMatchesRegularExpression(
            '/@media\s*\(min-width:\s*1200px\)\s*\{[^@]*?\.btn-back\s*\{[^}]*--sidebar-width/',
            $tables,
            'The back button does not clear the fixed sidebar, so the rail covers it.'
        );
        $this->assertMatchesRegularExpression('/html\.sidebar-collapsed \.btn-back/', $tables);

        $this->assertDoesNotMatchRegularExpression(
            '/\.table-responsive\s*\{[^}]*overflow:\s*hidden/',
            $tables,
            'A hidden overflow clips the table instead of letting it scroll.'
        );
    }

    /** @return array<string, string> every admin view, keyed by its repo-relative path */
    private function adminViews(): array
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(base_path('resources/views/admin'))
        );

        $views = [];

        foreach ($files as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            // normalised so the keys match on Windows, where the iterator hands
            // back backslashes
            $views[str_replace('\\', '/', $file->getPathname())] = file_get_contents($file->getPathname());
        }

        return $views;
    }

    /**
     * Every sidebar icon exists in the icon font the layout actually loads.
     *
     * A Boxicons class that is not in the bundled build is not an error: the element
     * renders, the markup looks right, the page returns 200 - there is simply no
     * glyph there. The Exam item sat that way with `bx-file-pdf`, which the bundled
     * version has never had, so the item read as a bare word.
     *
     * Only the bundled Boxicons is checked, because that is the one that can rot
     * silently; the Font Awesome icons come from a pinned CDN build.
     */
    public function test_every_sidebar_icon_exists_in_the_bundled_icon_font(): void
    {
        $aside = file_get_contents(base_path('resources/views/admin/components/aside.blade.php'));
        $boxicons = file_get_contents(base_path('public/admin/assets/vendor/fonts/boxicons.css'));

        preg_match_all('/class="menu-icon[^"]*?\b(bx-[a-z0-9-]+)\b/', $aside, $matches);

        $icons = array_unique($matches[1]);

        // a menu with no icons at all would pass the loop below silently
        $this->assertNotEmpty($icons, 'No Boxicons icons were found in the sidebar.');

        $missing = [];

        foreach ($icons as $icon) {
            if (! preg_match('/\.' . preg_quote($icon, '/') . ':before/', $boxicons)) {
                $missing[] = $icon;
            }
        }

        $this->assertSame([], $missing, 'These sidebar icons are not in the bundled Boxicons, so they render as nothing.');
    }

    /**
     * Every sidebar item belongs to a foldable group, and every group is foldable.
     *
     * The headings are plain buttons rather than the template's `.menu-header`
     * because that one fixes itself at the full 16.25rem width and holds only text,
     * so it would force the collapsed rail open and had nothing to click. Items stay
     * flat children of `.menu-inner` so the template's own collapsed-rail metrics,
     * which key off `.menu-inner > .menu-item`, keep applying.
     *
     * Asserted as a pairing rather than a list of expected names: what breaks is an
     * item with a group nobody toggles, or a group with no hiding rule behind it,
     * and either leaves an item that can never be reached or a button that does
     * nothing.
     */
    public function test_the_sidebar_groups_are_collapsible(): void
    {
        $aside = file_get_contents(base_path('resources/views/admin/components/aside.blade.php'));
        $sidebar = file_get_contents(base_path('public/css/admin-sidebar.css'));

        preg_match_all('/<li class="menu-item[^"]*" data-group="([a-z]+)"/', $aside, $items);
        preg_match_all('/<button type="button" class="nav-group-toggle" data-group="([a-z]+)" aria-expanded="true">/', $aside, $headings);

        $groups = array_unique($headings[1]);

        $this->assertNotEmpty($groups, 'No foldable sidebar group was found.');

        // every item sits under a heading, including Dashboard, which is a group of its own
        preg_match_all('/<li class="menu-item[^>]*>/', $aside, $allItems);
        $ungrouped = array_values(array_filter($allItems[0], function ($tag) {
            return ! str_contains($tag, 'data-group');
        }));

        $this->assertSame([], $ungrouped, 'Every sidebar item should sit under a group heading.');

        $this->assertSame([], array_diff($items[1], $groups), 'Some items point at a group with no heading.');
        $this->assertSame([], array_diff($groups, $items[1]), 'Some groups have no items under them.');

        foreach ($groups as $group) {
            $this->assertStringContainsString(
                "html.nav-hide-{$group} #layout-menu .menu-item[data-group=\"{$group}\"]",
                $sidebar,
                "Nothing hides the '{$group}' group when it is folded."
            );
            $this->assertStringContainsString(
                "html.nav-hide-{$group} #layout-menu .nav-group-toggle[data-group=\"{$group}\"] .nav-group-chevron",
                $sidebar,
                "The '{$group}' chevron does not turn when the group is folded."
            );
        }

        // a menu link is a full page load, so the open group has to outlive it
        $this->assertStringContainsString('yha.nav.groups', $aside);
        $this->assertStringContainsString('localStorage.setItem(GROUP_KEY', $aside);

        // accordion, not independent folds: a click re-applies every group rather
        // than only the one clicked, and a single name is what gets remembered
        $this->assertStringContainsString('groupNames.forEach(function (other) {', $aside);
        $this->assertStringContainsString('applyGroup(other, other === name)', $aside);
        $this->assertStringContainsString('JSON.stringify({ open: name })', $aside);

        // the page you are on always wins, so the item you navigated to is never
        // left hidden inside a folded group
        $this->assertStringContainsString(".menu-item.active[data-group]", $aside);

        // and the icon-only rail shows every icon regardless, since it has no
        // heading to fold and no button to undo it with
        $this->assertMatchesRegularExpression(
            '/html\.sidebar-collapsed #layout-menu \.menu-item\[data-group\]\s*\{\s*display:\s*block\s*!important/',
            $sidebar
        );
    }

    /**
     * Below the template's 1200px breakpoint the rail becomes a drawer.
     *
     * The template only pins the menu with `position: fixed` from 1200px up; under
     * that it drops back into the flex row as a 16.25rem block beside the content,
     * so a tablet spent its width on navigation. There is no navbar in this admin,
     * so nothing was left to open a drawer either - the rail was simply always
     * there and always in the way.
     */
    public function test_the_sidebar_becomes_a_drawer_below_the_template_breakpoint(): void
    {
        $aside = file_get_contents(base_path('resources/views/admin/components/aside.blade.php'));
        $sidebar = file_get_contents(base_path('public/css/admin-sidebar.css'));

        // the opener and the backdrop live outside the aside, so the rail keeps the
        // exact markup the template's own script initialises
        $this->assertStringContainsString('id="sidebarDrawerToggle"', $aside);
        $this->assertStringContainsString('sidebar-drawer-backdrop', $aside);

        // the close chevron must not be the template's toggle: that would also flip
        // `layout-menu-collapsed` and quietly collapse the desktop rail afterwards
        $this->assertStringContainsString('sidebar-drawer-close', $aside);
        $this->assertDoesNotMatchRegularExpression('/class="[^"]*layout-menu-toggle[^"]*"/', $aside);

        // parked off the left edge, restored by the open state
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1199\.98px\)\s*\{(?:[^{}]|\{[^{}]*\})*#layout-menu\s*\{[^}]*transform:\s*translateX\(-100%\)/',
            $sidebar
        );
        $this->assertStringContainsString('html.sidebar-open #layout-menu', $sidebar);

        // the opener only exists where there is a drawer to open
        $this->assertMatchesRegularExpression(
            '/\.sidebar-drawer-toggle\s*\{[^}]*display:\s*none/',
            $sidebar,
            'The hamburger shows at desktop width, where the rail is already permanent.'
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1199\.98px\)\s*\{\s*\.sidebar-drawer-toggle\s*\{[^}]*display:\s*inline-flex/',
            $sidebar
        );

        // and it can be opened, closed and dismissed
        foreach (["applyDrawer(false)", 'backdrop.addEventListener', "event.key === 'Escape'"] as $wiring) {
            $this->assertStringContainsString($wiring, $aside);
        }
    }

    /**
     * No admin view points at a developer's own machine.
     *
     * The course -> subject/section/student chain on the timetable form called
     * `http://127.0.0.1:8000/ajax/course/list`, hardcoded in the layout. On a dev
     * machine it worked, so it survived; everywhere else the request left the host
     * and the three selects simply never filled, which looks like a bug in the form
     * rather than a wrong URL.
     */
    public function test_no_admin_view_calls_a_hardcoded_local_address(): void
    {
        $offenders = [];

        foreach ($this->adminViews() as $path => $contents) {
            if (preg_match('#https?://(127\.0\.0\.1|localhost|0\.0\.0\.0)(:\d+)?/#i', $contents, $match)) {
                $offenders[$path] = $match[0];
            }
        }

        $this->assertSame([], $offenders, 'An admin view calls an address hardcoded to a local machine.');
    }

    /**
  * Every delete control in the admin asks first.
 *
 * This is the one that bit: pages whose delete had no prompt at all. Nothing about
 * those pages was broken-looking, they simply removed the record the moment they
 * were clicked - and each page that had one was found by hand, one at a time.
 *
 * So it is checked the way it fails: by finding every link or form that points at
 * a delete, destroy or remove route and asking whether that element carries the
 * prompt. One new delete link anywhere in the admin fails this until it does.
 */
public function test_every_delete_control_in_the_admin_asks_first(): void
{
    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator(base_path('resources/views/admin'))
    );

    $unconfirmed = [];

    foreach ($files as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $contents = file_get_contents($file->getPathname());

        // every route() call that names a destructive action, with its position
        preg_match_all(
            '/route\(\s*[\'"]([a-zA-Z]+)\.(delete|destroy|remove)[^\'"]*[\'"]/',
            $contents,
            $routes,
            PREG_OFFSET_CAPTURE
        );

        foreach ($routes[0] as $hit) {
            $position = $hit[1];

            // The element is found by walking out to its own boundaries rather
            // than by matching the tag: a route() call whose arguments are an
            // array contains "=>", and any attempt to stop the match at the
            // first ">" cuts the tag in half and misses the attribute sitting
            // after it.
            $before = substr($contents, 0, $position);
            $start = max(
                (int) strrpos($before, '<a '),
                (int) strrpos($before, '<a\r'),
                (int) strrpos($before, '<form')
            );

            $after = substr($contents, $position);
            $ends = PHP_INT_MAX;

            foreach (['<a ', '<a\r', '<form'] as $next) {
                $found = strpos($after, $next);

                if ($found !== false) {
                    $ends = min($ends, $found);
                }
            }

            $element = $ends === PHP_INT_MAX
                ? substr($contents, $start)
                : substr($contents, $start, ($position - $start) + $ends);

            if (! str_contains($element, 'data-confirm')) {
                $unconfirmed[] = basename($file->getPathname()) . ': ' . $routes[1][0][0] . '.' . $routes[2][0][0];
            }
        }
    }

    $this->assertSame(
        [],
        $unconfirmed,
        "These delete controls delete without asking:\n" . implode("\n", $unconfirmed)
    );
}

    // --------------------------------------------------------------- fixtures

    private function createDropOut(): DropOut
    {
        return DropOut::create([
            'student_id' => $this->student->id,
            'course_id' => $this->courseId,
            'drop_out_date' => '2026-07-14',
            'remark' => 'Left the course',
        ]);
    }

    private function createCertificate(string $remark = Certificate::NOT_RECEIVED): Certificate
    {
        return Certificate::create([
            'student_id' => $this->student->id,
            'complete_date' => '2026-06-30',
            'remark' => $remark,
        ]);
    }

    private function createBand(string $name, string $score): Grading
    {
        return Grading::create(['name' => $name . '-' . uniqid(), 'score' => $score]);
    }

    private function createResult(?Grading $band = null): GradingResult
    {
        return GradingResult::create([
            'grade_id' => $band?->id,
            'student_id' => $this->student->id,
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'score' => '72',
            'date' => '2026-09-01',
        ]);
    }

    /**
     * A finished enrollment for this student in this course, which is what the
     * certificate form reads its default completion date from.
     *
     * Reused rather than duplicated if the row is already there, because the
     * unique index is on student + course + section and the section is null on
     * purpose. The state is then forced, so the fixture does not depend on what
     * the row happened to say in the dev database.
     *
     * @param  string|null  $completeDate  null leaves the enrollment open
     */
    private function createEnrollment(
        ?string $completeDate = '2026-06-30',
        string $status = StudentEnrollment::STATUS_COMPLETED
    ): StudentEnrollment {
        $enrollment = StudentEnrollment::firstOrCreate(
            ['student_id' => $this->student->id, 'course_id' => $this->courseId, 'section_id' => null],
            ['enroll_date' => Carbon::parse($completeDate ?? '2026-01-01')->subMonths(3)->toDateString()]
        );

        $enrollment->forceFill([
            'status' => $status,
            'complete_date' => $completeDate,
        ])->save();

        return $enrollment->fresh();
    }

    /**
     * Forget that this student has ever finished anything.
     *
     * Several rules read a student's finished enrollments rather than the course
     * in front of them - the certificate form's default date is the obvious one -
     * so a test that wants one particular answer has to remove the others. Done
     * inside the test's transaction, so the dev data goes back untouched.
     */
    private function clearCompletions(): void
    {
        StudentEnrollment::where('student_id', $this->student->id)
            ->update(['complete_date' => null, 'status' => StudentEnrollment::STATUS_ACTIVE]);
    }
}