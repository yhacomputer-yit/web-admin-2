@extends('admin.master.master')

@section('title', 'Attendance History')

@section('content')
    <div class="att-page">

        @if (session('success'))
            <span data-flash-toast="{{ session('success') }}" data-flash-type="success" hidden></span>
        @endif

        @if (session('error'))
            <span data-flash-toast="{{ session('error') }}" data-flash-type="error" hidden></span>
        @endif

        <div class="att-page-head">
            <div>
                <h4 class="att-page-title">Attendance History</h4>
                <p class="att-page-sub">
                    {{ \Carbon\Carbon::parse($filters['from'])->format('d M Y') }}
                    &rarr;
                    {{ \Carbon\Carbon::parse($filters['to'])->format('d M Y') }}
                    @if ($filters['student_id'])
                        &middot; filtered to one student
                    @endif
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('attendance.report', request()->query()) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-bar-chart-alt-2 me-1"></i> Reports
                </a>
                <a href="{{ route('attendance.exportFiltered', request()->query()) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-export me-1"></i> Export CSV
                </a>
                <a href="{{ route('attendance.createPage') }}" class="btn btn-sm att-btn-primary">
                    <i class="bx bx-user-check me-1"></i> Mark Attendance
                </a>
            </div>
        </div>

        {{-- ---------- filters ---------- --}}
        <form method="GET" action="{{ route('attendance.index') }}" class="card mb-3">
            <div class="card-body">

                <div class="row g-3">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <label for="f_course" class="form-label small fw-semibold mb-1">Course</label>
                        <select name="course_id" id="f_course" class="form-select form-select-sm"
                            data-dependent data-sections-url="{{ route('attendance.forCourse', ['courseId' => '__ID__']) }}"
                            data-subjects-url="{{ route('attendance.subjectsForCourse', ['courseId' => '__ID__']) }}">
                            <option value="">All courses</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($filters['course_id'] === $course->id)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <label for="f_section" class="form-label small fw-semibold mb-1">Section</label>
                        <select name="section_id" id="f_section" class="form-select form-select-sm">
                            <option value="">All sections</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected($filters['section_id'] === $section->id)>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <label for="f_subject" class="form-label small fw-semibold mb-1">Subject</label>
                        <select name="subject_id" id="f_subject" class="form-select form-select-sm">
                            <option value="">All subjects</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected($filters['subject_id'] === $subject->id)>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-1">
                        <label for="f_from" class="form-label small fw-semibold mb-1">From</label>
                        <input type="date" name="from" id="f_from" class="form-control form-control-sm"
                            value="{{ $filters['from'] }}">
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="f_to" class="form-label small fw-semibold mb-1">To</label>
                        <input type="date" name="to" id="f_to" class="form-control form-control-sm"
                            value="{{ $filters['to'] }}">
                    </div>
                </div>

                {{-- less-used filters, collapsed unless one is active --}}
                <div class="att-more" id="attMoreFilters" hidden>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label for="f_status" class="form-label small fw-semibold mb-1">Status</label>
                            <select name="status" id="f_status" class="form-select form-select-sm"
                                @data-active="{{ $filters['status'] ? '1' : '0' }}">
                                <option value="">All statuses</option>
                                @foreach ($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $filters['status'] === (string) $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($filters['student_id'])
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <label for="f_student" class="form-label small fw-semibold mb-1">Student</label>
                                <input type="hidden" name="student_id" id="f_student" value="{{ $filters['student_id'] }}">
                                <div class="form-control form-control-sm att-chip-row" style="height:auto">
                                    <span class="att-badge att-badge-1">Drill-down active</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3 align-items-center">
                    <button type="submit" class="btn btn-sm att-btn-primary" data-loading="Applying...">
                        <i class="bx bx-filter me-1"></i> Apply filters
                    </button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-reset me-1"></i> Reset
                    </a>

                    <button type="button" class="att-more-toggle ms-2" id="attMoreToggle"
                        aria-expanded="false" aria-controls="attMoreFilters">
                        <i class="bx bx-chevron-down"></i> More filters
                    </button>

                    <span class="ms-auto small text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        Reset returns the date range to the current month.
                    </span>
                </div>
            </div>
        </form>

        {{-- ---------- summary ---------- --}}
        <div class="row g-2 mb-3">
            <div class="col-6 col-lg">
                <div class="att-sum">
                    <div class="att-sum-label">Total Students</div>
                    <div class="att-sum-value">{{ $totalStudents }}</div>
                    <div class="att-sum-pct">distinct &middot; {{ $totalRecords }} mark(s)</div>
                </div>
            </div>

            @foreach ($statusLabels as $value => $label)
                <div class="col-6 col-lg">
                    <div class="att-sum att-sum-{{ $value }}">
                        <div class="att-sum-label">{{ $label }}</div>
                        <div class="att-sum-value">{{ $statusCounts[$value] ?? 0 }}</div>
                        <div class="att-sum-pct">
                            {{ $totalRecords > 0 ? round(($statusCounts[$value] ?? 0) / $totalRecords * 100, 1) : 0 }}% of marks
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-6 col-lg">
                <div class="att-sum att-sum-ok">
                    <div class="att-sum-label">Attended %</div>
                    <div class="att-sum-value">{{ $attendedPercent }}%</div>
                    <div class="att-sum-pct">attended marks</div>
                </div>
            </div>
        </div>

        {{-- ---------- records ---------- --}}
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="att-section-title">
                    <i class="bx bx-list-ul"></i> Records
                    <span class="att-section-count">{{ $records->total() }} total</span>
                </h5>

                @if ($records->hasPages())
                    <span class="small text-muted">
                        Showing {{ $records->firstItem() }}&ndash;{{ $records->lastItem() }}
                    </span>
                @endif
            </div>

            @if ($records->isEmpty())
                <div class="att-empty">
                    <span class="att-empty-icon"><i class="bx bx-calendar-x"></i></span>
                    <h6 class="att-empty-title">No attendance records match these filters</h6>
                    <p class="att-empty-text">
                        Nothing was recorded in
                        {{ \Carbon\Carbon::parse($filters['from'])->format('d M Y') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($filters['to'])->format('d M Y') }}.
                        Widen the date range, or clear the course and subject filters.
                    </p>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                        <i class="bx bx-reset me-1"></i> Clear filters
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table att-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Section</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th style="width: 92px;" class="text-end">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td class="text-nowrap">{{ $record->date?->format('d M Y') }}</td>
                                    <td>
                                        <div class="att-cell-name">{{ $record->student?->name ?? '—' }}</div>
                                        @if ($record->student?->username)
                                            <code class="att-cell-user">{{ $record->student->username }}</code>
                                        @endif
                                    </td>
                                    <td>{{ $record->course?->name ?? '—' }}</td>
                                    <td class="text-nowrap">{{ $record->section?->name ?? '—' }}</td>
                                    <td>{{ $record->subject?->name ?? '—' }}</td>
                                    <td>
                                        <span class="att-badge att-badge-{{ \App\Models\Attendance::markableStatus((int) $record->status) }}">
                                            {{ \App\Models\Attendance::markableLabel((int) $record->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        <span class="att-remark-text">{{ $record->remark ?: '—' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="att-edit-btn" data-bs-toggle="modal"
                                            data-bs-target="#attEditModal"
                                            data-action="{{ route('attendance.updateStatus', ['id' => $record->id]) }}"
                                            data-student="{{ $record->student?->name ?? 'Unknown' }}"
                                            data-meta="{{ $record->date?->format('d M Y') }} &middot; {{ $record->course?->name }} &middot; {{ $record->subject?->name }}"
                                            data-status="{{ \App\Models\Attendance::markableStatus((int) $record->status) }}"
                                            data-remark="{{ $record->remark }}">
                                            <i class="bx bx-edit-alt"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($records->hasPages())
                    <div class="card-footer bg-transparent">
                        <nav>{!! $records->links() !!}</nav>
                    </div>
                @endif
            @endif
        </div>

        {{-- ---------- row edit modal ---------- --}}
        <div class="modal fade" id="attEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="" id="attEditForm">
                        @csrf
                        @method('PATCH')

                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title" id="attEditStudent">Edit attendance</h5>
                                <div class="small text-muted" id="attEditMeta"></div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="form-label small fw-semibold mb-1">Status</div>
                            <div class="att-modal-status mb-3" role="group" aria-label="Attendance status">
                                @foreach ($statusLabels as $value => $label)
                                    <label class="att-toggle-btn att-{{ $value }}">
                                        <input type="radio" class="visually-hidden" name="status" value="{{ $value }}">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <label for="attEditRemark" class="form-label small fw-semibold mb-1">Remark</label>
                            <input type="text" name="remark" id="attEditRemark" class="form-control"
                                maxlength="500" placeholder="Optional note (leave empty to clear)">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn att-btn-primary" data-loading="Saving...">
                                <i class="bx bx-save me-1"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/attendance.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/toast.js') }}"></script>
    <script src="{{ asset('admin/course-section-link.js') }}"></script>
    <script src="{{ asset('admin/attendance-history.js') }}"></script>
@endpush
