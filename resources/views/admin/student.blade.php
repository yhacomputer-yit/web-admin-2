@extends('admin.master.master')

@section('content')
<div class="container-fluid">

    {{-- one-time password reveal after a reset  --}}
    @if (session('new_password'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>New password for {{ session('new_password')['student'] }}:</strong>
            <code class="fs-6">{{ session('new_password')['password'] }}</code>
            <span class="text-muted">(copy it now - it will not be shown again)</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <div class="row mb-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="fs-4"><i class="bx bx-user fs-3 mb-1"></i>Student</div>
            <a style="background-color: #ff6c0f; color:white;" href="{{ route('student.createPage') }}" class="btn d-inline">
                <i class="bx bx-plus"></i> Add Student
            </a>
        </div>

        {{-- Filters  --}}
        <form method="GET" action="{{ route('admin.student') }}" class="filter-form row g-2 align-items-end mb-3">
            <div class="col-md-4 col-lg-3">
                <label for="search" class="form-label small mb-1">Search</label>
                <input type="text" name="search" id="search" class="form-control form-control-sm"
                    value="{{ request('search') }}" placeholder="Name / Username / Phone / NRC">
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="status" class="form-label small mb-1">Status</label>
                <select name="status" id="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
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
                <label for="min_courses" class="form-label small mb-1">Min. Courses</label>
                <select name="min_courses" id="min_courses" class="form-select form-select-sm">
                    <option value="">Any</option>
                    @foreach ([1, 2, 3, 4, 5] as $n)
                        <option value="{{ $n }}" @selected((string) request('min_courses') === (string) $n)>{{ $n }}+</option>
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
                <a href="{{ route('admin.student') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        {{-- Bulk actions: only shown once rows are ticked  --}}
        <form method="POST" action="{{ route('student.bulkStatus') }}" id="bulkForm">
            @csrf
            <div class="alert alert-secondary d-none align-items-center gap-3 py-2" id="bulkBar">
                <span class="fw-semibold"><span id="selectedCount">0</span> selected</span>
                <button type="submit" name="status" value="active" class="btn btn-sm btn-success">
                    <i class="bx bx-check"></i> Set Active
                </button>
                <button type="submit" name="status" value="inactive" class="btn btn-sm btn-secondary">
                    <i class="bx bx-x"></i> Set Inactive
                </button>
            </div>
        </form>

        <div class="col-12 mb-5">
            <div class="table-responsive text-nowrap mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 35px;">
                                <input type="checkbox" class="form-check-input" id="checkAll">
                            </th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Ph No</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @forelse ($students as $student)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input row-check" name="ids[]"
                                        value="{{ $student->id }}" form="bulkForm">
                                </td>
                                <td>{{ $student->id }}</td>
                                <td>
                                    <a href="{{ route('student.show', ['id' => $student->id]) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $student->name }}
                                    </a>
                                </td>
                                <td>{{ $student->username ?? '—' }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <span class="badge {{ $student->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('student.show', ['id' => $student->id]) }}" class="text-decoration-none me-2">
                                        <i class="bx bx-show me-1"></i> View
                                    </a>
                                    <a href="{{ route('student.edit', ['id' => $student->id]) }}" class="text-decoration-none me-2">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    {{-- <a href="{{ route('student.resetPassword', ['id' => $student->id]) }}"
                                        class="text-decoration-none me-2 text-warning"
                                        data-confirm="Generate a new password for {{ $student->name }}? The current one stops working."
                                                    data-confirm-tone="info" data-confirm-label="Generate">
                                        <i class="bx bx-key me-1"></i> Reset Pass
                                    </a> --}}
                                    <a href="{{ route('student.delete', ['id' => $student->id]) }}"
                                        class="text-decoration-none text-danger"
                                        data-confirm="Delete {{ $student->name }}? Their attendance, enrollments and marks go with them."
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No students match these filters
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const checks = Array.from(document.querySelectorAll('.row-check'));
        const bulkBar = document.getElementById('bulkBar');
        const count = document.getElementById('selectedCount');

        function refresh() {
            const n = checks.filter(c => c.checked).length;
            count.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
        }

        checkAll.addEventListener('change', function () {
            checks.forEach(c => c.checked = checkAll.checked);
            refresh();
        });
        checks.forEach(c => c.addEventListener('change', refresh));
        refresh();
    });
</script>
@endsection
