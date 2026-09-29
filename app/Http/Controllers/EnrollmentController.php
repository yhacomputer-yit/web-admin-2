<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EnrollmentController extends Controller
{
    // direct create page
    public function createPage()
    {
        return view('admin.enrollment.create', $this->formData());
    }

    // create a new enrollment
    public function create(Request $request)
    {
        $data = $this->validation($request);

        $enrollment = StudentEnrollment::create($data);

        return redirect()->route('admin.enrollment')->with([
            'success' => 'Enrolled ' . $enrollment->student->name . ' in '
                . $enrollment->course->name . ' successfully.',
        ]);
    }

    // edit enrollment
    public function edit($id)
    {
        $enrollment = StudentEnrollment::findOrFail($id);

        return view('admin.enrollment.edit', array_merge($this->formData(), [
            'enrollment' => $enrollment,
        ]));
    }

    // update enrollment
    public function update(Request $request, $id)
    {
        $enrollment = StudentEnrollment::findOrFail($id);
        $data = $this->validation($request, $enrollment->id);

        $enrollment->update($data);
        $enrollment->load('student', 'course', 'section');

        return redirect()->route('admin.enrollment')->with([
            'success' => 'Updated enrollment for ' . $enrollment->student->name . ' successfully.',
        ]);
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
     * Options needed by the create/edit forms.
     */
    private function formData()
    {
        return [
            'students' => Student::orderBy('name')->get(['id', 'name', 'username']),
            'courses' => Course::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('start', 'asc')->get(['id', 'name']),
        ];
    }

    // validation the request data
    private function validation(Request $request, $ignoreId = null)
    {
        $data = [
            'student_id' => $request->input('student_id'),
            'course_id' => $request->input('course_id'),
            'section_id' => $request->input('section_id') ?: null,
            'enroll_date' => $request->input('enroll_date'),
        ];

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'nullable|exists:sections,id',
            'enroll_date' => 'required|date',
        ], [
            'student_id.required' => 'Please select a student.',
            'student_id.exists' => 'The selected student no longer exists.',
            'course_id.required' => 'Please select a course.',
            'course_id.exists' => 'The selected course no longer exists.',
            'section_id.exists' => 'The selected section no longer exists.',
            'enroll_date.required' => 'Enroll date is required.',
        ]);

        // the table has a unique (student, course, section) index; stop duplicates
        $duplicate = StudentEnrollment::where('student_id', $data['student_id'])
            ->where('course_id', $data['course_id'])
            ->when($data['section_id'],
                fn ($q) => $q->where('section_id', $data['section_id']),
                fn ($q) => $q->whereNull('section_id'))
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'course_id' => 'This student is already enrolled in that course and section.',
            ]);
        }

        return $data;
    }
}
