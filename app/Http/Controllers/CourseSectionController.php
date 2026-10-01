<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncCourseSectionsRequest;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Section;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    /**
     * Course <-> Section linking. The old implementation was a one-pair
     * create/edit form (and its update used the course id as the row id).
     * This is a two-pane manager: pick a course, then attach or detach any of
     * its sections in one submit.
     */
    public function index(Request $request)
    {
        $courses = Course::with('sections')
            ->withCount('enrollments')
            ->orderBy('name')
            ->get();

        $selected = $request->integer('course_id') ?: $courses->first()?->id;

        $linkedIds = $selected
            ? CourseSection::where('course_id', $selected)->pluck('section_id')->all()
            : [];

        return view('admin.courseSection.index', [
            'courses' => $courses,
            'sections' => Section::orderBy('start')->orderBy('id')->get(),
            'selectedCourseId' => $selected,
            'linkedSectionIds' => $linkedIds,
        ]);
    }

    /**
     * Replace the whole link set for a course.
     */
    public function sync(SyncCourseSectionsRequest $request)
    {
        $courseId = (int) $request->input('course_id');
        $sectionIds = $request->sectionIds();

        // attach the newly checked sections, then detach the unchecked ones
        $keep = CourseSection::where('course_id', $courseId)
            ->whereIn('section_id', $sectionIds)
            ->pluck('section_id')
            ->all();

        $attach = array_diff($sectionIds, $keep);
        $detach = array_diff(
            CourseSection::where('course_id', $courseId)->pluck('section_id')->all(),
            $sectionIds
        );

        if ($attach !== []) {
            $now = now();
            CourseSection::insert(array_map(fn (int $sectionId) => [
                'course_id' => $courseId,
                'section_id' => $sectionId,
                'created_at' => $now,
                'updated_at' => $now,
            ], $attach));
        }

        if ($detach !== []) {
            CourseSection::where('course_id', $courseId)
                ->whereIn('section_id', $detach)
                ->delete();
        }

        $course = Course::findOrFail($courseId);

        return redirect()
            ->route('course.section.index', ['course_id' => $courseId])
            ->with('success', $course->name . ' now runs ' . count($sectionIds) . ' section(s).');
    }

    /**
     * JSON used by the enrollment form to reload sections when the course
     * changes. Only sections linked to that course are returned.
     */
    public function sectionsForCourse(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        $linked = CourseSection::where('course_id', $course->id)
            ->join('sections', 'sections.id', '=', 'course_sections.section_id')
            ->orderBy('sections.start')
            ->orderBy('sections.id')
            ->get(['sections.id', 'sections.name']);

        return response()->json([
            'course_id' => (int) $course->id,
            'sections' => $linked->map(fn ($s) => ['id' => (int) $s->id, 'name' => $s->name])->values(),
            'message' => $linked->isEmpty() ? 'No sections available for this course' : null,
        ]);
    }

    // ---- legacy routes, kept so existing links keep working ---------------

    /** direct create page: redirect to the manager */
    public function createPage()
    {
        return redirect()->route('course.section.index');
    }

    public function create(Request $request)
    {
        return $this->sync(SyncCourseSectionsRequest::createFrom($request))
            ->with('success', 'Section linked to the course successfully!');
    }

    public function edit($id)
    {
        return redirect()->route('course.section.index', [
            'course_id' => CourseSection::findOrFail($id)->course_id,
        ]);
    }

    public function update(Request $request)
    {
        return $this->sync(SyncCourseSectionsRequest::createFrom($request));
    }

    public function delete($id)
    {
        $link = CourseSection::findOrFail($id);
        $link->delete();

        return redirect()
            ->route('course.section.index', ['course_id' => $link->course_id])
            ->with('success', 'Section unlinked from the course successfully!');
    }
}
