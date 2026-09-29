<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $enrollment = StudentEnrollment::with('student')->findOrFail($id);

        return view('admin.enrollment.edit', array_merge($this->formData(), [
            'enrollment' => $enrollment,
            'selectedStudent' => $enrollment->student,
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
     * Options needed by the create/edit forms.
     */
    private function formData()
    {
        return [
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
