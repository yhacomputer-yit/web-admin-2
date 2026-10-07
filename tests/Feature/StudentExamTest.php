<?php

namespace Tests\Feature;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\Student;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * The three gates on a sitting, from a student's point of view.
 *
 * Every test here is about a refusal rather than a happy path: the list has to
 * hide an unpublished sitting, the sitting page and the paper have to refuse a
 * window that is not open, and the submission has to refuse one that has shut.
 * Those are the assertions that matter, because each of them is the difference
 * between the feature working and the feature being a shared download link with
 * a countdown drawn on top.
 *
 * The fixtures are discovered rather than created. Building a course and a
 * student by hand would mean reproducing six tables' worth of NOT NULL columns
 * for a test about access control, and the only thing these tests need from the
 * database is a student enrolled in a course that teaches a subject -- which the
 * seeded data has. A database with no such row skips instead of failing, because
 * "no exam data" is not a bug in the exam system.
 *
 * The rest of the suite runs against yhaproject_testing, because those tests
 * use RefreshDatabase and would drop the development database to do it. These
 * do not drop anything -- each one is wrapped in a transaction that is rolled
 * back -- and they need the seeded portal data to exist, so they point themselves
 * back at the development database before touching it.
 *
 * @see \Database\Seeders\ExamDemoSeeder
 */
class StudentExamTest extends TestCase
{
    use DatabaseTransactions;

    /** a valid but tiny PDF: enough for the mime rule and for streaming */
    private const PDF = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF\n";

    private ?Student $student = null;

    private ?int $courseId = null;

    private ?int $subjectId = null;

    /**
     * Point this test at the database named in .env rather than the one
     * phpunit.xml hands the rest of the suite.
     *
     * Done while the application is being built, because DatabaseTransactions
     * opens its transaction as part of the standard setUp -- changing the
     * connection afterwards would leave the transaction on the wrong database.
     */
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql.database', $this->developmentDatabase());

