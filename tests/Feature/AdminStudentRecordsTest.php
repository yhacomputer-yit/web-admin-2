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
}