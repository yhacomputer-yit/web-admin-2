@extends('admin.master.master')

@section('title', 'Subject Resource')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-materials.css') }}" />
@endpush

@section('content')
    @php
        use App\Models\Material;
        use Illuminate\Support\Facades\Storage;

        $kinds = Material::KINDS;

        // one card per course, one subject row inside it, one line per file:
        // a subject can hold any number of files, so the file is the row now
        // and the subject is only a heading above them
        $grouped = $materials->groupBy('course_id');
        $fileCount = $materials->count();
        $subjectCount = $materials->groupBy('course_id')
            ->flatMap(fn ($rows) => $rows->groupBy('subject_id'))
            ->count();

        $humanSize = function (?string $path): ?string {
            if (blank($path) || ! Storage::disk('public')->exists($path)) {
                return null;
            }
            $bytes = Storage::disk('public')->size($path);
            foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
                if ($bytes < 1024) {
                    return round($bytes, $unit === 'B' ? 0 : 1) . ' ' . $unit;
                }
                $bytes /= 1024;
            }
            return round($bytes, 1) . ' TB';
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Subject Resource</h4>
            <div class="text-muted small">
                {{ $fileCount }} file(s) across {{ $subjectCount }} subject(s). Students see
                these on their Courses page.
            </div>
        </div>
        <a href="{{ route('material.createPage') }}" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Add File
        </a>
    </div>



    {{-- filters: the subject list follows the chosen course --}}
    <form method="GET" action="{{ route('material.index') }}" class="mat-card mb-3">
        <div class="p-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="course_id" class="form-label small fw-semibold mb-1">Course</label>
                    <select name="course_id" id="course_id" class="form-select form-select-sm">
                        <option value="">All courses</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected($selectedCourseId === $course->id)>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="subject_id" class="form-label small fw-semibold mb-1">Subject</label>
                    <select name="subject_id" id="subject_id" class="form-select form-select-sm">
                        <option value="">All subjects</option>
                        @foreach (($selectedCourseId ? ($subjectsByCourse[$selectedCourseId] ?? []) : []) as $subject)
                            <option value="{{ $subject['id'] }}" @selected($selectedSubjectId === $subject['id'])>
                                {{ $subject['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                        <i class="bx bx-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('material.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    @forelse ($grouped as $courseId => $courseRows)
        @php($course = $courseRows->first()->course)
        <div class="mat-card mb-3">
            <div class="mat-course-head">
                <i class="bx bx-book-reader"></i>
                <span class="mat-course-name">{{ $course?->name ?? 'Unknown course #' . $courseId }}</span>
                <span class="badge bg-light text-muted">{{ $courseRows->count() }} file(s)</span>

                <div class="ml-auto d-flex align-items-center gap-2">
                    <a href="{{ route('material.createPage', ['course_id' => $courseId]) }}"
                        class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-plus me-1"></i>Add file
                    </a>
                </div>
            </div>

                @foreach ($courseRows->groupBy('subject_id') as $subjectId => $subjectRows)
                    @php($subject = $subjectRows->first()->subject)
                    <div class="mat-subject">
                        <div class="mat-line" style="min-width: 0;">
                            <span class="mat-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="mat-name">{{ $subject?->name ?? 'Unknown subject' }}</span>
                            <span class="mat-count" title="files">{{ $subjectRows->count() }}</span>
                        </div>

                        <div class="mat-files">
                            @foreach ($subjectRows as $material)
                                @php($label = $material->displayTitle())
                                @php($size = $humanSize($material->file_link))
                                @php($isMissing = $size === null)
                                <div class="mat-chip{{ $isMissing ? ' is-missing' : '' }}">
                                    <span class="mat-kind-tag">{{ $material->typeLabel() }}</span>
                                    @if ($material->url())
                                        <a href="{{ $material->url() }}" target="_blank" rel="noopener"
                                            class="mat-chip-name text-decoration-none"
                                            title="{{ $label }}{{ $isMissing ? ' (file missing from storage)' : '' }}">
                                            <i class="{{ $kinds[$material->type]['icon'] ?? 'bx bx-file' }} me-1"></i>{{ $label }}
                                        </a>
                                    @else
                                        <span class="mat-chip-name">
                                            <i class="{{ $kinds[$material->type]['icon'] ?? 'bx bx-file' }} me-1"></i>{{ $label }}
                                        </span>
                                    @endif

                                    @if (filled($material->remark))
                                        <span class="mat-remark">
                                            <i class="bx bx-message-dots me-1"></i>{{ $material->remark }}
                                        </span>
                                    @endif

                                    <span class="mat-chip-size">{{ $isMissing ? 'missing' : $size }}</span>

                                    <span class="mat-when">
                                        {{ optional($material->updated_at)->format('M j, H:i') ?? '—' }}
                                    </span>

                                    <a href="{{ route('material.edit', ['id' => $material->id]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit this file">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="{{ route('material.delete', ['id' => $material->id]) }}"
                                        class="btn btn-sm btn-outline-danger" title="Delete this file"
                                        data-confirm="Delete {{ $label }}? The uploaded file is removed with it.">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
        </div>
    @empty
        <div class="mat-card">
            <div class="text-center py-5">
                <i class="bx bx-folder-open" style="font-size: 2rem; color: #cbd5e1;"></i>
                <div class="fw-semibold mt-2">
                    @if ($selectedCourseId || $selectedSubjectId)
                        No materials match these filters
                    @else
                        No materials yet
                    @endif
                </div>
                <div class="text-muted small mb-3">
                    Add a file to publish a subject's book, recording or archive to students.
                </div>
                <a href="{{ route('material.createPage') }}" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-plus me-1"></i> Add File
                </a>
            </div>
        </div>
    @endforelse
@endsection

@push('scripts')
    <script>
        (function () {
            // the subject options depend on the course, so they are filtered
            // client side from the map the controller sent down
            const map = @json($subjectsByCourse);
            const courseSelect = document.getElementById('course_id');
            const subjectSelect = document.getElementById('subject_id');
            if (!courseSelect || !subjectSelect) return;

            const render = () => {
                const current = subjectSelect.value;
                const options = map[courseSelect.value] || [];

                subjectSelect.innerHTML = '<option value="">All subjects</option>';
                options.forEach((subject) => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    option.selected = String(subject.id) === current;
                    subjectSelect.appendChild(option);
                });

                if (courseSelect.value) { courseSelect.form.submit(); }
            };

            courseSelect.addEventListener('change', render);
        })();
    </script>
@endpush
