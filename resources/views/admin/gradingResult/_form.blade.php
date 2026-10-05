@php
    // shared by create and edit; $result is null on create
    $result = $result ?? null;
    $isEdit = $result !== null;

    $currentCourseId = (int) old('course_id', $preselectedCourseId ?? $result->course_id ?? 0);
    $currentSubjectId = (int) old('subject_id', $preselectedSubjectId ?? $result->subject_id ?? 0);
    $currentGradeId = (int) old('grade_id', $result->grade_id ?? 0);
@endphp

<form method="POST" action="{{ $action }}">
    @csrf

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-user me-1"></i> Student</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @include('admin.partials.student-picker', [
                            'selectedStudent' => old('student_id')
                                ? \App\Models\Student::find(old('student_id'))
                                : ($result?->student),
                        ])
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-book-reader me-1"></i> Subject &amp; mark</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="course_id" class="form-label h6 my-2">
                            Course <span class="text-danger">*</span>
                        </label>
                        <select name="course_id" id="course_id" required
                            class="form-select form-select-sm @error('course_id') is-invalid @enderror">
                            <option value="">Choose a course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($currentCourseId === $course->id)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject_id" class="form-label h6 my-2">
                            Subject <span class="text-danger">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required
                            class="form-select form-select-sm @error('subject_id') is-invalid @enderror">
                            <option value="">Choose a subject</option>
                            @foreach (($currentCourseId ? ($subjectsByCourse[$currentCourseId] ?? []) : []) as $subject)
                                <option value="{{ $subject['id'] }}" @selected($currentSubjectId === $subject['id'])>
                                    {{ $subject['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label for="score" class="form-label h6 my-2">
                            Mark <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="score" id="score" step="0.01" min="0" max="9999" required
                            value="{{ old('score', $result?->scoreLabel() ?? '') }}"
                            class="form-control form-control-sm @error('score') is-invalid @enderror"
                            placeholder="e.g. 72">
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="grade_id" class="form-label h6 my-2">Band</label>
                        <select name="grade_id" id="grade_id"
                            class="form-select form-select-sm @error('grade_id') is-invalid @enderror">
                            <option value="">Not graded yet</option>
                            @foreach ($gradings as $grading)
                                <option value="{{ $grading->id }}" @selected($currentGradeId === $grading->id)>
                                    {{ $grading->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('grade_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-2">
                        <label for="date" class="form-label h6 my-2">
                            Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                            value="{{ old('date', $result?->date?->toDateString() ?? now()->toDateString()) }}"
                            class="form-control form-control-sm @error('date') is-invalid @enderror">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('gradingResult.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> Back
        </a>
        <button type="submit" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-down-arrow-alt me-1"></i> {{ $isEdit ? 'Save Changes' : 'Save' }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        (function () {
            const map = @json($subjectsByCourse);
            const courseSelect = document.getElementById('course_id');
            const subjectSelect = document.getElementById('subject_id');
            if (!courseSelect || !subjectSelect) return;

            const renderSubjects = () => {
                subjectSelect.innerHTML = '<option value="">Choose a subject</option>';
                (map[courseSelect.value] || []).forEach((subject) => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
            };

            courseSelect.addEventListener('change', renderSubjects);
        })();
    </script>
@endpush
