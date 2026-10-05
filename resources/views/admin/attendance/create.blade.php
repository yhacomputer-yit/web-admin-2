@extends('admin.master.master')

@section('title', 'Mark Attendance')

@section('content')
    <div class="att-page">

        {{-- session flashes become toasts; the nodes are removed by toast.js --}}


        {{-- Button metadata is built once, outside the loops: a raw
             @php ... @endphp block nested inside an @foreach gets swallowed
             by Blade's block matcher and leaves the loop uncompiled. --}}
        @php
            $statusButtons = [];
            foreach ($attendanceStatuses as $sv => $sl) {
                $statusButtons[$sv] = [
                    'label' => $sl,
                    'icon' => [1 => 'bx-check', 2 => 'bx-x', 4 => 'bx-calendar-x'][$sv] ?? 'bx-minus',
                ];
            }
        @endphp

        <div class="att-page-head">
            <div>
                <h4 class="att-page-title">Mark Attendance</h4>
                <p class="att-page-sub">Only students with an active enrollment for this class are listed.</p>
            </div>
            {{-- <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('attendance.report') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-bar-chart-alt-2 me-1"></i> Reports
                </a>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-history me-1"></i> Review History
                </a>
                <a href="{{ route('course.section.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-link-alt me-1"></i> Sections
                </a>
            </div> --}}
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="fw-semibold mb-1">
                    <i class="bx bx-error-circle me-1"></i> Attendance was not saved
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ---------- class selectors ---------- --}}
        <form method="GET" action="{{ route('attendance.createPage') }}" class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="course_id" class="form-label small fw-semibold mb-1">Course <span class="text-danger">*</span></label>
                        <select name="course_id" id="course_id" class="form-select" required
                            data-dependent data-sections-url="{{ route('attendance.forCourse', ['courseId' => '__ID__']) }}"
                            data-subjects-url="{{ route('attendance.subjectsForCourse', ['courseId' => '__ID__']) }}">
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($course->id === $selectedCourseId)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="section_id" class="form-label small fw-semibold mb-1">Section <span class="text-danger">*</span></label>
                        <select name="section_id" id="section_id" class="form-select" required>
                            @if (! $selectedCourseId)
                                <option value="">Select Course first</option>
                            @endif
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected($section->id === $selectedSectionId)>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-3">
                        <label for="subject_id" class="form-label small fw-semibold mb-1">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            @if (! $selectedCourseId)
                                <option value="">Select Course first</option>
                            @endif
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected($subject->id === $selectedSubjectId)>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-2">
                        <label for="date" class="form-label small fw-semibold mb-1">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $date }}" required>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" class="btn btn-sm att-btn-primary" data-loading="Loading students...">
                        <i class="bx bx-user-check me-1"></i> Load Students
                    </button>

                    @if ($selectedCourseId && $selectedSectionId)
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#completeClassModal"
                            title="Mark every active enrollment in this class as completed">
                            <i class="bx bx-check-square me-1"></i> Complete Class
                        </button>
                    @endif

                    <div class="ms-auto small text-muted align-self-center">
                        <i class="bx bx-info-circle me-1"></i>
                        Picking a class reloads the roster. Nothing is written until you save.
                    </div>
                </div>

                @if ($selectedCourseId && $selectedSectionId && $classClosed)
                    <div class="alert alert-warning mt-3 mb-0 py-2">
                        <i class="bx bx-lock me-1"></i>
                        <strong>This class is completed.</strong>
                        No active enrollment remains, so attendance can no longer be marked for
                        {{ $courses->firstWhere('id', $selectedCourseId)?->name }} &rarr;
                        {{ $sections->firstWhere('id', $selectedSectionId)?->name }}.
                    </div>
                @endif
            </div>
        </form>

        {{-- ---------- roster ---------- --}}
        @if (! $selectedCourseId || ! $selectedSectionId || ! $selectedSubjectId)
            <div class="card">
                <div class="att-empty">
                    <span class="att-empty-icon"><i class="bx bx-user-check"></i></span>
                    @if ($selectedCourseId && $subjects->isEmpty())
                        <h6 class="att-empty-title">No subjects available for this course</h6>
                        <p class="att-empty-text">Link subjects to this course before marking attendance.</p>
                    @else
                        <h6 class="att-empty-title">Pick a class to continue</h6>
                        <p class="att-empty-text">Choose a course, section, subject and date, then load the class list.</p>
                    @endif
                </div>
            </div>
        @elseif ($roster->isEmpty())
            <div class="card">
                <div class="att-empty">
                    <span class="att-empty-icon"><i class="bx bx-user-x"></i></span>
                    <h6 class="att-empty-title">No active enrollments for this class</h6>
                    <p class="att-empty-text">
                        Attendance can only be marked for students enrolled in this course and section.
                    </p>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('attendance.store') }}" id="attendanceForm">
                @csrf
                <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
                <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="card">
                    <div class="card-body">

                        {{-- toolbar: bulk actions + live tally --}}
                        <div class="att-roster-head mb-3">
                            <div class="att-bulk-bar">
                                <span class="fw-semibold small text-muted me-1">
                                    <i class="bx bx-group me-1"></i> {{ $roster->count() }} student(s)
                                </span>

                                <button type="button" class="btn btn-sm btn-outline-success" data-bulk="1">
                                    <i class="bx bx-check me-1"></i> All Attended
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bulk="2">
                                    <i class="bx bx-x me-1"></i> All Absent
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bulk="4">
                                    <i class="bx bx-calendar-x me-1"></i> All Leave
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bulk="">
                                    <i class="bx bx-eraser me-1"></i> Clear
                                </button>
                            </div>

                            <div class="att-counter" id="statusSummary" aria-live="polite"></div>
                        </div>

                        {{-- ---------- student cards ---------- --}}
                        <div class="att-grid">
                            @foreach ($roster as $row)
                                <div class="att-card" data-student-card="{{ $loop->index }}">
                                    <input type="hidden" name="entries[{{ $loop->index }}][student_id]"
                                        value="{{ $row->student?->id }}">

                                    <div class="att-card-head">
                                        @if ($row->student?->image)
                                            <img src="{{ Storage::url($row->student->image) }}"
                                                onerror="this.onerror=null;this.src='/image/no-image.jpg';"
                                                alt="{{ $row->student->name }}" class="att-avatar" width="40" height="40">
                                        @else
                                            <div class="att-avatar att-avatar-initials">{{ $row->initials }}</div>
                                        @endif

                                        <div class="text-truncate">
                                            <div class="att-card-name text-truncate" data-student-name="{{ $row->student?->name ?? 'Unknown' }}">
                                                {{ $row->student?->name ?? '—' }}
                                            </div>
                                            @if ($row->student?->username)
                                                <code class="att-card-user">{{ $row->student->username }}</code>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="att-toggle" role="group"
                                        aria-label="Attendance for {{ $row->student?->name }}">
                                        @foreach ($statusButtons as $sv => $btn)
                                            <label class="att-toggle-btn att-{{ $sv }}"
                                                title="{{ $btn['label'] }}">
                                                <input type="radio" class="visually-hidden"
                                                    name="entries[{{ $loop->parent->index }}][status]"
                                                    value="{{ $sv }}" data-att-status
                                                    @checked((int) $row->status === (int) $sv)>
                                                <i class="bx {{ $btn['icon'] }} att-toggle-ico"></i>
                                                <span>{{ $btn['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <input type="text" name="entries[{{ $loop->index }}][remark]"
                                        class="form-control form-control-sm att-remark"
                                        placeholder="Remark (optional)" maxlength="500"
                                        value="{{ $row->remark }}"
                                        aria-label="Remark for {{ $row->student?->name }}">
                                </div>
                            @endforeach
                        </div>

                        {{-- ---------- validation warning ---------- --}}
                        <div class="att-warn mt-3" id="saveWarning" role="alert" hidden>
                            <i class="bx bx-error-circle"></i>
                            <div>
                                <div class="fw-semibold">Some students have no status selected.</div>
                                <div>Mark every student before saving &mdash; missing: <span id="saveWarningList"></span></div>
                            </div>
                        </div>

                        {{-- ---------- sticky action bar ---------- --}}
                        <div class="att-savebar">
                            <p class="att-savebar-hint" id="saveBarHint"></p>
                            <div class="att-savebar-actions">
                                <a href="{{ route('attendance.createPage') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-x me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn att-btn-primary" id="saveAttendanceBtn"
                                    data-loading="Saving...">
                                    <i class="bx bx-save me-1"></i> Save Attendance
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif

        {{-- ---------- complete class ---------- --}}
        @if ($selectedCourseId && $selectedSectionId)
            <div class="modal fade" id="completeClassModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('attendance.completeClass') }}">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
                            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">

                            <div class="modal-header">
                                <h5 class="modal-title">Complete this class?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-2">
                                    Every <strong>active</strong> enrollment in
                                    <strong>{{ $courses->firstWhere('id', $selectedCourseId)?->name }}</strong>
                                    &rarr;
                                    <strong>{{ $sections->firstWhere('id', $selectedSectionId)?->name }}</strong>
                                    will be set to <em>completed</em> with today's date.
                                </p>
                                <p class="mb-0 text-muted small">
                                    Attendance can no longer be marked for this class afterwards.
                                    Students can be re-enrolled to reopen it.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger" data-loading="Completing...">
                                    <i class="bx bx-check-square me-1"></i> Complete Class
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/attendance.css') }}">
@endpush

@push('scripts')
    {{-- toast.js is loaded by the layout, so every admin page has one --}}
    <script src="{{ asset('admin/course-section-link.js') }}"></script>
    <script src="{{ asset('admin/attendance-grid.js') }}"></script>
@endpush
