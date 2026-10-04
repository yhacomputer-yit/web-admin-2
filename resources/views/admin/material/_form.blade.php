@php
    use App\Models\Material;
    use Illuminate\Support\Facades\Storage;

    // shared by create and edit; $material is null on create
    $material = $material ?? null;
    $isEdit = $material !== null;

    $kinds = Material::KINDS;

    $currentCourseId = (int) old('course_id', $preselectedCourseId ?? $material->course_id ?? 0);
    $currentSubjectId = (int) old('subject_id', $material->subject_id ?? 0);
    $currentType = (string) old('type', $material->type ?? array_key_first($kinds));
@endphp

<style>
    /* scoped to this page: three radio inputs dressed as cards, so the kind of
       file is a tap rather than a dropdown, and the chosen one is obvious */
    .mat-kind {
        display: block;
        height: 100%;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: .65rem .75rem;
        background: #fff;
        transition: border-color .15s ease, background-color .15s ease;
    }

    .mat-kind:hover {
        border-color: #ffc79a;
    }

    .mat-kind input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .mat-kind:has(input:checked),
    .mat-kind.is-on {
        border-color: #ff6c0f;
        background: #fff8f2;
    }

    .mat-kind:has(input:focus-visible) {
        outline: 2px solid #ff6c0f;
        outline-offset: 2px;
    }

    .mat-kind-title {
        display: block;
        font-weight: 600;
        font-size: .875rem;
        color: #0f172a;
    }

    .mat-kind-hint {
        display: block;
        font-size: .72rem;
        color: #94a3b8;
    }

    /* the file being replaced, shown above its input so nobody uploads a second
       copy by accident */
    .mat-current {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .5rem;
        padding: .5rem .65rem;
        border-radius: 8px;
        margin-bottom: .6rem;
    }
</style>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-link-alt me-1"></i> Course &amp; Subject</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 form-group">
                        <label for="course_id" class="form-label h6 my-2">Course</label>
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

                    <div class="mb-3 form-group">
                        <label for="subject_id" class="form-label h6 my-2">Subject</label>
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
                        {{-- <div class="form-text">
                            Only subjects linked to the chosen course are listed. Link more under
                            <a href="{{ route('admin.course') }}" class="text-decoration-none">Course</a>.
                        </div> --}}
                    </div>

                    <div class="mb-2 form-group">
                        <label for="remark" class="form-label h6 my-2">Remark</label>
                        <textarea name="remark" id="remark" rows="3" maxlength="500"
                            class="form-control form-control-sm @error('remark') is-invalid @enderror"
                            placeholder="Optional note for the teaching team">{{ old('remark', $material->remark ?? '') }}</textarea>
                        @error('remark')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-cloud-upload me-1"></i> File</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        One file per row. A subject can hold as many books, recordings
                        and archives as it needs &mdash; add another file to the same
                        subject whenever you need one.
                    </p>

                    <div class="form-group">
                        <label class="form-label h6 my-2">Kind of file</label>
                        <div class="row g-2">
                            @foreach ($kinds as $key => $kind)
                                <div class="col-12 col-md-4">
                                    <label class="mat-kind">
                                        <input type="radio" name="type" value="{{ $key }}" @checked($currentType === $key)>
                                        <span class="d-flex align-items-center gap-2 mb-1">
                                            <i class="{{ $kind['icon'] }} {{ $kind['class'] }}"></i>
                                            <span class="mat-kind-title">{{ $kind['form_label'] }}</span>
                                        </span>
                                        <span class="mat-kind-hint">{{ $kind['hint'] }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="title" class="form-label h6 my-2">Title</label>
                        <input type="text" name="title" id="title" maxlength="120"
                            value="{{ old('title', $material->title ?? '') }}"
                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                            placeholder="Optional &mdash; the file name is used when this is empty">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file" class="form-label h6 my-2">Upload</label>

                        @if ($isEdit && filled($material->file_link))
                            @php($onDisk = Storage::disk('public')->exists($material->file_link))
                            <div class="mat-current"
                                style="background: {{ $onDisk ? '#fff8f2' : '#fef2f2' }};
                                       border: 1px solid {{ $onDisk ? '#ffe0c2' : '#fecaca' }};">
                                <i class="{{ $kinds[$material->type]['icon'] ?? 'bx bx-file' }}"></i>
                                @if ($onDisk)
                                    <a href="{{ $material->url() }}" target="_blank" rel="noopener"
                                        class="text-decoration-none text-truncate" style="max-width: 60%;"
                                        title="{{ $material->displayTitle() }}">
                                        {{ $material->displayTitle() }}
                                    </a>
                                @else
                                    <span class="text-truncate" style="max-width: 60%;"
                                        title="{{ $material->displayTitle() }}">
                                        {{ $material->displayTitle() }}
                                    </span>
                                    <span class="badge text-bg-danger">missing from storage</span>
                                @endif
                            </div>
                        @endif

                        <input type="file" name="file" id="file"
                            accept="{{ $kinds[$currentType]['accept'] ?? '' }}"
                            class="form-control form-control-sm @error('file') is-invalid @enderror"
                            @required(! $isEdit)>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="file-hint">
                            {{ $kinds[$currentType]['hint'] ?? '' }}
                            @if ($isEdit && filled($material->file_link))
                                &mdash; leave empty to keep the current file
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('material.index') }}" class="btn btn-outline-secondary">
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

            if (courseSelect && subjectSelect) {
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
            }

            // the kind of file decides what the picker accepts, so the accept
            // filter and the hint follow whichever card is ticked
            const kinds = @json($kinds);
            const fileInput = document.getElementById('file');
            const fileHint = document.getElementById('file-hint');

            if (fileInput && fileHint) {
                const radios = Array.from(document.querySelectorAll('input[name="type"]'));

                const syncFileRules = () => {
                    radios.forEach((radio) => {
                        radio.closest('.mat-kind')?.classList.toggle('is-on', radio.checked);
                    });

                    const chosen = document.querySelector('input[name="type"]:checked');
                    const kind = chosen ? kinds[chosen.value] : null;

                    if (!kind) return;

                    fileInput.accept = kind.accept;
                    fileHint.textContent = kind.hint;
                };

                radios.forEach((radio) => {
                    radio.addEventListener('change', syncFileRules);
                });

                syncFileRules();
            }
        })();
    </script>
@endpush
