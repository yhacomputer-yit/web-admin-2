@extends('admin.master.master')

@section('title', 'Attendance Reports')

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
                <h4 class="att-page-title">Attendance Reports</h4>
                <p class="att-page-sub">
                    {{ \Carbon\Carbon::parse($from)->format('d M Y') }} &rarr;
                    {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                    @if ($selectedCourseId)
                        &middot; {{ $courses->firstWhere('id', $selectedCourseId)?->name }}
                    @else
                        &middot; all courses
                    @endif
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('attendance.exportReport', request()->query()) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-export me-1"></i> Export CSV
                </a>
                <a href="{{ route('attendance.index', request()->query()) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-history me-1"></i> Review History
                </a>
                <a href="{{ route('attendance.createPage') }}" class="btn btn-sm att-btn-primary">
                    <i class="bx bx-user-check me-1"></i> Mark Attendance
                </a>
            </div>
        </div>

        {{-- ---------- filters: export reuses the same query string ---------- --}}
        <form method="GET" action="{{ route('attendance.report') }}" class="card mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="r_course" class="form-label small fw-semibold mb-1">Course</label>
                        <select name="course_id" id="r_course" class="form-select">
                            <option value="">All courses</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($course->id === $selectedCourseId)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="r_from" class="form-label small fw-semibold mb-1">From</label>
                        <input type="date" name="from" id="r_from" class="form-control" value="{{ $from }}">
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="r_to" class="form-label small fw-semibold mb-1">To</label>
                        <input type="date" name="to" id="r_to" class="form-control" value="{{ $to }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn w-100 att-btn-primary" data-loading="Building...">
                            <i class="bx bx-filter-alt me-1"></i> Apply
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3 align-items-center">
                    <a href="{{ route('attendance.report', [
                        'from' => now()->startOfMonth()->toDateString(),
                        'to' => now()->endOfMonth()->toDateString(),
                        'course_id' => $selectedCourseId,
                    ]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-calendar me-1"></i> This month
                    </a>
                    <a href="{{ route('attendance.report', [
                        'from' => now()->subMonths(3)->startOfMonth()->toDateString(),
                        'to' => now()->endOfMonth()->toDateString(),
                        'course_id' => $selectedCourseId,
                    ]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-history me-1"></i> Last 3 months
                    </a>

                    <span class="ms-auto small text-muted">
                        <i class="bx bx-download me-1"></i>
                        Export includes every filter on this form.
                    </span>
                </div>
            </div>
        </form>

        {{-- ---------- summary ---------- --}}
        <div class="row g-2 mb-3">
            <div class="col-6 col-lg-3">
                <div class="att-sum">
                    <div class="att-sum-label">Total Records</div>
                    <div class="att-sum-value">{{ number_format($totalRecords) }}</div>
                    <div class="att-sum-pct">marks in range</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="att-sum">
                    <div class="att-sum-label">Courses</div>
                    <div class="att-sum-value">{{ $courseBreakdown->count() }}</div>
                    <div class="att-sum-pct">{{ $subjectBreakdown->count() }} subject(s)</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="att-sum">
                    <div class="att-sum-label">Students</div>
                    <div class="att-sum-value">{{ $studentBreakdown->count() }}</div>
                    <div class="att-sum-pct">with marks</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="att-sum att-sum-ok">
                    <div class="att-sum-label">Months Covered</div>
                    <div class="att-sum-value">{{ $monthlyBreakdown->count() }}</div>
                    <div class="att-sum-pct">in this range</div>
                </div>
            </div>
        </div>

        @if ($totalRecords === 0)
            {{-- nothing to report: one clear empty state instead of four empty tables --}}
            <div class="card">
                <div class="att-empty">
                    <span class="att-empty-icon"><i class="bx bx-bar-chart-alt-2"></i></span>
                    <h6 class="att-empty-title">No attendance recorded in this period</h6>
                    <p class="att-empty-text">
                        Nothing was marked between
                        {{ \Carbon\Carbon::parse($from)->format('d M Y') }} and
                        {{ \Carbon\Carbon::parse($to)->format('d M Y') }}.
                        Widen the date range, pick a different course, or mark a class first.
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="{{ route('attendance.report', [
                            'from' => now()->startOfMonth()->toDateString(),
                            'to' => now()->endOfMonth()->toDateString(),
                        ]) }}" class="btn btn-sm btn-outline-secondary">This month</a>
                        <a href="{{ route('attendance.createPage') }}" class="btn btn-sm att-btn-primary">
                            <i class="bx bx-user-check me-1"></i> Mark Attendance
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- ---------- top 10 most absent ----------
                 Hidden entirely when nobody was absent: a table with no rows
                 reads as a bug, so the section simply does not appear. --}}
            @if ($mostAbsent->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header bg-transparent d-flex align-items-center">
                        <h5 class="att-section-title">
                            <i class="bx bx-user-x"></i> Top 10 Most Absent
                            <span class="att-section-count">ranked by absence rate</span>
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table att-table">
                            <thead>
                                <tr>
                                    <th style="width:52px">#</th>
                                    <th>Student</th>
                                    <th class="text-center">Absences</th>
                                    <th class="text-center">Classes</th>
                                    <th class="text-end">Absence rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mostAbsent as $row)
                                    <tr>
                                        <td>
                                            <span class="att-rank {{ $row->rank <= 3 ? 'att-rank-top' : '' }}">
                                                {{ $row->rank }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="att-student-link" href="{{ route('attendance.index', [
                                                'student_id' => $row->student_id,
                                                'course_id' => $selectedCourseId,
                                                'from' => $from,
                                                'to' => $to,
                                            ]) }}">
                                                {{ $row->name }}
                                            </a>
                                            <code class="att-cell-user">{{ $row->username }}</code>
                                        </td>
                                        <td class="text-center fw-bold">{{ $row->absent }}</td>
                                        <td class="text-center">{{ $row->total }}</td>
                                        <td class="text-end">
                                            <div class="att-rate">
                                                <div class="att-rate-bar">
                                                    <div class="att-pct-fill {{ $row->absent_percent >= 50 ? 'att-pct-bad' : ($row->absent_percent >= 30 ? 'att-pct-warn' : 'att-pct-ok') }}"
                                                        style="width:{{ min(100, (float) $row->absent_percent) }}%"></div>
                                                </div>
                                                <span class="att-rate-val {{ $row->absent_percent >= 50 ? 'att-pct-bad' : ($row->absent_percent >= 30 ? 'att-pct-warn' : 'att-pct-ok') }}">
                                                    {{ $row->absent_percent }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card mb-3">
                    <div class="att-empty-sm">
                        <span class="att-empty-icon"><i class="bx bx-check-circle"></i></span>
                        <div class="fw-semibold" style="color:var(--att-ink)">No absences in this period</div>
                        <div class="mt-1">Every mark in range was attended or leave.</div>
                    </div>
                </div>
            @endif

            <div class="row g-3">
                {{-- ---------- course ---------- --}}
                {{-- <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="att-section-title">
                                <i class="bx bx-book"></i> Course Attendance
                                <span class="att-section-count">{{ $courseBreakdown->count() }}</span>
                            </h5>
                        </div>
                        @if ($courseBreakdown->isEmpty())
                            <div class="att-empty-sm">No course data in this range.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table att-table">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th class="text-center">Present</th>
                                            <th class="text-center">Absent</th>
                                            <th class="text-center">Late</th>
                                            <th class="text-center">Leave</th>
                                            <th class="text-end">Attended %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courseBreakdown as $row)
                                            <tr>
                                                <td class="att-cell-name">{{ $row->name }}</td>
                                                <td class="text-center">{{ $row->present }}</td>
                                                <td class="text-center text-danger">{{ $row->absent }}</td>
                                                <td class="text-center">{{ $row->late }}</td>
                                                <td class="text-center text-muted">{{ $row->leave }}</td>
                                                <td class="text-end">@include('admin.attendance.partials.percent', ['value' => $row->percent])</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div> --}}

                {{-- ---------- subject ---------- --}}
                {{-- <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="att-section-title">
                                <i class="bx bx-collection"></i> Subject Attendance
                                <span class="att-section-count">{{ $subjectBreakdown->count() }}</span>
                            </h5>
                        </div>
                        @if ($subjectBreakdown->isEmpty())
                            <div class="att-empty-sm">No subject data in this range.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table att-table">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th class="text-center">Present</th>
                                            <th class="text-center">Absent</th>
                                            <th class="text-center">Late</th>
                                            <th class="text-center">Leave</th>
                                            <th class="text-end">Attended %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subjectBreakdown as $row)
                                            <tr>
                                                <td class="att-cell-name">{{ $row->name }}</td>
                                                <td class="text-center">{{ $row->present }}</td>
                                                <td class="text-center text-danger">{{ $row->absent }}</td>
                                                <td class="text-center">{{ $row->late }}</td>
                                                <td class="text-center text-muted">{{ $row->leave }}</td>
                                                <td class="text-end">@include('admin.attendance.partials.percent', ['value' => $row->percent])</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div> --}}

                {{-- ---------- individual students ---------- --}}
                <div class="col-12 col-xl-12">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="att-section-title">
                                <i class="bx bx-user"></i> Individual Students
                                <span class="att-section-count">click a name to open their history</span>
                            </h5>
                        </div>
                        @if ($studentBreakdown->isEmpty())
                            <div class="att-empty-sm">No students in this range.</div>
                        @else
                            <div class="table-responsive" style="max-height:26rem;overflow-y:auto">
                                <table class="table att-table">
                                    <thead class="sticky-top">
                                        <tr>
                                            <th>Student</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Attended</th>
                                            <th class="text-center">Absent</th>
                                            <th class="text-center">Leave</th>
                                            <th class="text-end">Attended %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentBreakdown as $row)
                                            <tr>
                                                <td>
                                                    <a class="att-student-link" href="{{ route('attendance.index', [
                                                        'student_id' => $row->student_id,
                                                        'course_id' => $selectedCourseId,
                                                        'from' => $from,
                                                        'to' => $to,
                                                    ]) }}">
                                                        {{ $row->name }}
                                                    </a>
                                                    <code class="att-cell-user">{{ $row->username }}</code>
                                                </td>
                                                <td class="text-center">{{ $row->total }}</td>
                                                <td class="text-center">{{ $row->attended }}</td>
                                                <td class="text-center text-danger">{{ $row->absent }}</td>
                                                <td class="text-center">{{ $row->leave }}</td>
                                                <td class="text-end">@include('admin.attendance.partials.percent', ['value' => $row->percent])</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ---------- monthly ---------- --}}
                {{-- <div class="col-12 col-xl-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="att-section-title">
                                <i class="bx bx-calendar"></i> Monthly Summary
                                <span class="att-section-count">{{ $monthlyBreakdown->count() }} month(s)</span>
                            </h5>
                        </div>
                        @if ($monthlyBreakdown->isEmpty())
                            <div class="att-empty-sm">No monthly data in this range.</div>
                        @else
                            <div class="table-responsive" style="max-height:26rem;overflow-y:auto">
                                <table class="table att-table">
                                    <thead class="sticky-top">
                                        <tr>
                                            <th>Month</th>
                                            <th class="text-center">Classes</th>
                                            <th class="text-center">Present</th>
                                            <th class="text-center">Absent</th>
                                            <th class="text-center">Late</th>
                                            <th class="text-center">Leave</th>
                                            <th class="text-end">Attended %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monthlyBreakdown as $row)
                                            <tr>
                                                <td class="att-cell-name">{{ $row->label }}</td>
                                                <td class="text-center">{{ $row->total }}</td>
                                                <td class="text-center">{{ $row->present }}</td>
                                                <td class="text-center text-danger">{{ $row->absent }}</td>
                                                <td class="text-center">{{ $row->late }}</td>
                                                <td class="text-center text-muted">{{ $row->leave }}</td>
                                                <td class="text-end">@include('admin.attendance.partials.percent', ['value' => $row->percent])</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div> --}}
            </div>

            <p class="small text-muted mt-3 mb-0">
                <i class="bx bx-info-circle me-1"></i>
                Bars follow the attendance threshold: green at 90% and above, amber from 70%, red below 70%.
            </p>
        @endif

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/attendance.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admin/toast.js') }}"></script>
@endpush