        return $app;
    }

    /**
     * The database named in .env, read from the file rather than through env().
     *
     * env() would answer with phpunit.xml's value, which is the throwaway
     * database this method exists to get away from.
     */
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

        Storage::fake(ExamQuestion::PAPER_DISK);
        Storage::fake('public');

        $this->fixture();
    }

    /**
     * The rows every test needs: an active student, a course they are enrolled
     * in, and one subject that course teaches.
     */
    private function fixture(): void
    {
        $pair = DB::table('student_enrollments as se')
            ->join('students', 'students.id', '=', 'se.student_id')
            ->join('subject_detail as sd', 'sd.course_id', '=', 'se.course_id')
            ->where('students.status', 'active')
            ->orderBy('se.student_id')
            ->orderBy('sd.subject_id')
            ->first(['se.student_id', 'se.course_id', 'sd.subject_id']);

        if ($pair === null) {
            $this->markTestSkipped('No active student enrolled in a course that teaches a subject.');
        }

        $this->student = Student::find($pair->student_id);
        $this->courseId = (int) $pair->course_id;
        $this->subjectId = (int) $pair->subject_id;
    }

    /**
     * A sitting in a given state, with a paper on the private disk.
     *
     * The window is derived from `now()` rather than written down, because the
     * assertions are about the relationship between the clock and the window,
     * and a fixed date stops testing that the day after it is written.
     *
     * The subject name comes from the courses and subjects already in the
     * database, so the assertions name the subject the way the page will.
     */
    private function sitting(string $state): ExamQuestion
    {
        [$day, $start, $end] = match ($state) {
            // opened a few minutes ago, not an hour: a fixture opened
            // long enough ago is one whose window may already have shut
            'open' => [Carbon::now(), Carbon::now()->subMinutes(5), Carbon::now()->addHours(2)],
            // a window that is still ahead of us, on a day that is too
            default => [Carbon::now()->addDay(), Carbon::now()->addHours(9), Carbon::now()->addHours(12)],
        };

        $published = $state === 'unpublished' ? 'unpublish' : ExamQuestion::PUBLISHED;

        ExamQuestion::updateOrCreate(
            ['course_id' => $this->courseId, 'subject_id' => $this->subjectId],
            [
                'exam_date' => $day->toDateString(),
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'is_published' => $published,
            ]
        );

        $sitting = ExamQuestion::where('course_id', $this->courseId)
            ->where('subject_id', $this->subjectId)
            ->firstOrFail();

        if ($published !== ExamQuestion::PUBLISHED) {
            // nothing to see, so nothing to put behind the path either
            return $sitting;
        }

        $path = ExamQuestion::PAPER_DIRECTORY . '/test-' . $sitting->id . '-paper.pdf';
        Storage::disk(ExamQuestion::PAPER_DISK)->put($path, self::PDF);
        $sitting->update(['question_file' => $path]);

        return $sitting;
    }

    /**
     * A sitting that has already closed.
     *
     * Kept separate from sitting() because a closed window is not a window at all:
     * it is the same day's start and end with both behind the clock.
     */
    private function closedSitting(): ExamQuestion
    {
        $sitting = $this->sitting('open');

        $day = Carbon::now();

        $sitting->update([
            'exam_date' => $day->toDateString(),
            'start_time' => $day->copy()->subHours(4)->format('H:i:s'),
            'end_time' => $day->copy()->subHours(2)->format('H:i:s'),
        ]);

        return $sitting->fresh();
    }

    /**
     * A running sitting whose window opened a given number of minutes
     * ago, with its end an hour ahead: a point at a chosen distance
     * into a window that is still comfortably open.
     */
    private function sittingOpenedFor(int $minutes): ExamQuestion
    {
        $sitting = $this->sitting('open');

        $now = Carbon::now();

        $sitting->update([
            'exam_date' => $now->toDateString(),
            'start_time' => $now->copy()->subMinutes($minutes)->format('H:i:s'),
            // an end an hour ahead, so every distance into
            // the window is still comfortably inside it
            'end_time' => $now->copy()->addHour()->format('H:i:s'),
        ]);

        return $sitting->fresh();
    }

    /**
     * A sitting whose own time ended a given number of minutes
     * ago. Under the grace period's length that is the state the
     * grace period is about: the questions gone, the upload still
     * open. Past it, the sitting is closed in every way at once.
     */
    private function sittingEndedFor(int $minutes): ExamQuestion
    {
        $sitting = $this->sitting('open');

        $now = Carbon::now();

        $sitting->update([
            'exam_date' => $now->toDateString(),
            'start_time' => $now->copy()->subMinutes($minutes + 30)->format('H:i:s'),
            'end_time' => $now->copy()->subMinutes($minutes)->format('H:i:s'),
        ]);

        return $sitting->fresh();
    }

    private function asStudent(): self
    {
        $this->actingAs($this->student, 'student');

        return $this;
    }

    /**
     * The name the page will show for the fixture's subject.
     */
    private function subjectName(): string
    {
        return (string) \App\Models\Subject::find($this->subjectId)?->name;
    }

    /**
     * Every card on the list, flattened out of the subject grouping.
     *
     * The list is grouped by subject because that is how the page reads it, so
     * an assertion about "a sitting" has to look inside the groups. Flattening
     * here keeps that from being spelled out in each test.
     *
     * @param  mixed  $groups  the `groups` prop
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function cards($groups): Collection
    {
        return collect($groups)->flatMap(fn (array $subject) => $subject['exams']);
    }

    // ---------------------------------------------------------------- the list

    public function test_the_list_shows_a_published_sitting_to_an_enrolled_student(): void
    {
        $sitting = $this->sitting('open');

        $this->asStudent()
            ->get(route('student.exam'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('StudentExams')
                ->where('groups', fn ($groups) => $this->cards($groups)->contains(
                    fn ($row) => $row['id'] === $sitting->id
                        && $row['subject_name'] === $this->subjectName()
                        && $row['status'] === ExamQuestion::ONGOING
                        && $row['can_open'] === true
                ))
                // grouped under the subject, which is the heading the cards sit
                // beneath, and a card carries nothing about the script
                ->where('groups', fn ($groups) => collect($groups)->contains(
                    fn ($subject) => $subject['name'] === $this->subjectName()
                        && collect($subject['exams'])->contains(fn ($exam) => $exam['id'] === $sitting->id)
                ))
            );
    }

    public function test_the_list_hides_an_unpublished_sitting_entirely(): void
    {
        $this->sitting('unpublished');

        $this->asStudent()
            ->get(route('student.exam'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('groups', fn ($groups) => ! $this->cards($groups)->contains(
                    fn ($row) => $row['subject_id'] === $this->subjectId
                ))
            );
    }

    public function test_the_list_never_hands_out_a_paper_url_before_the_window_opens(): void
    {
        $sitting = $this->sitting('upcoming');

        $this->asStudent()
            ->get(route('student.exam'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('groups', fn ($groups) => $this->cards($groups)->every(
                    fn ($row) => $row['id'] !== $sitting->id || $row['can_open'] === false
                ))
            );
    }

    /**
     * A card carries the sitting and whether it is answered, and nothing about
     * the file the student uploaded or the moment they uploaded it: a schedule is
     * not a file listing, so those keys are not sent to the list at all.
     */
    public function test_the_list_reports_a_submitted_script_without_the_script(): void
    {
        $sitting = $this->sitting('open');

        Storage::disk('public')->put('exam/answers/test.pdf', self::PDF);

        ExamAnswer::updateOrCreate(
            [
                'course_id' => $sitting->course_id,
                'subject_id' => $sitting->subject_id,
                'student_id' => $this->student->id,
            ],
            ['answer_file' => 'exam/answers/test.pdf', 'submitted_date' => now()]
        );

        $this->asStudent()
            ->get(route('student.exam'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('groups', fn ($groups) => $this->cards($groups)->contains(
                    fn ($row) => $row['id'] === $sitting->id
                        && $row['submitted'] === true
                        && ! array_key_exists('answer_name', $row)
                        && ! array_key_exists('submitted_label', $row)
                        && ! array_key_exists('answer_url', $row)
                ))
            );
    }

    public function test_the_list_filters_by_course_and_subject(): void
    {
        $sitting = $this->sitting('open');

        $this->asStudent()
            ->get(route('student.exam', ['subject_id' => $this->subjectId]))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('filters.subject_id', $this->subjectId)
                ->where('groups', fn ($groups) => $this->cards($groups)->every(
                    fn ($row) => $row['subject_id'] === $this->subjectId
                ))
                ->where('groups', fn ($groups) => $this->cards($groups)->contains(
                    fn ($row) => $row['id'] === $sitting->id
                ))
            );

        // a course with no published sittings narrows to nothing rather than
        // quietly falling back to the unfiltered list
        $this->asStudent()
            ->get(route('student.exam', ['course_id' => $this->courseId + 99999]))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('summary.total', 0)
                ->where('groups', fn ($groups) => $this->cards($groups)->isEmpty())
            );
    }

    // ------------------------------------------------------------- the sitting

    public function test_the_sitting_page_renders_only_while_the_window_is_open(): void
    {
        $sitting = $this->sitting('open');

        $this->asStudent()
            ->get(route('student.exam.show', ['examId' => $sitting->id]))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('StudentExamTake')
                ->where('exam.id', $sitting->id)
                // the countdown is built from the server's own clock against the
                // sitting's own end time, so this is what the page
                // counts down to
                ->where('exam.ends_at', $sitting->endsAt()->toIso8601String())
                ->where('exam.submit_open', true)
                ->where('exam.paper_url', route('student.exam.paper', ['examId' => $sitting->id]))
                ->has('server_time')
            );
    }

    /**
 * Times are shown in 12-hour form with the meridiem spelled out, because that is
 * how the school reads them and because "9:00" beside "11:00" on a card grid is
 * ambiguous where "9:00 AM" is not.
 *
 * Pinned here rather than left to the formatter: a change back to 24-hour would
 * still render, still be readable, and quietly make an evening exam look like a
 * morning one.
 */
