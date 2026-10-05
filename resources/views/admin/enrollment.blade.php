@extends('admin.master.master')

@section('content')
<div class="container-fluid">



    <div class="row mb-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="fs-4"><i class="bx bx-book-add fs-3 mb-1"></i>Course Enrollment</div>
            <a style="background-color: #ff6c0f; color:white;" href="{{ route('enrollment.createPage') }}" class="btn d-inline">
                <i class="bx bx-plus"></i> Add Enrollment
            </a>
        </div>

        {{-- Filters  --}}
        <form method="GET" action="{{ route('admin.enrollment') }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-4 col-lg-3">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="text" name="search" id="search" class="form-control form-control-sm"
                    value="{{ request('search') }}" placeholder="Student / Course">
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="course_id" class="form-label small mb-1">Course</label>
                <select name="course_id" id="course_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected((string) request('course_id') === (string) $course->id)>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="per_page" class="form-label small mb-1">Per Page</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm">
                    @foreach ([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-filter"></i> Filter
                </button>
                <a href="{{ route('admin.enrollment') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="col-12 mb-5">
            {{-- One block per student so their name is not repeated on every row  --}}
            @forelse ($students as $student)
                <div class="card mb-2 border-warning shadow-sm">
                    <div class="card-header border-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <a href="{{ route('student.show', ['id' => $student->id]) }}"
                                class="fw-semibold text-decoration-none">
                                {{ $student->name }}
                            </a>
                            @if ($student->username)
                                <code class="ms-2">{{ $student->username }}</code>
                            @endif
                            <span class="badge bg-secondary ms-2">
                                {{ $student->enrollments->count() }} course(s)
                            </span>
                        </div>
                        <a href="{{ route('student.show', ['id' => $student->id]) }}"
                            class="text-decoration-none small">View Profile <i class="bx bx-right-arrow-alt"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Section</th>
                                        <th>Enroll Date</th>
                                        <th>Status</th>
                                        <th style="width: 190px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statusClass = [
                                            \App\Models\StudentEnrollment::STATUS_ACTIVE => 'text-bg-success',
                                            \App\Models\StudentEnrollment::STATUS_COMPLETED => 'text-bg-primary',
                                            \App\Models\StudentEnrollment::STATUS_DROPPED => 'text-bg-secondary',
                                        ];
                                    @endphp
                                    @foreach ($student->enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->course?->name ?? '—' }}</td>
                                            <td>{{ $enrollment->section?->name ?? '—' }}</td>
                                            <td>{{ $enrollment->enroll_date?->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge {{ $statusClass[$enrollment->status] ?? 'text-bg-light border' }}">
                                                    {{ ucfirst($enrollment->status ?? 'active') }}
                                                </span>
                                                @if ($enrollment->complete_date)
                                                    <div class="text-muted" style="font-size:.7rem">
                                                        {{ $enrollment->complete_date->format('Y-m-d') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                @if ($enrollment->isActive() && $enrollment->section_id)
                                                    <form method="POST" class="d-inline"
                                                        action="{{ route('enrollment.completeClass') }}"
                                                        data-confirm="Mark {{ $enrollment->course?->name }} ({{ $enrollment->section?->name }}) as completed for {{ $student->name }}? Attendance can no longer be marked after this."
                                                        data-confirm-tone="info" data-confirm-label="Mark completed">
                                                        @csrf
                                                        <input type="hidden" name="course_id" value="{{ $enrollment->course_id }}">
                                                        <input type="hidden" name="section_id" value="{{ $enrollment->section_id }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-warning me-1"
                                                            title="Complete this class">
                                                            <i class="bx bx-check-circle me-1"></i>Complete
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('enrollment.edit', ['id' => $enrollment->id]) }}"
                                                    class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="bx bx-edit-alt me-1"></i>Edit
                                                </a>
                                                <a href="{{ route('enrollment.delete', ['id' => $enrollment->id]) }}"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-confirm="Remove this enrollment?">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="table-responsive text-nowrap bg-light rounded shadow mb-3">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-center text-muted py-4">No enrollments match these filters</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforelse

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}
                    of {{ $students->total() }} students
                </div>
                <nav>{!! $students->onEachSide(1)->links() !!}</nav>
            </div>
        </div>
    </div>
</div>
@endsection
