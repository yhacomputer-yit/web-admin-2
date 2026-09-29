{{-- Shared fields for the enrollment create/edit forms. Expects $enrollment (or null)  --}}
@php($enrollment = $enrollment ?? null)

<div class="row g-3">
    @include('admin.enrollment.partials.student-picker', [
        'selectedStudent' => old('student_id')
            ? \App\Models\Student::find(old('student_id'))
            : ($enrollment?->student),
    ])

    <div class="col-md-6">
        <label for="course_id" class="form-label h6 my-2">Course <span class="text-danger">*</span></label>
        <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
            <option value="">Select Course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}"
                    @selected((string) old('course_id', $enrollment?->course_id) === (string) $course->id)>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="section_id" class="form-label h6 my-2">Section</label>
        <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror">
            <option value="">No Section</option>
            @foreach ($sections as $section)
                <option value="{{ $section->id }}"
                    @selected((string) old('section_id', $enrollment?->section_id) === (string) $section->id)>
                    {{ $section->name }}
                </option>
            @endforeach
        </select>
        @error('section_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="enroll_date" class="form-label h6 my-2">Enroll Date <span class="text-danger">*</span></label>
        <input type="date" name="enroll_date" id="enroll_date" class="form-control @error('enroll_date') is-invalid @enderror"
            value="{{ old('enroll_date', $enrollment?->enroll_date?->format('Y-m-d') ?? now()->toDateString()) }}" required>
        @error('enroll_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
