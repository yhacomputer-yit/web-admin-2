<?php

namespace App\Http\Requests;

use App\Models\StudentEnrollment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'enroll_date' => ['required', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // an empty select posts "", which is not a valid foreign key
        if ($this->input('section_id') === '') {
            $this->merge(['section_id' => null]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateSectionBelongsToCourse($validator);
            $this->validateNoDuplicateActiveEnrollment($validator);
        });
    }

    /**
     * A section can only be enrolled if the course has it linked through the
     * course_sections pivot. Reuse the same source of truth as the UI so the
     * form cannot be bypassed by posting a raw section id.
     */
    private function validateSectionBelongsToCourse(Validator $validator): void
    {
        $courseId = $this->input('course_id');
        $sectionId = $this->input('section_id');

        if (! $courseId || ! $sectionId) {
            return;
        }

        $linked = \App\Models\CourseSection::where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->exists();

        if (! $linked) {
            $validator->errors()->add(
                'section_id',
                'That section is not linked to the selected course. Link it first.'
            );
        }
    }

    /**
     * Only active enrollments are protected. A completed or dropped class can
     * be re-enrolled, which is why the database unique index was relaxed.
     */
    private function validateNoDuplicateActiveEnrollment(Validator $validator): void
    {
        $studentId = $this->input('student_id');
        $courseId = $this->input('course_id');
        $sectionId = $this->input('section_id');

        if (! $studentId || ! $courseId) {
            return;
        }

        $query = StudentEnrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('status', StudentEnrollment::STATUS_ACTIVE)
            ->when(
                $this->route('id') ?? $this->route('enrollment'),
                fn ($q) => $q->where('id', '!=', $this->route('id') ?? $this->route('enrollment'))
            );

        if ($sectionId === null) {
            $query->whereNull('section_id');
        } else {
            $query->where('section_id', $sectionId);
        }

        if ($query->exists()) {
            $validator->errors()->add(
                'section_id',
                'This student already has an active enrollment for that course and section.'
            );
        }
    }

    /**
     * Persisted attributes. status/complete_date are deliberately not taken
     * from the request: saving always produces an active enrollment, and
     * completion is its own explicit action.
     *
     * @return array<string, mixed>
     */
    public function enrollmentData(): array
    {
        return [
            'student_id' => $this->input('student_id'),
            'course_id' => $this->input('course_id'),
            'section_id' => $this->input('section_id'),
            'enroll_date' => $this->input('enroll_date'),
            'status' => StudentEnrollment::STATUS_ACTIVE,
            'complete_date' => null,
        ];
    }
}
