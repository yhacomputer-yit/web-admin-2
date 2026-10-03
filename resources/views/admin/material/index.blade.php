@extends('admin.master.master')

@section('title', 'Materials')

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

    <style>
        /* scoped to this page: the layout is card based, so nothing here needs a
           stylesheet of its own and it cannot leak into other admin pages */
        .mat-card {
            border: 1px solid #eceef2;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .06);
        }
        .mat-course-head {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
            padding: .85rem 1rem;
            background: #fff8f2;
            border-bottom: 1px solid #ffe0c2;
        }
        .mat-course-name {
            font-weight: 600;
            font-size: .95rem;
            color: #0f172a;
        }
        .mat-subject {
            padding: .85rem 1rem .6rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .mat-subject:last-child {
            border-bottom: 0;
        }
        .mat-line {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .mat-num {
            flex: 0 0 auto;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .04em;
            color: #ff6c0f;
            background: #fff1e6;
            border-radius: 5px;
            padding: .1rem .35rem;
        }
        .mat-name {
            font-weight: 600;
            font-size: .9rem;
            color: #0f172a;
            min-width: 0;
        }
        /* the file lines sit inside the subject, so they are indented under it
           rather than running the full width of the card */
        .mat-files {
            display: flex;
            flex-direction: column;
            gap: .3rem;
            margin: .55rem 0 .6rem 2.1rem;
        }
        /* a chip wraps its own text instead of pushing the row wider, which is
           what a table cell cannot do */
        .mat-chip {
            display: flex;
            align-items: center;
            gap: .5rem;
            max-width: 100%;
            padding: .35rem .6rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .78rem;
            color: #334155;
            text-decoration: none;
        }
        .mat-chip:hover {
            background: #fff1e6;
            border-color: #ffc79a;
            color: #0f172a;
            text-decoration: none;
        }
        /* the row still holds a path but there is no file behind it */
        .mat-chip.is-missing {
            background: #fef2f2;
            border-color: #fecaca;
            border-style: dashed;
        }
        .mat-chip.is-missing .mat-chip-name {
            text-decoration: line-through;
        }
        .mat-chip.is-missing .mat-chip-size {
            color: #dc2626;
            font-weight: 600;
        }
        /* the kind of file, so a list of books, recordings and archives can be
           told apart at a glance without reading every name */
        .mat-kind-tag {
            flex: 0 0 auto;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: .12rem .4rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }
        .mat-chip-name {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .mat-chip-size {
            flex: 0 0 auto;
            font-size: .68rem;
            color: #94a3b8;
        }
        .mat-remark {
            font-size: .72rem;
            color: #64748b;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Subject Materials</h4>
            <div class="text-muted small">
                {{ $fileCount }} file(s) across {{ $subjectCount }} subject(s). Students see
                these on their Courses page.
            </div>
        </div>
        <a href="{{ route('material.createPage') }}" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Add File
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                        <span class="badge bg-light text-muted">{{ $subjectRows->count() }} file(s)</span>
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
                                        <i class="bx bx-message-dots mr-1"></i>{{ $material->remark }}
                                    </span>
                                @endif

                                <span class="mat-chip-size ml-auto">{{ $isMissing ? 'missing' : $size }}</span>

                                <span class="text-muted text-nowrap" style="font-size: .72rem;">
                                    {{ optional($material->updated_at)->format('M j, H:i') ?? '—' }}
                                </span>

                                <a href="{{ route('material.edit', ['id' => $material->id]) }}"
                                    class="btn btn-sm btn-outline-secondary" title="Edit this file">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <a href="{{ route('material.delete', ['id' => $material->id]) }}"
                                    class="btn btn-sm btn-outline-danger" title="Delete this file"
                                    onclick="return confirm('Delete {{ $label }}? The uploaded file is removed with it.')">
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

    @if ($grouped->isNotEmpty())
        <p class="text-muted small mt-2 mb-0">
            <i class="bx bx-info-circle me-1"></i>
            A subject can hold any number of files: add one for each book, each
            recording and each archive. Deleting a file also removes it from
            storage, so only ever delete the copy you meant to.
        </p>
    @endif
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