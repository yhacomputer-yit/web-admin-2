<?php

namespace App\Http\Requests;

use App\Models\Attendance;
use App\Models\StudentEnrollment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'date' => ['required', 'date'],
            'entries' => ['required', 'array', 'min:1'],
            'entries.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'entries.*.status' => ['required', 'integer', 'in:' . implode(',', array_keys(Attendance::MARKABLE_STATUSES))],
            // optional note per student; an empty string is allowed and stored as NULL
            'entries.*.remark' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * The attendance rule: a student can only be marked if they hold an active
     * enrollment for that exact course + section. Anything else is rejected
     * with a per-student message rather than silently dropped.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateSubjectBelongsToCourse($validator);
            $this->rejectCompletedClass($validator);
            $this->rejectStudentsWithoutActiveEnrollment($validator);
        });
    }

    /**
     * A subject can only be marked against a course that actually teaches it.
     * Same source of truth as the dependent dropdown, so the form cannot be
     * bypassed by posting a raw subject id.
     */
    private function validateSubjectBelongsToCourse(Validator $validator): void
    {
        $courseId = $this->input('course_id');
        $subjectId = $this->input('subject_id');

        if (! $courseId || ! $subjectId) {
            return;
        }

        $linked = \App\Models\Subject::whereHas('courses', fn ($q) => $q->where('courses.id', $courseId))
            ->whereKey($subjectId)
            ->exists();

        if (! $linked) {
            $validator->errors()->add(
                'subject_id',
                'That subject is not linked to the selected course.'
            );
        }
    }

    /**
     * A class is closed once it has been completed: every enrollment for that
     * course + section is completed or dropped and none is still active.
     *
     * This deliberately does not block on "any completed row exists". A
     * completed class can be re-enrolled (that is why the hard unique index
     * was relaxed), and the student who re-enrolled must be markable again.
     * Once nothing active remains, attendance is closed for good.
     */
    private function rejectCompletedClass(Validator $validator): void
    {
        $courseId = (int) $this->input('course_id');
        $sectionId = (int) $this->input('section_id');

        $total = StudentEnrollment::forClass($courseId, $sectionId)->count();
        $active = StudentEnrollment::forClass($courseId, $sectionId)->active()->count();

        if ($total > 0 && $active === 0) {
            $validator->errors()->add(
                'entries',
                'This class has already been completed. No active enrollment remains, so attendance can no longer be marked.'
            );
        }
    }

    private function rejectStudentsWithoutActiveEnrollment(Validator $validator): void
    {
        $courseId = (int) $this->input('course_id');
        $sectionId = (int) $this->input('section_id');

        $entries = $this->input('entries', []);
        $studentIds = array_map('intval', array_column($entries, 'student_id'));

        if ($studentIds === []) {
            return;
        }

        $enrolled = StudentEnrollment::where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->where('status', StudentEnrollment::STATUS_ACTIVE)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $inactive = array_diff($studentIds, $enrolled);

        if ($inactive !== []) {
            $names = \App\Models\Student::whereIn('id', $inactive)->pluck('name')->all();

            $validator->errors()->add(
                'entries',
                'These students have no active enrollment for this course and section: '
                . implode(', ', $names ?: $inactive) . '.'
            );
        }
    }
}
