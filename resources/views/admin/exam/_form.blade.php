@php
    use App\Models\ExamQuestion;
    use App\Support\StoredFile;
    use App\Support\TimeOfDay;
    use Illuminate\Support\Facades\Storage;

    // shared by create and edit; $exam is null on create
    $exam = $exam ?? null;
    $isEdit = $exam !== null;

    $currentCourseId = (int) old('course_id', $preselectedCourseId ?? $exam->course_id ?? 0);
    $currentSubjectId = (int) old('subject_id', $exam->subject_id ?? 0);
    $currentDate = old('exam_date', $exam?->exam_date?->toDateString() ?? '');
    // the inputs need a 24-hour value; only the hints below are shown to a human
    $currentStart = old('start_time', TimeOfDay::value($exam?->start_time) ?? '09:00');
    $currentEnd = old('end_time', TimeOfDay::value($exam?->end_time) ?? '11:00');
    $currentState = (string) old('is_published', $exam?->is_published ?? ExamQuestion::PUBLISHED);

    // the paper the row points at right now, so a replace is never a surprise and
    // a file that has gone missing is said rather than silently replaced
    $onDisk = $isEdit && filled($exam->question_file)
        && Storage::disk(ExamQuestion::PAPER_DISK)->exists($exam->question_file);

    $paperLabel = $isEdit ? StoredFile::label($exam->question_file) : null;

    // what the upload will do to the students' portal, said before it is saved:
    // the window is the whole contract between the two pages
    $closesLabel = TimeOfDay::format(
        $exam?->submissionClosesAt()
            ?? \Illuminate\Support\Carbon::parse($currentDate . ' ' . $currentStart)->addMinutes(ExamQuestion::SUBMIT_WINDOW_MINUTES)
    );
@endphp

<style>
    /* scoped to this page: the two states the paper can be in, drawn as a strip
       above the input rather than left to the browser's own file field */
    .ex-paper-now {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .5rem;
        padding: .5rem .65rem;
        border-radius: 8px;
        margin-bottom: .6rem;
    }
