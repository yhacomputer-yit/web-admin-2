@extends('admin.master.master')

@section('title', 'Course Sections')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/student-profile.css') }}" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Course &rarr; Section Linking</h4>
            <div class="text-muted small">
                Choose a course, then tick the time sections it runs. Enrollments and
                attendance can only use sections linked here.
            </div>
        </div>
        <a href="{{ route('admin.section') }}" class="sp-btn-ghost">
            <i class="bx bx-time"></i> Manage Sections
        </a>
    </div>



    <form method="POST" action="{{ route('course.section.sync') }}" id="linkForm">
        @csrf

        <div class="row g-3">
            {{-- course picker --}}
            <div class="col-12 col-lg-4">
                <div class="sp-card">
                    <div class="sp-card-header"><i class="bx bx-book-reader"></i> Courses</div>
                    <div class="sp-card-body p-0">
                        @forelse ($courses as $course)
                            <label class="d-flex align-items-center gap-2 px-3 py-2 border-bottom cs-course-row"
                                style="cursor:pointer; {{ $course->id === $selectedCourseId ? 'background:#fff8f2;' : '' }}">
                                <input type="radio" name="course_id" value="{{ $course->id }}"
                                    class="form-check-input mt-0" @checked($course->id === $selectedCourseId)
                                    onchange="window.location='{{ route('course.section.index') }}?course_id='+this.value">
                                <span class="flex-grow-1">
                                    <span class="d-block fw-semibold" style="font-size:.875rem">{{ $course->name }}</span>
                                    <span class="d-block text-muted" style="font-size:.75rem">
                                        {{ $course->enrollments_count }} enrollment(s)
                                        &middot; {{ $course->sections->count() }} section(s)
                                    </span>
                                </span>
                            </label>
                        @empty
                            <div class="p-4 text-center text-muted small">No courses yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- section checklist --}}
            <div class="col-12 col-lg-8">
                <div class="sp-card">
                    <div class="sp-card-header justify-content-between flex-wrap gap-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-time"></i> Sections
                            @if ($selectedCourseId)
                                @php($selectedCourse = $courses->firstWhere('id', $selectedCourseId))
                                <span class="text-muted fw-normal" style="font-size:.8125rem">
                                    &mdash; {{ $selectedCourse->name ?? '' }}
                                </span>
                            @endif
                        </span>
                        @if ($selectedCourseId)
                            <button type="button" class="btn btn-sm btn-link text-decoration-none px-0"
                                style="color:#ff6c0f" id="toggleAll">
                                <i class="bx bx-check-square me-1"></i>Select all
                            </button>
                        @endif
                    </div>

                    <div class="sp-card-body">
                        @if (! $selectedCourseId)
                            <div class="sp-empty py-4">
                                <div class="sp-empty-icon"><i class="bx bx-book-reader"></i></div>
                                <div class="sp-empty-title">Select a course</div>
                                <p class="sp-empty-text mb-0">Pick a course on the left to manage its sections.</p>
                            </div>
                        @elseif ($sections->isEmpty())
                            <div class="sp-empty py-4">
                                <div class="sp-empty-icon"><i class="bx bx-time"></i></div>
                                <div class="sp-empty-title">No sections exist yet</div>
                                <p class="sp-empty-text mb-0">
                                    Create time sections first, then link them to a course.
                                </p>
                                <a href="{{ route('admin.section') }}" class="sp-btn-primary mt-3">
                                    <i class="bx bx-plus"></i> Manage Sections
                                </a>
                            </div>
                        @else
                            <div class="row g-2">
                                @foreach ($sections as $section)
                                    @php($isLinked = in_array($section->id, $linkedSectionIds, true))
                                    <div class="col-12 col-md-6">
                                        <label class="d-flex align-items-center gap-2 p-2 border rounded"
                                            style="cursor:pointer; border-color:{{ $isLinked ? '#ff6c0f' : '#eceef2' }} !important;
                                                   background:{{ $isLinked ? '#fff8f2' : '#fff' }}">
                                            <input type="checkbox" name="section_ids[]" value="{{ $section->id }}"
                                                class="form-check-input mt-0 cs-section-box" @checked($isLinked)>
                                            <span class="flex-grow-1">
                                                <span class="d-block fw-semibold" style="font-size:.875rem">
                                                    {{ $section->name }}
                                                </span>
                                                @if ($section->start)
                                                    <span class="d-block text-muted" style="font-size:.75rem">
                                                        {{ $section->start }} &rarr; {{ $section->end }}
                                                    </span>
                                                @endif
                                            </span>
                                            @if ($isLinked)
                                                <i class="bx bx-check-circle" style="color:#ff6c0f"></i>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted small">
                                    <span id="linkedCount">{{ count($linkedSectionIds) }}</span>
                                    of {{ $sections->count() }} section(s) linked
                                </div>
                                <button type="submit" class="sp-btn-primary">
                                    <i class="bx bx-save"></i> Save Sections
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const boxes = Array.from(document.querySelectorAll('.cs-section-box'));
            const counter = document.getElementById('linkedCount');
            const toggleAll = document.getElementById('toggleAll');

            const refresh = () => {
                if (counter) counter.textContent = boxes.filter((b) => b.checked).length;
            };

            boxes.forEach((b) => b.addEventListener('change', refresh));

            if (toggleAll) {
                toggleAll.addEventListener('click', function () {
                    const allChecked = boxes.every((b) => b.checked);
                    boxes.forEach((b) => { b.checked = !allChecked; });
                    refresh();
                });
            }

            refresh();
        })();
    </script>
@endpush
