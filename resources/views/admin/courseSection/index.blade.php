@extends('admin.master.master')

@section('title', 'Course Sections')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-course-sections.css') }}" />
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
        <a href="{{ route('admin.section') }}" class="cs-btn-ghost">
            <i class="bx bx-time"></i> Manage Sections
        </a>
    </div>

    <form method="POST" action="{{ route('course.section.sync') }}" id="linkForm">
        @csrf

        <div class="row g-3">
            {{-- course picker --}}
            <div class="col-12 col-lg-4">
                <div class="cs-panel">
                    <div class="cs-panel-header"><i class="bx bx-book-reader"></i> Courses</div>
                    <div class="cs-panel-body p-0 cs-course-list">
                        @forelse ($courses as $course)
                            <label class="cs-course-row {{ $course->id === $selectedCourseId ? 'cs-course-row--active' : '' }}">
                                <input type="radio" name="course_id" value="{{ $course->id }}"
                                    class="form-check-input mt-0" @checked($course->id === $selectedCourseId)>
                                <span class="flex-grow-1">
                                    <span class="d-block fw-semibold" style="font-size:.875rem">{{ $course->name }}</span>
                                    <span class="d-block text-muted" style="font-size:.75rem">
                                        {{ $course->enrollments_count }} enrollment(s)
                                        &middot; {{ $course->sections->count() }} section(s)
                                    </span>
                                </span>
                            </label>
                        @empty
                            <div class="cs-empty">
                                <div class="cs-empty-icon"><i class="bx bx-book-reader"></i></div>
                                <div class="cs-empty-title">No courses yet</div>
                                <p class="cs-empty-text mb-0">Create a course first.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- section checklist --}}
            <div class="col-12 col-lg-8">
                <div class="cs-panel" id="sectionsPanel">
                    <div class="cs-panel-header justify-content-between flex-wrap gap-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bx bx-time"></i> Sections
                            @if ($selectedCourseId)
                                @php($selectedCourse = $courses->firstWhere('id', $selectedCourseId))
                                <span class="text-muted fw-normal" style="font-size:.8125rem">
                                    &mdash; {{ $selectedCourse->name ?? '' }}
                                </span>
                            @endif
                        </span>
                        @if ($selectedCourseId && $sections->isNotEmpty())
                            <button type="button" class="cs-btn-ghost" id="toggleAll">
                                <i class="bx bx-check-square me-1"></i><span id="toggleAllLabel">Select all</span>
                            </button>
                        @endif
                    </div>

                    <div class="cs-panel-body p-3">
                        <div class="cs-busy"><i class="bx bx-loader-alt bx-spin"></i> Loading sections&hellip;</div>

                        @if (! $selectedCourseId)
                            <div class="cs-empty py-4">
                                <div class="cs-empty-icon"><i class="bx bx-book-reader"></i></div>
                                <div class="cs-empty-title">Select a course</div>
                                <p class="cs-empty-text mb-0">Pick a course on the left to manage its sections.</p>
                            </div>
                        @elseif ($sections->isEmpty())
                            <div class="cs-empty py-4">
                                <div class="cs-empty-icon"><i class="bx bx-time"></i></div>
                                <div class="cs-empty-title">No sections exist yet</div>
                                <p class="cs-empty-text mb-0">
                                    Create time sections first, then link them to a course.
                                </p>
                                <a href="{{ route('admin.section') }}" class="cs-btn-primary mt-3">
                                    <i class="bx bx-plus"></i> Manage Sections
                                </a>
                            </div>
                        @else
                            <div class="row g-2">
                                @foreach ($sections as $section)
                                    @php($isLinked = in_array($section->id, $linkedSectionIds, true))
                                    <div class="col-12 col-md-6">
                                        <label class="cs-card {{ $isLinked ? 'cs-card--linked' : '' }}">
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
                                            <i class="bx bx-check-circle cs-card-check"></i>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted small" aria-live="polite">
                                    <span id="linkedCount">{{ count($linkedSectionIds) }}</span>
                                    of {{ $sections->count() }} section(s) linked
                                </div>
                                <button type="submit" class="cs-btn-primary">
                                    <i class="bx bx-save"></i> Save Sections
                                </button>
                            </div>
                            {{-- <div class="mt-2 text-muted" style="font-size:.75rem">
                                <i class="bx bx-info-circle"></i> Ticks stay on this screen until you press
                                <strong>Save Sections</strong> &mdash; switching course discards them.
                            </div> --}}
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
            const toggleLabel = document.getElementById('toggleAllLabel');
            const toggleIcon = toggleAll ? toggleAll.querySelector('.bx') : null;

            const refresh = () => {
                const checked = boxes.filter((b) => b.checked);
                if (counter) counter.textContent = checked.length;
                if (toggleAll && toggleLabel) {
                    const allChecked = boxes.length > 0 && checked.length === boxes.length;
                    toggleLabel.textContent = allChecked ? 'Clear all' : 'Select all';
                    if (toggleIcon) {
                        toggleIcon.classList.toggle('bx-square', allChecked);
                        toggleIcon.classList.toggle('bx-check-square', !allChecked);
                    }
                }
                boxes.forEach((b) => {
                    const card = b.closest('.cs-card');
                    if (card) card.classList.toggle('cs-card--linked', b.checked);
                });
            };

            boxes.forEach((b) => b.addEventListener('change', refresh));

            if (toggleAll) {
                toggleAll.addEventListener('click', function () {
                    const allChecked = boxes.length > 0 && boxes.every((b) => b.checked);
                    boxes.forEach((b) => { b.checked = !allChecked; });
                    refresh();
                });
            }

            // picking a course reloads the page; show it, and swallow stray double-clicks
            const radios = Array.from(document.querySelectorAll('input[name="course_id"]'));
            radios.forEach((r) => r.addEventListener('change', function () {
                radios.forEach((x) => { x.disabled = true; });
                const panel = document.getElementById('sectionsPanel');
                if (panel) panel.classList.add('is-busy');
                window.location = '{{ route('course.section.index') }}?course_id=' + this.value;
            }));

            refresh();
        })();
    </script>
@endpush
