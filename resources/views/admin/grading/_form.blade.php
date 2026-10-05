@php
    // shared by create and edit; $grading is null on create
    $grading = $grading ?? null;
    $isEdit = $grading !== null;
@endphp

<form method="POST" action="{{ $action }}">
    @csrf

    <div class="card">
        <div class="card-header">Band</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="name" class="form-label h6 my-2">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" maxlength="60" required
                        value="{{ old('name', $grading->name ?? '') }}"
                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                        placeholder="e.g. A, B, Distinction">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-12 col-md-6">
                    <label for="score" class="form-label h6 my-2">Top mark <span class="text-danger">*</span></label>
                    <input type="number" name="score" id="score" step="0.01" min="0" max="9999" required
                        value="{{ old('score', $grading?->scoreLabel() ?? '') }}"
                        class="form-control form-control-sm @error('score') is-invalid @enderror"
                        placeholder="e.g. 100 for A, 80 for B">
                    @error('score')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('grading.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> Back
        </a>
        <button type="submit" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-down-arrow-alt me-1"></i> {{ $isEdit ? 'Save Changes' : 'Save' }}
        </button>
    </div>
</form>
