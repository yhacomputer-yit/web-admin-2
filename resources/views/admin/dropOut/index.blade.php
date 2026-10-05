@extends('admin.master.master')

@section('title', 'Drop Outs')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Drop Outs</h4>
            <div class="text-muted small">
                Students who left a class before it finished, newest first. Saving one closes
                that course's enrollment for the student.
            </div>
        </div>
        <a href="{{ route('dropOut.createPage', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}"
            class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Record Drop Out
        </a>
    </div>

    @include('admin.partials.feedback')

    {{-- filters: the course narrows through the student's own enrollments --}}
    <form method="GET" action="{{ route('dropOut.index') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="q" class="form-label small fw-semibold mb-1">Student</label>
                    <input type="search" name="q" id="q" value="{{ $term }}"
                        class="form-control form-control-sm" placeholder="Name, username, phone or NRC">
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
                <div class="col-12 col-md-3">
                    <label for="from" class="form-label small fw-semibold mb-1">From</label>
                    <input type="date" name="from" id="from" value="{{ $from }}" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                        <i class="bx bx-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('dropOut.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        @if ($dropOuts->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bx bx-user-minus" style="font-size: 2rem; color: #cbd5e1;"></i>
                <div class="fw-semibold mt-2">
                    @if ($term !== '' || $selectedCourseId || $from !== '')
                        No drop-outs match these filters
                    @else
                        No drop-outs recorded yet
                    @endif
                </div>
                <div class="text-muted small mb-3">
                    Record a student leaving a class so the date and the reason are on file, and
                    the class list stops counting them.
                </div>
                <a href="{{ route('dropOut.createPage', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}"
                    class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-plus me-1"></i> Record Drop Out
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <caption class="visually-hidden">Recorded drop-outs, most recent first</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Course</th>
                            <th scope="col">Dropped out</th>
                            <th scope="col">Remark</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dropOuts as $dropOut)
                            @php($student = $dropOut->student)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $student?->name ?? 'Student #' . $dropOut->student_id }}</div>
                                    <div class="small text-muted">
                                        {{ $student?->username ? '@' . $student->username : '—' }}
                                        @if ($student?->status)
                                            &middot; {{ ucfirst($student->status) }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    {{ $dropOut->course?->name ?? 'Course #' . $dropOut->course_id }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $dropOut->drop_out_date?->format('j M Y') ?? '—' }}
                                </td>
                                <td style="max-width: 22rem;">
                                    @if (filled($dropOut->remark))
                                        <span class="small">{{ $dropOut->remark }}</span>
                                    @else
                                        <span class="small text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('dropOut.edit', ['id' => $dropOut->id]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit this drop-out">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="{{ route('dropOut.delete', ['id' => $dropOut->id]) }}"
                                        class="btn btn-sm btn-outline-danger" title="Delete this drop-out"
                                        onclick="return confirm('Delete the drop-out for {{ $student?->name ?? 'this student' }}?')">
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