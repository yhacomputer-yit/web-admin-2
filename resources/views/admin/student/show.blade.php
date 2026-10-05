@extends('admin.master.master')

@section('content')
<div class="container-fluid">



    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex gap-2">
            <a href="{{ route('student.resetPassword', ['id' => $student->id]) }}" class="btn btn-warning"
                data-confirm="Generate a new password for {{ $student->name }}? The current one stops working."
                data-confirm-tone="info" data-confirm-label="Generate">
                <i class="bx bx-key"></i> Reset Password
            </a>
            <a href="{{ route('student.edit', ['id' => $student->id]) }}" class="btn"
                style="background-color: #ff6c0f; color: white;">
                <i class="bx bx-edit-alt"></i> Edit
            </a>
        </div>
    </div>

    {{-- Header card  --}}
    <div class="card mb-3 border-warning shadow">
        <div class="card-body">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <img src="{{ $student->image ? Storage::url($student->image) : '/image/no-image.jpg' }}"
                        width="110" height="110" alt=""
                        class="rounded-circle object-fit-cover border">
                </div>
                <div class="col">
                    <h3 class="h4 mb-1">{{ $student->name }}</h3>
                    <div class="text-muted mb-2">
                        @if ($student->nickname) &ldquo;{{ $student->nickname }}&rdquo; &middot; @endif
                        ID {{ $student->id }}
                    </div>
                    <span class="badge {{ $student->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                        {{ $student->enrollments->count() }} course(s)
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Credentials  --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100 border-warning shadow-sm">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-key me-1"></i> Login Credentials</span>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Username</dt>
                        <dd class="col-7"><code>{{ $student->username ?? '—' }}</code></dd>
                        <dt class="col-5">Password</dt>
                        <dd class="col-7"><code>••••••••</code></dd>
                    </dl>
                    <p class="text-muted small mb-0 mt-3">
                        Passwords are stored as one-way hashes and cannot be displayed.
                        Use <strong>Reset Password</strong> to issue a new one.
                    </p>
                </div>
            </div>
        </div>

        {{-- Personal  --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100 border-warning shadow-sm">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-user me-1"></i> Personal</span>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Name</dt>
                        <dd class="col-7">{{ $student->name ?? '—' }}</dd>
                        <dt class="col-5">Father</dt>
                        <dd class="col-7">{{ $student->father_name ?? '—' }}</dd>
                        <dt class="col-5">Mother</dt>
                        <dd class="col-7">{{ $student->mother_name ?? '—' }}</dd>
                        <dt class="col-5">Gender</dt>
                        <dd class="col-7">{{ ucfirst($student->gender ?? '') ?: '—' }}</dd>
                        <dt class="col-5">Date of Birth</dt>
                        <dd class="col-7">{{ $student->date_of_birth?->format('Y-m-d') ?? '—' }}</dd>
                        <dt class="col-5">NRC No</dt>
                        <dd class="col-7">{{ $student->nrc ?? '—' }}</dd>
                        <dt class="col-5">Religion</dt>
                        <dd class="col-7">{{ $student->religious_status ?? '—' }}</dd>
                        <dt class="col-5">Race</dt>
                        <dd class="col-7">{{ $student->race ?? '—' }}</dd>
                        <dt class="col-5">Native Town</dt>
                        <dd class="col-7">{{ $student->native_town ?? '—' }}</dd>
                        <dt class="col-5">Education</dt>
                        <dd class="col-7">{{ $student->education ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Contact  --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100 border-warning shadow-sm">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-phone me-1"></i> Contact</span>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Phone</dt>
                        <dd class="col-7">{{ $student->phone ?? '—' }}</dd>
                        <dt class="col-5">Viber</dt>
                        <dd class="col-7">{{ $student->viber_phone ?? '—' }}</dd>
                        <dt class="col-5">Email</dt>
                        <dd class="col-7 text-break">{{ $student->email ?? '—' }}</dd>
                        <dt class="col-5">Facebook</dt>
                        <dd class="col-7 text-break">{{ $student->facebook_acc_name ?? '—' }}</dd>
                        <dt class="col-5">Telegram</dt>
                        <dd class="col-7 text-break">{{ $student->telegram_username ?? '—' }}</dd>
                        <dt class="col-5">Address</dt>
                        <dd class="col-7">{{ $student->address ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Courses  --}}
        <div class="col-12">
            <div class="card border-warning shadow-sm">
                <div class="card-header border-warning d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bx bx-book-add me-1"></i> Course Enrollment</span>
                    <a href="{{ route('enrollment.createPage') }}" class="btn btn-sm"
                        style="background-color: #ff6c0f; color: white;">
                        <i class="bx bx-plus"></i> Add Enrollment
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Section</th>
                                    <th>Enroll Date</th>
                                    <th style="width: 130px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($student->enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->course?->name ?? '—' }}</td>
                                        <td>{{ $enrollment->section?->name ?? '—' }}</td>
                                        <td>{{ $enrollment->enroll_date?->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('enrollment.edit', ['id' => $enrollment->id]) }}"
                                                class="text-decoration-none me-2">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </a>
                                            <a href="{{ route('enrollment.delete', ['id' => $enrollment->id]) }}"
                                                class="text-decoration-none text-danger"
                                                data-confirm="Remove this enrollment?">
                                                <i class="bx bx-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Not enrolled in any course yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back button at bottom --}}
    <a href="{{ route('admin.student') }}" class="btn btn-back">
        <i class="bx bx-left-arrow-alt"></i> Back to Students
    </a>
</div>
@endsection
