<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * The course -> section linking manager.
 *
 * The rule worth pinning down: clearing every section is a save, not
 * a mistake. A form with no box ticked sends no section_ids field at
 * all, so a "present" rule on that field turns "run none of these"
 * into a validation failure and silently puts every link back on the
 * screen - the "Clear all" button appears to do nothing.
 *
 * Runs against the database named in .env rather than the throwaway
 * one phpunit.xml hands the rest of the suite, because it rolls each
 * test back instead of migrating. The course is discovered rather
 * than invented: the courses table carries a wall of NOT NULL
 * columns the linking manager never touches, and a database with no
 * course is not a bug in this page.
 */
class CourseSectionLinkingTest extends TestCase
{
    use DatabaseTransactions;

    private ?User $admin = null;

    private ?Course $course = null;

    /** @var array<int, Section> */
    private array $sections = [];

    /**
     * Point this test at the database named in .env rather than the
     * one phpunit.xml hands the rest of the suite, before
     * DatabaseTransactions opens its transaction.
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

        $this->admin = User::where('role', '!=', 'user')->first();

        if ($this->admin === null) {
            $this->markTestSkipped('No admin user to sign in as.');
        }

        $this->course = Course::orderBy('id')->first();

        if ($this->course === null) {
            $this->markTestSkipped('No course to link sections against.');
        }

        $this->sections = [
            Section::create([
                'name' => 'Linking morning ' . uniqid(),
                'start' => '06:00',
                'end' => '09:00',
            ]),
            Section::create([
                'name' => 'Linking evening ' . uniqid(),
                'start' => '18:00',
                'end' => '21:00',
            ]),
        ];

        // start every test from a known link set, whatever the
        // development database already holds for this course
        CourseSection::where('course_id', $this->course->id)->delete();
    }

    /** @test */
    public function the_manager_page_lists_the_course_and_its_sections()
    {
        $this->actingAs($this->admin);

        $this->get(route('course.section.index'))
            ->assertOk()
            ->assertSee($this->course->name)
            ->assertSee($this->sections[0]->name);
    }

    /** @test */
    public function ticking_sections_links_them()
    {
        $this->actingAs($this->admin);

        $this->post(route('course.section.sync'), [
            'course_id' => $this->course->id,
            'section_ids' => [$this->sections[0]->id, $this->sections[1]->id],
        ])
            ->assertRedirect(route('course.section.index', ['course_id' => $this->course->id]))
            ->assertSessionHas('success');

        $this->assertSame(2, CourseSection::where('course_id', $this->course->id)->count());
    }

    /** @test */
    public function clearing_every_section_is_a_save_not_a_validation_error()
    {
        $this->actingAs($this->admin);

        CourseSection::create([
            'course_id' => $this->course->id,
            'section_id' => $this->sections[0]->id,
        ]);
        CourseSection::create([
            'course_id' => $this->course->id,
            'section_id' => $this->sections[1]->id,
        ]);

        // no box ticked, so the form sends no section_ids field at all
        $this->post(route('course.section.sync'), [
            'course_id' => $this->course->id,
        ])
            ->assertRedirect(route('course.section.index', ['course_id' => $this->course->id]))
            ->assertSessionHas('success')
            ->assertSessionDoesntHaveErrors();

        $this->assertSame(0, CourseSection::where('course_id', $this->course->id)->count());
    }

    /** @test */
    public function unchecking_one_section_detaches_only_that_one()
    {
        $this->actingAs($this->admin);

        CourseSection::create([
            'course_id' => $this->course->id,
            'section_id' => $this->sections[0]->id,
        ]);
        CourseSection::create([
            'course_id' => $this->course->id,
            'section_id' => $this->sections[1]->id,
        ]);

        $this->post(route('course.section.sync'), [
            'course_id' => $this->course->id,
            'section_ids' => [$this->sections[0]->id],
        ])
            ->assertRedirect(route('course.section.index', ['course_id' => $this->course->id]));

        $linked = CourseSection::where('course_id', $this->course->id)
            ->pluck('section_id')
            ->all();

        $this->assertSame([$this->sections[0]->id], $linked);
    }

    /** @test */
    public function an_unknown_section_is_refused()
    {
        $this->actingAs($this->admin);

        $this->post(route('course.section.sync'), [
            'course_id' => $this->course->id,
            'section_ids' => [999999],
        ])->assertSessionHasErrors(['section_ids.0']);
    }
}
