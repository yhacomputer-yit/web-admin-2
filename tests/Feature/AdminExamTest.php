<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\Subject;
use App\Models\SubjectDetail;
use App\Models\User;
use App\Support\StoredFile;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The admin side of the exam schedule: what an admin can make, and the two
 * consequences that reach the student portal.
 *
 * Those two are why these tests exist rather than a "does the form save" check:
 *
 *  - a paper must land on the private disk. On the public disk it would have a
 *    URL anyone could guess, and the window the student portal enforces would
 *    become advice instead of a gate;
 *
 *  - an unpublished sitting must be invisible to students. That is the whole
 *    purpose of the visibility field, and a save that quietly published a draft
 *    would put an exam in front of a class that was never told.
 *
 * The fixtures are discovered rather than created, for the reason the student
 * exam tests give: these assertions are about the admin's choices and their
 * effect, not about six tables' worth of NOT NULL columns. A database with no
 * course that teaches a subject skips rather than fails, because "no courses" is
 * not a bug in the exam admin.
 *
 * These run against the database named in .env rather than the throwaway one
 * phpunit.xml hands the rest of the suite, because they wrap each test in a
 * transaction that is rolled back instead of migrating, and they need the seeded
 * courses and subjects to already exist.
 *
 * @see \Database\Seeders\ExamDemoSeeder
 * @see \Tests\Feature\StudentExamTest
 */
class AdminExamTest extends TestCase
{
    use DatabaseTransactions;

    /** a valid but tiny PDF: enough for the mime rule */
    private const PDF = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF\n";

    private ?User $admin = null;

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

        $this->fixture();
    }

    /**
     * The rows every test needs: an admin, and a course that teaches a subject.
     *
     * The pair comes from subject_detail rather than being invented, because the
     * form refuses a subject the course does not teach and a test that built its
     * own pair would be testing a pair the form would never accept.
     */
    private function fixture(): void
    {
        $this->admin = User::where('role', '!=', 'user')->first();

        if ($this->admin === null) {
            $this->markTestSkipped('No admin user to sign in as.');
        }

        $pair = SubjectDetail::whereIn('course_id', Course::pluck('id'))
            ->first(['course_id', 'subject_id']);

        if ($pair === null) {
            $this->markTestSkipped('No course that teaches a subject, so there is nothing to schedule.');
        }

        $this->courseId = (int) $pair->course_id;
        $this->subjectId = (int) $pair->subject_id;
    }

    private function asAdmin(): self
    {
        $this->actingAs($this->admin);

        return $this;
    }

    /**
     * A valid payload for this course and subject, one day out so the window is
     * still ahead and nothing here depends on the hour the suite happens to run.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'exam_date' => Carbon::now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'is_published' => ExamQuestion::PUBLISHED,
        ], $overrides);
    }

    // ------------------------------------------------------------- the list

    public function test_the_list_shows_the_sittings_and_their_papers(): void
    {
        $sitting = $this->asAdmin()->createSitting();

        $this->asAdmin()
            ->get(route('exam.index'))
            ->assertOk()
            ->assertViewIs('admin.exam.index')
            ->assertViewHas('exams', fn ($exams) => $exams->contains(
                fn (ExamQuestion $exam) => $exam->id === $sitting->id
                    && $exam->subject?->name === Subject::find($this->subjectId)?->name
                    && $exam->course?->name === Course::find($this->courseId)?->name
            ));
    }

    public function test_the_list_narrows_by_course_subject_and_visibility(): void
    {
        $this->asAdmin()->createSitting();

        $this->asAdmin()
            ->get(route('exam.index', ['subject_id' => $this->subjectId]))
            ->assertOk()
            ->assertViewHas('exams', fn ($exams) => $exams->every(
                fn (ExamQuestion $exam) => $exam->subject_id === $this->subjectId
            ));

        // a visibility that is not one of the two states narrows to nothing
        // rather than quietly falling back to the unfiltered list
        $this->asAdmin()
            ->get(route('exam.index', ['is_published' => 'draft']))
            ->assertOk()
            ->assertViewHas('exams', fn ($exams) => $exams->isEmpty());
    }

    /**
 * The two form pages render, and the sitting's own times come back as something
 * a time input will accept.
 *
 * The form is one partial shared by create and edit, and it hands the times to an
 * `<input type="time">`, which silently empties itself when given anything but a
 * 24-hour value. A view that renders is not a view that works, so this asserts
 * the value the input is actually handed rather than only the status code.
 */
