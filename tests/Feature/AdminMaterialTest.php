<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Material;
use App\Models\Subject;
use App\Models\SubjectDetail;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The admin side of the subject resources: the list an admin
 * scans, the form a file is added through, and the one rule
 * that keeps the two honest.
 *
 * That rule is why these tests exist rather than a "does the
 * form save" check: a subject may only hold a file when the
 * course teaches it, because the student portal groups a
 * course's subjects and a file under a subject the course
 * does not teach would be filed somewhere a student cannot
 * reach it.
 *
 * The fixtures are discovered rather than created, for the
 * reason the exam tests give: these assertions are about the
 * admin's choices and their effect, not about five tables'
 * worth of NOT NULL columns. A database with no course that
 * teaches a subject skips rather than fails, because "no
 * courses" is not a bug in the resource list.
 *
 * These run against the database named in .env rather than the
 * throwaway one phpunit.xml hands the rest of the suite, because
 * they wrap each test in a transaction that is rolled back
 * instead of migrating, and they need the seeded courses and
 * subjects to already exist.
 */
class AdminMaterialTest extends TestCase
{
    use DatabaseTransactions;

    private ?User $admin = null;

    private ?int $courseId = null;

    private ?int $subjectId = null;

    /** a minimal PDF, so a row can point at a readable file */
    private const PDF = '%PDF-1.4 admin material test fixture';

    /**
     * Point this test at the database named in .env rather than the
     * one phpunit.xml hands the rest of the suite.
     *
     * Done while the application is being built, because
     * DatabaseTransactions opens its transaction as part of the
     * standard setUp -- changing the connection afterwards would
     * leave the transaction on the wrong database.
     */
    public function createApplication(): Application
    {
        $app = parent::createApplication();

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
     * The rows every test needs: an admin, and a course that
     * teaches a subject.
     *
     * The pair comes from subject_detail rather than being invented,
     * because the form refuses a subject the course does not teach
     * and a test that built its own pair would be testing a pair the
     * form would never accept.
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
            $this->markTestSkipped('No course that teaches a subject, so there is nothing to attach a file to.');
        }

        $this->courseId = (int) $pair->course_id;
        $this->subjectId = (int) $pair->subject_id;
    }

    private function asAdmin(): self
    {
        $this->actingAs($this->admin);

        return $this;
    }

    /** @test */
    public function test_the_list_renders_grouped_by_course_and_subject(): void
    {
        $this->asAdmin()
            ->get(route('material.index'))
            ->assertOk()
            ->assertSee('Subject Resource');
    }

    /** @test */
    public function test_the_create_page_offers_the_three_file_kinds(): void
    {
        $this->asAdmin()
            ->get(route('material.createPage'))
            ->assertOk()
            ->assertSee('Book (PDF)')
            ->assertSee('Lecture recording')
            ->assertSee('Archive (ZIP)');
    }

    /** @test */
    public function test_an_admin_can_upload_a_file_to_a_subject(): void
    {
        $this->asAdmin()
            ->post(route('material.create'), [
                'course_id' => $this->courseId,
                'subject_id' => $this->subjectId,
                'type' => 'book',
                'title' => 'Admin material test book',
                'file' => UploadedFile::fake()->create('book.pdf', 30, 'application/pdf'),
            ])
            ->assertRedirect(route('material.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reference', [
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'type' => 'book',
            'title' => 'Admin material test book',
        ]);

        // the transaction gives the row back, but the file it
        // points at is on a disk, so it is taken off by hand
        $stored = Material::where('title', 'Admin material test book')->first();

        if ($stored !== null) {
            Storage::disk('public')->delete($stored->file_link);
        }
    }

    /** @test */
    public function test_a_subject_the_course_does_not_teach_is_refused(): void
    {
        // a subject the chosen course does not teach at all, which is
        // the one the form has to refuse: the link table is what the
        // student portal groups a course's subjects by
        $taught = SubjectDetail::where('course_id', $this->courseId)
            ->pluck('subject_id');

        $other = Subject::whereNotIn('id', $taught)->first();

        if ($other === null) {
            $this->markTestSkipped('Every subject is linked to this course, so the link rule cannot be tested.');
        }

        $this->asAdmin()
            ->post(route('material.create'), [
                'course_id' => $this->courseId,
                'subject_id' => $other->subject_id,
                'type' => 'book',
                'file' => UploadedFile::fake()->create('book.pdf', 30, 'application/pdf'),
            ])
            ->assertSessionHasErrors('subject_id');
    }

    /**
     * A subject unlinked after the file was saved must still be
     * offered on the edit page, because the row points at it and
     * an admin has to be able to correct the row rather than lose
     * it to a select that no longer lists the subject.
     */
    public function test_the_edit_page_still_offers_a_subject_unlinked_since_the_save(): void
    {
        $material = Material::create([
            'course_id' => $this->courseId,
            'subject_id' => $this->subjectId,
            'type' => 'book',
            'title' => 'Unlinked subject test',
            'file_link' => 'reference/test-unlinked-subject.pdf',
        ]);

        Storage::disk('public')->put('reference/test-unlinked-subject.pdf', self::PDF);

        SubjectDetail::where('course_id', $this->courseId)
            ->where('subject_id', $this->subjectId)
            ->delete();

        $this->asAdmin()
            ->get(route('material.edit', ['id' => $material->id]))
            ->assertOk()
            ->assertSee($material->subject->name ?? ('Subject #' . $material->subject_id));

        Storage::disk('public')->delete('reference/test-unlinked-subject.pdf');
    }
}