</style>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-link-alt me-1"></i> Course &amp; Subject</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 form-group">
                        <label for="course_id" class="form-label h6 my-2">Course</label>
                        <select name="course_id" id="course_id" required
                            class="form-select form-select-sm @error('course_id') is-invalid @enderror">
                            <option value="">Choose a course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected($currentCourseId === $course->id)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2 form-group">
                        <label for="subject_id" class="form-label h6 my-2">Subject</label>
                        <select name="subject_id" id="subject_id" required
                            class="form-select form-select-sm @error('subject_id') is-invalid @enderror">
                            <option value="">Choose a subject</option>
                            @foreach (($currentCourseId ? ($subjectsByCourse[$currentCourseId] ?? []) : []) as $subject)
                                <option value="{{ $subject['id'] }}" @selected($currentSubjectId === $subject['id'])>
                                    {{ $subject['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- <div class="form-text">
                            Only subjects linked to the course above are listed. Changing the
                            course reloads this list; nothing is written until you save.
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header border-warning">
                    <span class="fw-semibold"><i class="bx bx-time me-1"></i> Window &amp; visibility</span>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 form-group">
                            <label for="exam_date" class="form-label h6 my-2">Exam date</label>
                            <input type="date" name="exam_date" id="exam_date" required
                                value="{{ $currentDate }}"
                                class="form-control form-control-sm @error('exam_date') is-invalid @enderror">
                            @error('exam_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="is_published" class="form-label h6 my-2">Visibility</label>
                            <select name="is_published" id="is_published" required
                                class="form-select form-select-sm @error('is_published') is-invalid @enderror">
                                @foreach (ExamQuestion::PUBLISH_STATES as $value => $label)
                                    <option value="{{ $value }}" @selected($currentState === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('is_published')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="start_time" class="form-label h6 my-2">Start time</label>
                            <input type="time" name="start_time" id="start_time" required step="300"
                                value="{{ $currentStart }}"
                                class="form-control form-control-sm @error('start_time') is-invalid @enderror">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="end_time" class="form-label h6 my-2">End time</label>
                            <input type="time" name="end_time" id="end_time" required step="300"
                                value="{{ $currentEnd }}"
                                class="form-control form-control-sm @error('end_time') is-invalid @enderror">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-text mb-3" id="window-hint">
                        Students open the paper at the start time, and uploads close{" "}
                        {{ ExamQuestion::SUBMIT_WINDOW_MINUTES }} minutes later
                        <span id="closes-hint">({{ $closesLabel ?? 'after the start time' }})</span>.
                        The paper itself stays readable until {{ TimeOfDay::format($exam?->end_time) ?? 'the end time' }},
                        after which the link stops working for everyone. Move the start time and
                        this deadline moves with it; nothing is written until you save.
                    </div> --}}

                    <div class="form-group mb-0">
                        <label for="question_file" class="form-label h6 my-2">Question paper</label>

                        @if ($isEdit && filled($exam->question_file))
                            <div class="ex-paper-now"
                                style="background: {{ $onDisk ? '#fff8f2' : '#fef2f2' }};
                                       border: 1px solid {{ $onDisk ? '#ffe0c2' : '#fecaca' }};">
                                <i class="bx bx-file"></i>
                                <span class="text-truncate" style="max-width: 60%;" title="{{ $paperLabel }}">
                                    {{ $paperLabel }}
                                </span>
                                @unless ($onDisk)
                                    <span class="badge text-bg-danger">missing from storage</span>
                                @endunless
                            </div>
                        @endif

                        <input type="file" name="question_file" id="question_file" accept=".pdf"
                            class="form-control form-control-sm @error('question_file') is-invalid @enderror">
                        @error('question_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- <div class="form-text">
                            PDF only, up to {{ round(ExamQuestion::PAPER_MAX_KILOBYTES / 1024) }} MB.
                            @if ($isEdit)
                                Leave this empty to keep the paper already on file &mdash; picking
                                a new one replaces it on save.
                            @else
                                Optional &mdash; the sitting can be saved now and the paper added
                                later.
                            @endif
                        </div> --}}
                        {{-- <div class="form-text">
                            The paper is stored privately and streamed to students only while
                            the window is open, so the link stops working the moment the exam
                            ends &mdash; uploading a new paper does not reopen a finished exam.
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <a href="{{ route('exam.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> Back
        </a>
        <button type="submit" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-down-arrow-alt me-1"></i> {{ $isEdit ? 'Save Changes' : 'Save' }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        (function () {
            const map = @json($subjectsByCourse);
            const courseSelect = document.getElementById('course_id');
            const subjectSelect = document.getElementById('subject_id');

            if (courseSelect && subjectSelect) {
                courseSelect.addEventListener('change', () => {
                    subjectSelect.innerHTML = '<option value="">Choose a subject</option>';
                    (map[courseSelect.value] || []).forEach((subject) => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectSelect.appendChild(option);
                    });
                });
            }

            /* The upload deadline is counted forward from the start time, so it is said
               here rather than left to the admin to work out from the end time. */
            const dateInput = document.getElementById('exam_date');
            const startInput = document.getElementById('start_time');
            const closesHint = document.getElementById('closes-hint');
            const windowMinutes = @json(ExamQuestion::SUBMIT_WINDOW_MINUTES);

            if (dateInput && startInput && closesHint) {
                const syncCloses = () => {
                    if (!dateInput.value || !startInput.value) {
                        closesHint.textContent = '';
                        return;
                    }

                    const [hours, minutes] = startInput.value.split(':').map(Number);
                    if (Number.isNaN(hours) || Number.isNaN(minutes)) return;

                    const closes = new Date(`${dateInput.value}T00:00:00`);
                    closes.setHours(hours, minutes + windowMinutes, 0, 0);

                    closesHint.textContent = '(' + closes.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                    }) + ')';
                };

                dateInput.addEventListener('change', syncCloses);
                startInput.addEventListener('change', syncCloses);
            }
        })();
    </script>
@endpush