public function test_the_create_and_edit_pages_render_with_usable_time_values(): void
    {
        $exam = $this->asAdmin()->createSitting();

        $this->asAdmin()
            ->get(route('exam.createPage'))
            ->assertOk()
            ->assertViewIs('admin.exam.create')
            ->assertSee('name="start_time"', escape: false)
            ->assertSee('name="end_time"', escape: false);

        $this->asAdmin()
            ->get(route('exam.edit', ['id' => $exam->id]))
            ->assertOk()
            ->assertViewIs('admin.exam.edit')
            // 24-hour, zero padded: the only shape the input takes
            ->assertSee('value="09:00"', escape: false)
            ->assertSee('value="11:00"', escape: false);
    }

    // ------------------------------------------------------------- creating

    public function test_an_admin_can_schedule_an_exam_with_its_paper(): void
    {
        $this->asAdmin()
            ->post(route('exam.create'), $this->payload([
                'question_file' => UploadedFile::fake()->create('paper.pdf', 30, 'application/pdf'),
            ]))
            ->assertRedirect(route('exam.index'))
            ->assertSessionHas('success');

        $exam = ExamQuestion::where('course_id', $this->courseId)
            ->where('subject_id', $this->subjectId)
            ->latest('id')
            ->firstOrFail();

        $this->assertSame('09:00', substr($exam->start_time, 0, 5));
        $this->assertSame('11:00', substr($exam->end_time, 0, 5));
        $this->assertTrue($exam->isPublished());

        // the paper is on the private disk and readable there -- and nowhere else
        $this->assertNotNull($exam->question_file);
        $this->assertTrue(Storage::disk(ExamQuestion::PAPER_DISK)->exists($exam->question_file));
        $this->assertFalse(Storage::disk('public')->exists($exam->question_file));
    }

    public function test_a_sitting_can_be_saved_without_a_paper(): void
    {
        $this->asAdmin()
            ->post(route('exam.create'), $this->payload(['is_published' => ExamQuestion::UNPUBLISHED]))
            ->assertRedirect(route('exam.index'));

        $exam = ExamQuestion::latest('id')->firstOrFail();

        $this->assertNull($exam->question_file);
        $this->assertFalse($exam->isPublished());
    }

    public function test_a_window_that_runs_backwards_is_refused(): void
    {
        $this->asAdmin()
            ->post(route('exam.create'), $this->payload(['start_time' => '11:00', 'end_time' => '09:00']))
            ->assertSessionHasErrors('end_time');

        $this->assertSame(
            0,
            ExamQuestion::where('exam_date', $this->payload()['exam_date'])->count(),
            'A refused window must not leave a sitting behind.'
        );
    }

    public function test_a_subject_the_course_does_not_teach_is_refused(): void
    {
        $other = Subject::whereNotIn('id', function ($query) {
            $query->select('subject_id')->from('subject_detail')->where('course_id', $this->courseId);
        })->first();

        if ($other === null) {
            $this->markTestSkipped('Every subject is linked to every course, so there is no outsider to test with.');
        }

        $this->asAdmin()
            ->post(route('exam.create'), $this->payload(['subject_id' => $other->id]))
            ->assertSessionHasErrors('subject_id');
    }

    public function test_a_paper_that_is_not_a_pdf_is_refused(): void
    {
        $this->asAdmin()
            ->post(route('exam.create'), $this->payload([
                'question_file' => UploadedFile::fake()->create('paper.php', 4, 'text/plain'),
            ]))
            ->assertSessionHasErrors('question_file');
    }

    // ------------------------------------------------------------- updating

    public function test_editing_the_window_leaves_the_paper_alone(): void
    {
        $exam = $this->asAdmin()->createSitting();

        $original = $exam->question_file;

        $this->asAdmin()
            ->post(route('exam.update', ['id' => $exam->id]), $this->payload([
                'start_time' => '13:00',
                'end_time' => '15:00',
            ]))
            ->assertRedirect(route('exam.index'));

        $exam->refresh();

        $this->assertSame('13:00', substr($exam->start_time, 0, 5));
        $this->assertSame($original, $exam->question_file, 'An edit with no new upload must keep the paper.');
        $this->assertTrue(Storage::disk(ExamQuestion::PAPER_DISK)->exists($original));
    }

    public function test_a_new_paper_replaces_the_old_one_on_disk(): void
    {
        $exam = $this->asAdmin()->createSitting();

        $original = $exam->question_file;

        $this->asAdmin()
            ->post(route('exam.update', ['id' => $exam->id]), $this->payload([
                'question_file' => UploadedFile::fake()->create('revised.pdf', 30, 'application/pdf'),
            ]))
            ->assertRedirect(route('exam.index'));

        $replacement = $exam->fresh()->question_file;

        $this->assertNotSame($original, $replacement);
        $this->assertTrue(Storage::disk(ExamQuestion::PAPER_DISK)->exists($replacement));

        // the replaced paper is not left behind: a deleted upload that outlives
        // its row is a file on disk nobody can reach and nobody can clean up
        $this->assertFalse(Storage::disk(ExamQuestion::PAPER_DISK)->exists($original));
    }

    public function test_visibility_can_be_withdrawn_and_restored(): void
    {
        $exam = $this->asAdmin()->createSitting();

        $this->assertTrue($exam->isPublished());

        $this->asAdmin()
            ->post(route('exam.update', ['id' => $exam->id]), $this->payload([
                'is_published' => ExamQuestion::UNPUBLISHED,
            ]))
            ->assertRedirect(route('exam.index'));

        $this->assertFalse($exam->fresh()->isPublished());

        $this->asAdmin()
            ->post(route('exam.update', ['id' => $exam->id]), $this->payload([
                'is_published' => ExamQuestion::PUBLISHED,
            ]))
            ->assertRedirect(route('exam.index'));

        $this->assertTrue($exam->fresh()->isPublished());
    }

    // ------------------------------------------------------------- deleting

    /**
     * Deleting a sitting takes its paper with it but leaves the scripts alone.
     *
     * The asymmetry is the schema, not an oversight: `exam_answers` has no
     * exam_question_id, so a script is filed against a course and a subject and
     * outlives any one sitting of it. Deleting a sitting must not take a
     * student's submitted work with it.
     */
    public function test_deleting_a_sitting_removes_the_paper_and_keeps_the_answers(): void
    {
        $exam = $this->asAdmin()->createSitting();

        Storage::disk('public')->put('exam/answers/kept.pdf', self::PDF);

        $studentId = ExamAnswer::query()->value('student_id');

        if ($studentId !== null) {
            ExamAnswer::updateOrCreate(
                [
                    'course_id' => $exam->course_id,
                    'subject_id' => $exam->subject_id,
                    'student_id' => $studentId,
                ],
                ['answer_file' => 'exam/answers/kept.pdf', 'submitted_date' => now()]
            );
        }

        $paper = $exam->question_file;

        $this->asAdmin()
            ->get(route('exam.delete', ['id' => $exam->id]))
            ->assertRedirect(route('exam.index'));

        $this->assertNull(ExamQuestion::find($exam->id));
        $this->assertFalse(Storage::disk(ExamQuestion::PAPER_DISK)->exists($paper));

        if ($studentId !== null) {
            $this->assertDatabaseHas('exam_answers', [
                'course_id' => $exam->course_id,
                'subject_id' => $exam->subject_id,
                'student_id' => $studentId,
            ]);
        }
    }

    /**
     * The inbox a sitting's scripts are read from: the students who
     * turned something in for the sitting's course and subject, with
     * the file itself linked from the disk it was stored on.
     */
    public function test_the_scripts_page_lists_each_student_and_their_file(): void
    {
        $exam = $this->asAdmin()->createSitting();

        $studentId = ExamAnswer::query()->value('student_id');

        if ($studentId === null) {
            $this->markTestSkipped('No student to hand in a script.');
        }

        $path = 'exam/answers/scripts-page.pdf';
        Storage::disk('public')->put($path, self::PDF);

        ExamAnswer::updateOrCreate(
            [
                'course_id' => $exam->course_id,
                'subject_id' => $exam->subject_id,
                'student_id' => $studentId,
            ],
            ['answer_file' => $path, 'submitted_date' => now()]
        );

        $this->asAdmin()
            ->get(route('exam.scripts', ['id' => $exam->id]))
            ->assertOk()
            ->assertSee(StoredFile::label($path))
            ->assertSee(Storage::url($path));
    }

    /**
     * A sitting nothing has been handed in for says so, rather than
     * rendering a table with nothing in it.
     */
    public function test_the_scripts_page_says_so_when_nothing_is_handed_in(): void
    {
        $exam = $this->asAdmin()->createSitting();

        ExamAnswer::query()
            ->where('course_id', $exam->course_id)
            ->where('subject_id', $exam->subject_id)
            ->delete();

        $this->asAdmin()
            ->get(route('exam.scripts', ['id' => $exam->id]))
            ->assertOk()
            ->assertSee('No scripts handed in yet');
    }

    // ------------------------------------- the effect on the student portal

    /**
     * The reason the visibility field exists: an unpublished sitting is not a row
     * a student can probe by its id, so unpublishing takes it out of reach rather
     * than only out of the list.
     */
    public function test_an_unpublished_sitting_is_invisible_to_a_student(): void
    {
        $exam = $this->asAdmin()->createSitting();

        $student = \App\Models\Student::query()->first();

        if ($student === null) {
            $this->markTestSkipped('No student to sign in as.');
        }

        $this->actingAs($student, 'student')->get(route('student.exam'))->assertOk();

        $this->asAdmin()
            ->post(route('exam.update', ['id' => $exam->id]), $this->payload([
                'is_published' => ExamQuestion::UNPUBLISHED,
            ]))
            ->assertRedirect(route('exam.index'));

        $this->actingAs($student, 'student')
            ->get(route('student.exam.show', ['examId' => $exam->id]))
            ->assertNotFound();

        $this->actingAs($student, 'student')
            ->get(route('student.exam.paper', ['examId' => $exam->id]))
            ->assertNotFound();
    }

    // ---- helpers ----------------------------------------------------------

    /**
     * A sitting saved through the real endpoint, so the tests exercise the same
     * path an admin's form takes rather than writing the row directly.
     */
    private function createSitting(): ExamQuestion
    {
        $this->asAdmin()->post(route('exam.create'), $this->payload([
            'question_file' => UploadedFile::fake()->create('paper.pdf', 30, 'application/pdf'),
        ]))->assertRedirect(route('exam.index'));

        return ExamQuestion::latest('id')->firstOrFail();
    }
}