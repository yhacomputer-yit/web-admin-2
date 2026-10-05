@php
    // shared by create and edit; $dropOut is null on create
    $dropOut = $dropOut ?? null;
    $isEdit = $dropOut !== null;

    $currentCourseId = (int) old('course_id', $preselectedCourseId ?? $dropOut?->course_id ?? 0);
@endphp

<form method="POST" action="{{ $action }}">
    @csrf

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                @include('admin.partials.student-picker', [
                    'selectedStudent' => old('student_id')
                        ? \App\Models\Student::find(old('student_id'))
                        : ($dropOut?->student),
                ])

                <div class="col-12 col-md-6">
                    <label for="course_id" class="form-label h6 my-2">
                        Course <span class="text-danger">*</span>
                    </label>
                    <select name="course_id" id="course_id" required
                        class="form-select form-select-sm @error('course_id') is-invalid @enderror">
                        <option value="">Choose the course they left</option>
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

                <div class="col-12 col-md-6">
                    <label for="drop_out_date" class="form-label h6 my-2">
                        Drop out date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="drop_out_date" id="drop_out_date" required
                        value="{{ old('drop_out_date', $dropOut?->drop_out_date?->toDateString() ?? now()->toDateString()) }}"
                        class="form-control form-control-sm @error('drop_out_date') is-invalid @enderror">
                    @error('drop_out_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-12">
                    <label for="remark" class="form-label h6 my-2">Remark</label>
                    <textarea name="remark" id="remark" rows="3" maxlength="500"
                        class="form-control form-control-sm @error('remark') is-invalid @enderror"
                        placeholder="Why the student left, or anything the office should know">{{ old('remark', $dropOut->remark ?? '') }}</textarea>
                    @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="alert alert-light border small mt-3 mb-0" role="note">
                <i class="bx bx-info-circle me-1"></i>
                Saving closes the student's enrollment in this course &mdash; status
                <strong>dropped</strong>, complete date set to the drop-out date &mdash; so the
                class list and this record cannot disagree. Deleting the record reopens that
                one enrollment again and leaves the rest of their classes alone. Neither
                action touches the student's own status on the
                <a href="{{ route('admin.student') }}" class="text-decoration-none">Student</a>
                page, and nothing at all is written until you press Save.
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('dropOut.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> Back
        </a>
        <button type="submit" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-down-arrow-alt me-1"></i> {{ $isEdit ? 'Save Changes' : 'Save' }}
        </button>
    </div>
</form>
