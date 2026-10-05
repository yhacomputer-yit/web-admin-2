@extends('admin.master.master')

@section('title', 'Marks')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Marks</h4>
            <div class="text-muted small">
                {{ $results->count() }} mark(s) shown{{ $ungradedOnly ? ', ungraded only' : '' }},
                newest first. A mark without a band is waiting to be graded.
            </div>
        </div>
        <a href="{{ route('gradingResult.createPage') }}" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Add Mark
        </a>
    </div>


    <form method="GET" action="{{ route('gradingResult.index') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="q" class="form-label small fw-semibold mb-1">Student</label>
                    <input type="search" name="q" id="q" value="{{ $term }}"
                        class="form-control form-control-sm" placeholder="Name or username">
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
                    <label for="grade_id" class="form-label small fw-semibold mb-1">Band</label>
                    <select name="grade_id" id="grade_id" class="form-select form-select-sm">
                        <option value="">Any band</option>
                        @foreach ($gradings as $grading)
                            <option value="{{ $grading->id }}" @selected($selectedGradeId === $grading->id)>
                                {{ $grading->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-8 d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ungraded" value="1" id="ungraded"
                            @checked($ungradedOnly)>
                        <label class="form-check-label small" for="ungraded">Only ungraded marks</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 d-flex gap-2 justify-content-md-end">
                    <button type="submit" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                        <i class="bx bx-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('gradingResult.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        @if ($results->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bx bx-note" style="font-size: 2rem; color: #cbd5e1;"></i>
                <div class="fw-semibold mt-2">
                    @if ($term !== '' || $selectedCourseId || $selectedSubjectId || $selectedGradeId || $ungradedOnly)
                        No marks match these filters
                    @else
                        No marks recorded yet
                    @endif
                </div>
                <div class="text-muted small mb-3">
                    Record a student's score on a subject, then put it into a band when it is graded.
                </div>
                <a href="{{ route('gradingResult.createPage') }}" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-plus me-1"></i> Add Mark
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <caption class="visually-hidden">Recorded marks, most recent first</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Mark</th>
                            <th scope="col">Band</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $result->student?->name ?? 'Student #' . $result->student_id }}</div>
                                    <div class="small text-muted">
                                        {{ $result->student?->username ? '@' . $result->student->username : '' }}
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $result->subject?->name ?? 'Subject #' . $result->subject_id }}</div>
                                    <div class="small text-muted">
                                        {{ $result->course?->name ?? 'Course #' . $result->course_id }}
                                    </div>
                                </td>
                                <td class="fw-semibold">{{ $result->scoreLabel() }}</td>
                                <td>
                                    @if ($result->isGraded())
                                        <span class="badge bg-success">{{ $result->grade?->name ?? 'Band #' . $result->grade_id }}</span>
                                    @else
                                        <span class="badge text-bg-warning">Not graded</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $result->date?->format('j M Y') ?? '—' }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('gradingResult.edit', ['id' => $result->id]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit this mark">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="{{ route('gradingResult.delete', ['id' => $result->id]) }}"
                                        class="btn btn-sm btn-outline-danger" title="Delete this mark"
                                        data-confirm="Delete {{ $result->student?->name ?? 'this student' }}&#39;s mark for {{ $result->subject?->name ?? 'this subject' }}?"
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
            // the subject options follow the chosen course, filtered client side
            // from the map the controller sent down
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
