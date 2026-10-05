@extends('admin.master.master')

@section('title', 'Exams')

@section('content')
    @php
        use App\Models\ExamQuestion;
        use App\Support\StoredFile;
        use App\Support\TimeOfDay;
        use Illuminate\Support\Facades\Storage;

        $now = now();

        /* Three reads of a paper in one closure: what it is called, how big it is,
           and whether it is still on the disk. A row can outlive its upload, so a
           filled-in path is not the same as a readable file, and a list that shows
           a size has to be able to say "missing" rather than offer a download that
           will not come. */
        $paper = function (?string $path): array {
            if (blank($path)) {
                return ['label' => null, 'size' => null, 'missing' => false];
            }

            $label = StoredFile::label($path);

            if (! Storage::disk(ExamQuestion::PAPER_DISK)->exists($path)) {
                return ['label' => $label, 'size' => null, 'missing' => true];
            }

            $bytes = Storage::disk(ExamQuestion::PAPER_DISK)->size($path);

            foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
                if ($bytes < 1024) {
                    return [
                        'label' => $label,
                        'size' => round($bytes, $unit === 'B' ? 0 : 1) . ' ' . $unit,
                        'missing' => false,
                    ];
                }

                $bytes /= 1024;
            }

            return ['label' => $label, 'size' => round($bytes, 1) . ' TB', 'missing' => false];
        };
    @endphp

    <style>
        /* scoped to this page: the schedule is a table, so the only thing that
           needs styling here is the date badge and the paper line, which are the
           two cells a plain <td> cannot draw on its own */
        .ex-shell {
            border: 1px solid #eceef2;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .ex-date {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 46px;
            padding: .3rem 0;
            border-radius: 8px;
            background: #fff1e6;
            color: #c2410c;
            line-height: 1.1;
        }

        .ex-date-day {
            font-size: 1.05rem;
            font-weight: 700;
        }

        .ex-date-mon {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .ex-subject {
            font-weight: 600;
            color: #0f172a;
        }

        .ex-course {
            font-size: .74rem;
            color: #64748b;
        }

        .ex-time {
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        /* the paper, as a line rather than a raw filename: a row can point at a
           file that is gone, and that has to look different from one that is not */
        .ex-paper {
            display: flex;
            align-items: center;
            gap: .4rem;
            max-width: 16rem;
            font-size: .76rem;
        }

        .ex-paper-name {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ex-paper.is-missing {
            color: #dc2626;
        }

        .ex-paper.is-missing .ex-paper-name {
            text-decoration: line-through;
        }

        .ex-paper-size {
            flex: 0 0 auto;
            font-size: .68rem;
            color: #94a3b8;
        }

        .ex-empty {
            padding: 3rem 1rem;
            text-align: center;
        }

        .ex-empty i {
            font-size: 2.4rem;
            color: #cbd5e1;
        }

        .ex-count {
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .ex-count.is-none {
            color: #b45309;
            font-weight: 600;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Exams</h4>
            {{-- <div class="text-muted small">
                {{ $exams->count() }} sitting(s). A published exam appears in the student portal for the
                duration of its window only.
            </div> --}}
        </div>
        <a href="{{ route('exam.createPage') }}" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Schedule Exam
        </a>
    </div>



    {{-- filters: the subject list follows the chosen course --}}
    <form method="GET" action="{{ route('exam.index') }}" class="ex-shell mb-3">
        <div class="p-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
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

                <div class="col-12 col-md-3">
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

                <div class="col-12 col-md-3">
                    <label for="is_published" class="form-label small fw-semibold mb-1">Visibility</label>
                    <select name="is_published" id="is_published" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (ExamQuestion::PUBLISH_STATES as $value => $label)
                            <option value="{{ $value }}" @selected($selectedState === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                        <i class="bx bx-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('exam.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="ex-shell">
        @if ($exams->isEmpty())
            <div class="ex-empty">
                <i class="bx bx-calendar-x"></i>
                <div class="fw-semibold mt-2">
                    @if ($selectedCourseId || $selectedSubjectId || $selectedState)
                        No exams match these filters
                    @else
                        No exams scheduled yet
                    @endif
                </div>
                <div class="text-muted small mb-3">
                    Schedule one and publish it to put a question paper in front of your students.
                </div>
                <a href="{{ route('exam.createPage') }}" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-plus me-1"></i> Schedule Exam
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <caption class="visually-hidden">Scheduled exams, newest day first</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Time</th>
                            <th scope="col">Visibility</th>
                            {{-- <th scope="col">Question paper</th> --}}
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $exam)
                            @php
                                $status = $exam->scheduleStatus($now);
                                $file = $paper($exam->question_file);
                                $enrolled = $enrolledByCourse[$exam->course_id] ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="ex-date">
                                        <span class="ex-date-day">{{ $exam->exam_date?->format('d') ?? '--' }}</span>
                                        <span class="ex-date-mon">{{ $exam->exam_date?->format('M') ?? '—' }}</span>
                                    </span>
                                </td>

                                <td>
                                    <div class="ex-subject">{{ $exam->subject?->name ?? 'Subject #' . $exam->subject_id }}</div>
                                    <div class="ex-course">{{ $exam->course?->name ?? 'Course #' . $exam->course_id }}</div>
                                </td>

                                <td class="ex-time">
                                    {{ TimeOfDay::format($exam->start_time) ?? '—' }}
                                    &ndash;
                                    {{ TimeOfDay::format($exam->end_time) ?? '—' }}
                                    <div class="ex-course">
                                        {{ ExamQuestion::SCHEDULE_STATUSES[$status] }}
                                        @if ($status === ExamQuestion::ONGOING && ! $exam->isSubmitOpen($now))
                                            &middot; uploads closed
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if ($exam->isPublished())
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Not published</span>
                                    @endif
                                </td>
{{--
                                <td>
                                    <span class="ex-count{{ $exam->answers_count === 0 ? ' is-none' : '' }}">
                                        {{ $exam->answers_count }} / {{ $enrolled }}
                                    </span>
                                </td> --}}

                                {{-- <td>
                                    @if ($file['label'] === null)
                                        <span class="text-muted small">No paper uploaded</span>
                                    @else
                                        <div class="ex-paper{{ $file['missing'] ? ' is-missing' : '' }}">
                                            <i class="bx bx-file"></i>
                                            <span class="ex-paper-name" title="{{ $file['label'] }}">
                                                {{ $file['label'] }}
                                            </span>
                                            @if ($file['missing'])
                                                <span class="badge text-bg-danger">missing</span>
                                            @else
                                                <span class="ex-paper-size">{{ $file['size'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td> --}}

                                <td class="text-right text-nowrap">
                                    <a href="{{ route('exam.edit', ['id' => $exam->id]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit this exam">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="{{ route('exam.delete', ['id' => $exam->id]) }}"
                                        class="btn btn-sm btn-outline-danger" title="Delete this exam"
                                        data-confirm="Delete the {{ $exam->subject?->name }} exam on {{ $exam->exam_date?->format('d M Y') }}? The uploaded paper and every script handed in for it are removed with it.">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

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

                subjectSelect.innerHTML = '<option value="">All subjects</option>';
                (map[courseSelect.value] || []).forEach((subject) => {
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
