@extends('admin.master.master')

@section('title', 'Certificates')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Certificates</h4>
            <div class="text-muted small">
                {{ $certificates->where('remark', \App\Models\Certificate::RECEIVED)->count() }} of
                {{ $certificates->count() }} shown have been handed over. Issuing a certificate is
                not the same as collecting it, so a row starts life as not received.
            </div>
        </div>
        <a href="{{ route('certificate.createPage') }}" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Add Certificate
        </a>
    </div>


    <form method="GET" action="{{ route('certificate.index') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="q" class="form-label small fw-semibold mb-1">Student</label>
                    <input type="search" name="q" id="q" value="{{ $term }}"
                        class="form-control form-control-sm" placeholder="Name, username, phone or NRC">
                </div>
                <div class="col-12 col-md-3">
                    <label for="status" class="form-label small fw-semibold mb-1">Collected</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">Any</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
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
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                        <i class="bx bx-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('certificate.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        @if ($certificates->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bx bx-award" style="font-size: 2rem; color: #cbd5e1;"></i>
                <div class="fw-semibold mt-2">
                    @if ($term !== '' || $selectedCourseId || $selectedStatus)
                        No certificates match these filters
                    @else
                        No certificates issued yet
                    @endif
                </div>
                <div class="text-muted small mb-3">
                    Record a student who has finished, and tick the certificate off when they
                    come back for it.
                </div>
                <a href="{{ route('certificate.createPage') }}" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-plus me-1"></i> Add Certificate
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <caption class="visually-hidden">Certificates issued to students</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Completed</th>
                            <th scope="col">Certificate File</th>
                            <th scope="col">Remark</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($certificates as $certificate)
                            @php($student = $certificate->student)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $student?->name ?? 'Student #' . $certificate->student_id }}</div>
                                    <div class="small text-muted">
                                        {{ $student?->username ? '@' . $student->username : '—' }}
                                        @if ($student?->status)
                                            &middot; {{ ucfirst($student->status) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    {{ $certificate->complete_date?->format('j M Y') ?? '—' }}
                                </td>
                                <td>
                                    @if ($certificate->certificate_file)
                                        <img src="{{ asset('storage/' . $certificate->certificate_file) }}" alt="Certificate" style="max-height: 60px; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $certificate->isReceived() ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $certificate->remark }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('certificate.edit', ['id' => $certificate->id]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit this certificate">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="{{ route('certificate.delete', ['id' => $certificate->id]) }}"
                                        class="btn btn-sm btn-outline-danger" title="Delete this certificate"
                                        data-confirm="Delete the certificate for {{ $student?->name ?? 'this student' }}?">
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
