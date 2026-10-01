<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncCourseSectionsRequest extends FormRequest
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
            'section_ids' => ['present', 'array'],
            'section_ids.*' => ['integer', 'exists:sections,id'],
        ];
    }

    /**
     * @return array<int, int>
     */
    public function sectionIds(): array
    {
        return array_values(array_unique(array_map('intval', $this->input('section_ids', []))));
    }
}
