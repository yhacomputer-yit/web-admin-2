@extends('admin.master.master')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Search by Student / Course / Section" id="searchInput">
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-2">
        <div class="fs-4 mb-4"><i class="bx bx-book-add fs-3 mb-1"></i>Course Enrollment</div>

        <div class="col-12 mb-5">
            <div class="table-responsive text-nowrap bg-light rounded shadow mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Username</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Enroll Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="enrollmentTableBody">
                        @forelse ($enrollments as $enrollment)
                            <tr class="enrollment-row">
                                <td>{{ $enrollment->id }}</td>
                                <td class="enrollment-text">{{ $enrollment->student?->name ?? '—' }}</td>
                                <td class="enrollment-text">{{ $enrollment->student?->username ?? '—' }}</td>
                                <td class="enrollment-text">{{ $enrollment->course?->name ?? '—' }}</td>
                                <td class="enrollment-text">{{ $enrollment->section?->name ?? '—' }}</td>
                                <td>{{ $enrollment->enroll_date?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('enrollment.edit', ['id' => $enrollment->id]) }}" class="text-decoration-none me-2">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('enrollment.delete', ['id' => $enrollment->id]) }}" class="text-decoration-none text-danger"
                                        onclick="return confirm('Remove this enrollment?')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No enrollments yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $enrollments->links() }}

            <a style="background-color: #ff6c0f; color:white;" href="{{ route('enrollment.createPage') }}" class="btn mb-5 d-inline">
                <i class="bx bx-plus"></i> Add Enrollment
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('enrollmentTableBody');

        searchInput.addEventListener('input', function () {
            const term = searchInput.value.toLowerCase();
            const rows = tableBody.getElementsByClassName('enrollment-row');

            Array.from(rows).forEach(function (row) {
                const cells = row.querySelectorAll('.enrollment-text');
                const text = Array.from(cells).map(c => c.textContent.toLowerCase()).join(' ');
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });
</script>
@endsection
