@php
    // shared by create and edit; $certificate is null on create
    $certificate = $certificate ?? null;
    $isEdit = $certificate !== null;

    $statuses = \App\Models\Certificate::STATUSES;

    // a new certificate has not been handed over, which is the state the column
    // defaults to and the one the select should land on
    $currentStatus = (string) old('remark', $certificate->remark ?? \App\Models\Certificate::NOT_RECEIVED);
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
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
                    <label for="certificate_file" class="form-label h6 my-2">Certificate file (JPEG)</label>
                    <input type="file" name="certificate_file" id="certificate_file"
                        accept="image/jpeg,image/jpg"
                        class="form-control form-control-sm @error('certificate_file') is-invalid @enderror">
                    @error('certificate_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">JPEG/JPG format only.</div>
                    @if ($isEdit && $certificate->certificate_file)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $certificate->certificate_file) }}" alt="Certificate" style="max-height: 100px;">
                        </div>
                    @endif
                </div>

                <div class="col-12 col-md-6">
                    <label for="remark" class="form-label h6 my-2">Remark</label>
                    <input type="text" name="remark" id="remark" maxlength="255"
                        value="{{ old('remark', $certificate->remark ?? \App\Models\Certificate::NOT_RECEIVED) }}"
                        class="form-control form-control-sm @error('remark') is-invalid @enderror"
                        placeholder="e.g. Received, Not received, Collected by brother, etc.">
                    @error('remark')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Free text. Common values: "Received", "Not received".</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('certificate.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> Back
        </a>
        <button type="submit" class="btn" style="background-color: #ff6c0f; color: white;" data-loading="Saving...">
            <i class="bx bx-down-arrow-alt me-1"></i> {{ $isEdit ? 'Save Changes' : 'Save' }}
        </button>
    </div>
</form>
