<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    // direct create page
    public function createPage()
    {
        // on a validation error the sections must come back for the course
        // the user had already picked
        return view('admin.enrollment.create', $this->formData(request()->integer('course_id') ?: null));
    }

    // create a new enrollment
    public function create(EnrollmentRequest $request)
    {
        $enrollment = StudentEnrollment::create($request->enrollmentData());

        return redirect()->route('admin.enrollment')->with([
            'success' => 'Enrolled ' . $enrollment->student->name . ' in '
                . $enrollment->course->name . ' successfully.',
        ]);
    }

    // update enrollment
    public function update(EnrollmentRequest $request, $id)
    {
        $enrollment = StudentEnrollment::findOrFail($id);
        $enrollment->update($request->enrollmentData());
        $enrollment->load('student', 'course', 'section');

        return redirect()->route('admin.enrollment')->with([
            'success' => 'Updated enrollment for ' . $enrollment->student->name . ' successfully.',
        ]);
    }

    // edit enrollment
    public function edit($id)
    {
        $enrollment = StudentEnrollment::with('student')->findOrFail($id);

        // on a validation error the sections must come back for the course
        // the user had already picked
        $courseId = request()->integer('course_id') ?: $enrollment->course_id;

        return view('admin.enrollment.edit', array_merge($this->formData($courseId), [
            'enrollment' => $enrollment,
            'selectedStudent' => $enrollment->student,
        ]));
    }

    // delete enrollment
    public function delete($id)
    {
        $enrollment = StudentEnrollment::find($id);

        if ($enrollment) {
            $name = $enrollment->student?->name;
            $enrollment->delete();

            return redirect()->route('admin.enrollment')->with([
                'success' => 'Removed ' . $name . "'s enrollment successfully.",
            ]);
        }

        return redirect()->route('admin.enrollment')->with(['error' => 'Enrollment not found.']);
    }

    /**
     * Autocomplete lookup for the student picker. Searches across name,
     * username, phone, email, NRC and the numeric student id.
     */
    public function searchStudents(Request $request)
    {
        $term = trim((string) $request->input('q', ''));

        $students = Student::query()
            ->when($term !== '', function ($q) use ($term) {
                $like = "%{$term}%";
                $q->where(function ($sub) use ($like, $term) {
                    $sub->where('name', 'like', $like)
                        ->orWhere('username', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('nrc', 'like', $like);

                    if (ctype_digit($term)) {
                        $sub->orWhere('id', (int) $term);
                    }
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'username', 'phone', 'email', 'image', 'status']);

        return response()->json($students->map(fn ($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'username' => $s->username,
            'phone' => $s->phone,
            'email' => $s->email,
            'status' => $s->status,
            'image' => $s->image ? Storage::url($s->image) : null,
        ]));
    }

    /**
     * Sections linked to a course, for the dependent select in the
     * create/edit form. Returns an explicit message when the course has no
     * linked sections yet.
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

    /**
     * Mark every enrollment of a course + section as completed.
     * After this no attendance can be recorded for that class.
     */
    public function completeClass(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'section_id' => 'required|integer|exists:sections,id',
        ], [
            'course_id.required' => 'Please select a course.',
            'section_id.required' => 'Please select a section.',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        $section = Section::findOrFail($validated['section_id']);

        $enrollments = StudentEnrollment::forClass($course->id, $section->id)
            ->where('status', StudentEnrollment::STATUS_ACTIVE)
            ->get();

        if ($enrollments->isEmpty()) {
            return back()->with('error', 'There are no active enrollments to complete for that class.');
        }

        $today = now();
        $count = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->markAs(StudentEnrollment::STATUS_COMPLETED, $today)) {
                $count++;
            }
        }

        return back()->with('success', "Completed $section->name of $course->name for $count student(s).");
    }

    /**
     * Options needed by the create/edit forms.
     */
    private function formData(?int $courseId = null)
    {
        return [
            'courses' => Course::orderBy('name')->get(['id', 'name']),
            'sections' => $courseId
                ? Section::whereIn('id', function ($q) use ($courseId) {
                    $q->select('section_id')->from('course_sections')->where('course_id', $courseId);
                })->orderBy('start')->get(['id', 'name'])
                : collect(),
        ];
    }
}
