@php
    // shared by create and edit; $certificate is null on create
    $certificate = $certificate ?? null;
    $isEdit = $certificate !== null;

    $statuses = \App\Models\Certificate::STATUSES;

    // a new certificate has not been handed over, which is the state the column
    // defaults to and the one the select should land on
    $currentStatus = (string) old('remark', $certificate->remark ?? \App\Models\Certificate::NOT_RECEIVED);
@endphp

<form method="POST" action="{{ $action }}">
    @csrf

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                @include('admin.partials.student-picker', [
                    'selectedStudent' => old('student_id')
                        ? \App\Models\Student::find(old('student_id'))
                        : ($certificate?->student),
                ])

                <div class="col-12 col-md-6">
                    <label for="complete_date" class="form-label h6 my-2">Complete date</label>
                    <input type="date" name="complete_date" id="complete_date"
                        value="{{ old('complete_date', $certificate?->complete_date?->toDateString() ?? '') }}"
                        class="form-control form-control-sm @error('complete_date') is-invalid @enderror">
                    @error('complete_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="remark" class="form-label h6 my-2">Certificate</label>
                    <select name="remark" id="remark"
                        class="form-select form-select-sm @error('remark') is-invalid @enderror">
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('certificate.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> Back
        </a>
        <button type="submit" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-down-arrow-alt me-1"></i> {{ $isEdit ? 'Save Changes' : 'Save' }}
        </button>
    </div>
</form>