public function test_times_are_shown_in_twelve_hour_form(): void
    {
        $sitting = $this->sitting('open');

        $this->asStudent()
            ->get(route('student.exam'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('groups', function ($groups) use ($sitting) {
                    $card = $this->cards($groups)->firstWhere('id', $sitting->id);

                    if ($card === null) {
                        return false;
                    }

                    $oneTime = '/^\d{1,2}:\d{2} (AM|PM)$/';
                    $aRange = '/^\d{1,2}:\d{2} (AM|PM) - \d{1,2}:\d{2} (AM|PM)$/';

                    // both the range and the single times the dialog quotes are
                    // 12-hour, and neither has a leading zero, which is what
                    // distinguishes them from the "H:i" the column stores
                    return preg_match($aRange, $card['time_label']) === 1
                        && preg_match($oneTime, $card['start_time']) === 1
                        && preg_match($oneTime, $card['end_time']) === 1;
                })
            );
    }

    public function test_the_sitting_page_refuses_a_student_before_the_start_time(): void
    {
        $sitting = $this->sitting('upcoming');

        $this->asStudent()
            ->get(route('student.exam.show', ['examId' => $sitting->id]))
            ->assertRedirect(route('student.exam'))
            ->assertSessionHas('error', 'Exam time has not started yet. It opens at '
                . \App\Support\TimeOfDay::format($sitting->start_time) . '.');
    }

    public function test_the_sitting_page_refuses_a_student_after_the_end_time(): void
    {
        $sitting = $this->closedSitting();

        $this->asStudent()
            ->get(route('student.exam.show', ['examId' => $sitting->id]))
            ->assertRedirect(route('student.exam'))
            ->assertSessionHas('error', 'Exam time is over. It closed at '
                . \App\Support\TimeOfDay::format($sitting->end_time)
                . ', and submissions stopped at '
                . \App\Support\TimeOfDay::format($sitting->submissionClosesAt()) . '.');
    }

    public function test_a_sitting_of_a_course_the_student_is_not_enrolled_in_is_not_found(): void
    {
        $sitting = $this->sitting('open');

        $outsider = Student::where('status', 'active')
            ->whereNotIn('id', DB::table('student_enrollments')->select('student_id'))
            ->first();

        if ($outsider === null) {
            $this->markTestSkipped('Every active student is enrolled, so there is no outsider to test with.');
        }

        $this->actingAs($outsider, 'student')
            ->get(route('student.exam.show', ['examId' => $sitting->id]))
            ->assertNotFound();
    }

    // -------------------------------------------------------------- the paper

    public function test_the_paper_is_streamed_while_the_window_is_open(): void
    {
        $sitting = $this->sitting('open');

        $response = $this->asStudent()->get(route('student.exam.paper', ['examId' => $sitting->id]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        // a file response marks itself public while it is built, so the cache
        // directives are the part worth asserting: a copy the browser or a shared
        // proxy kept would outlive the window the gate is protecting
        $cache = (string) $response->headers->get('Cache-Control');
        $this->assertStringContainsString('no-store', $cache);
        $this->assertStringContainsString('private', $cache);

        // and the bytes served are the paper on the private disk, not something
        // reachable by URL
        $this->assertSame(
            Storage::disk(ExamQuestion::PAPER_DISK)->path($sitting->question_file),
            $response->baseResponse->getFile()->getPathname()
        );
        $this->assertStringStartsWith('%PDF', file_get_contents($response->baseResponse->getFile()->getPathname()));
    }

    public function test_the_paper_is_refused_before_the_start_time(): void
    {
        $sitting = $this->sitting('upcoming');

        $this->asStudent()
            ->get(route('student.exam.paper', ['examId' => $sitting->id]))
            ->assertForbidden();
    }

    public function test_the_paper_is_refused_after_the_end_time(): void
    {
        $sitting = $this->closedSitting();

        $this->asStudent()
            ->get(route('student.exam.paper', ['examId' => $sitting->id]))
            ->assertForbidden();
    }

    public function test_the_paper_is_not_reachable_without_authentication(): void
    {
        $sitting = $this->sitting('open');

        $this->get(route('student.exam.paper', ['examId' => $sitting->id]))
            ->assertRedirect(route('login'));
    }

    public function test_a_paper_with_no_file_behind_it_is_not_found(): void
    {
        $sitting = $this->sitting('open');

        // the row still points at the paper, but the file is gone: a restore
        // that skipped storage, or a file removed off disk
        Storage::disk(ExamQuestion::PAPER_DISK)->delete($sitting->question_file);

        $this->asStudent()
            ->get(route('student.exam.paper', ['examId' => $sitting->id]))
            ->assertNotFound();
    }

    // ------------------------------------------------------------- the script

    public function test_a_student_can_submit_a_script_while_the_window_is_open(): void
    {
        $sitting = $this->sitting('open');

        $this->asStudent()
            ->post(route('student.exam.submit', ['examId' => $sitting->id]), [
                'file' => UploadedFile::fake()->create('script.pdf', 40, 'application/pdf'),
            ])
            ->assertRedirect(route('student.exam'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('exam_answers', [
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'student_id' => $this->student->id,
        ]);
    }

    public function test_submitting_again_replaces_the_script_rather_than_adding_one(): void
    {
        $sitting = $this->sitting('open');

        foreach (['first.pdf', 'second.pdf'] as $name) {
            $this->asStudent()->post(route('student.exam.submit', ['examId' => $sitting->id]), [
                'file' => UploadedFile::fake()->create($name, 40, 'application/pdf'),
            ])->assertRedirect(route('student.exam'));
        }

        $this->assertSame(1, ExamAnswer::forPair($this->courseId, $this->subjectId, $this->student->id)->count());
    }

    public function test_a_submission_after_the_window_shuts_is_refused(): void
    {
        $sitting = $this->closedSitting();

        $this->asStudent()
            ->from(route('student.exam'))
            ->post(route('student.exam.submit', ['examId' => $sitting->id]), [
                'file' => UploadedFile::fake()->create('late.pdf', 40, 'application/pdf'),
            ])
            ->assertRedirect(route('student.exam'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('exam_answers', [
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'student_id' => $this->student->id,
        ]);
    }

    /**
     * The page is handed the sitting's own end time, the moment
     * the grace period ends, and the server's clock, so its two
     * countdowns, the paper and the upload form all run on the
     * deadlines the endpoints also draw. A student whose time has
     * run out is not even handed the paper's URL.
     */
    public function test_the_sitting_page_is_told_the_deadline_and_the_clock(): void
    {
        $open = $this->sittingOpenedFor(2);

        $this->asStudent()
            ->get(route('student.exam.show', ['examId' => $open->id]))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('exam.can_open', true)
                ->where('exam.ends_at', $open->endsAt()->toIso8601String())
                ->where('exam.submit_closes_at', $open->submissionClosesAt()->toIso8601String())
                ->where('exam.submit_open', true)
                ->where('exam.paper_url', route('student.exam.paper', ['examId' => $open->id]))
                ->where('exam.seconds_remaining', fn ($seconds) => $seconds > 0)
                ->where('exam.submit_seconds_remaining', fn ($seconds) => $seconds > 0)
            );
    }

    /**
     * The grace period: the exam's own time has run out, so the
     * questions are gone -- the paper is refused even by URL -- but
     * the page still renders and a script can still be handed in
     * until the grace period ends.
     */
    public function test_the_grace_period_hides_the_paper_but_keeps_the_upload_open(): void
    {
        $sitting = $this->sittingEndedFor(5);

        // the exam itself is over, so only the grace period can be
        // what keeps the page reachable and the paper refused
        $this->assertTrue($sitting->isSubmitOpen());
        $this->assertFalse($sitting->isPaperOpen());

        $this->asStudent()
            ->get(route('student.exam.paper', ['examId' => $sitting->id]))
            ->assertForbidden();

        $this->asStudent()
            ->get(route('student.exam.show', ['examId' => $sitting->id]))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('exam.paper_url', null)
                ->where('exam.submit_open', true)
                ->where('exam.submit_closes_at', $sitting->submissionClosesAt()->toIso8601String())
                ->where('exam.submit_seconds_remaining', fn ($seconds) => $seconds > 0)
            );

        $this->asStudent()
            ->from(route('student.exam'))
            ->post(route('student.exam.submit', ['examId' => $sitting->id]), [
                'file' => UploadedFile::fake()->create('grace.pdf', 40, 'application/pdf'),
            ])
            ->assertRedirect(route('student.exam'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('exam_answers', [
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'student_id' => $this->student->id,
        ]);
    }

    /**
     * Once the grace period is gone the sitting is closed in every
     * way at once: the page sends the student back to the list and
     * the submit endpoint refuses the script.
     */
    public function test_a_submission_after_the_grace_period_is_refused(): void
    {
        $sitting = $this->sittingEndedFor(ExamQuestion::SUBMIT_WINDOW_MINUTES + 5);

        $this->asStudent()
            ->from(route('student.exam'))
            ->post(route('student.exam.submit', ['examId' => $sitting->id]), [
                'file' => UploadedFile::fake()->create('toolate.pdf', 40, 'application/pdf'),
            ])
            ->assertRedirect(route('student.exam'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('exam_answers', [
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'student_id' => $this->student->id,
        ]);

        $this->asStudent()
            ->get(route('student.exam.show', ['examId' => $sitting->id]))
            ->assertRedirect(route('student.exam'));
    }

    public function test_a_file_that_is_not_a_script_is_refused(): void
    {
        $sitting = $this->sitting('open');

        $this->asStudent()
            ->post(route('student.exam.submit', ['examId' => $sitting->id]), [
                'file' => UploadedFile::fake()->create('payload.php', 4, 'text/plain'),
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseMissing('exam_answers', [
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'student_id' => $this->student->id,
        ]);
    }
}
