<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamQuestion;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\SubjectDetail;
use App\Support\StoredFile;
use App\Support\TimeOfDay;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * The admin side of the exam schedule: the sittings, their papers, and whether a
 * student can see them.
 *
 * Two things here are load-bearing for the portal rather than incidental:
 *
 *  - the paper is written to the private disk, not the public one. A paper on the
 *    public disk has a URL anyone can guess, which would turn the window the
 *    student portal enforces in StudentExamController into advice;
 *
 *  - `is_published` is the only thing standing between a draft and a student's
 *    exam list, so the create and edit forms both make it a first-class choice
 *    rather than something left at its default.
 *
 * Deleting a sitting deliberately leaves the submitted scripts alone. They are
 * filed against a course and a subject rather than against a sitting, so they
 * outlive any one sitting of that subject and belong to whichever one comes next.
 */
class ExamController extends Controller
{
    /**
     * Every sitting, newest first, with what a student would make of each one.
     *
     * The three counts an admin needs before touching a sitting are all read in
     * one pass each: how many scripts are in, how many could be, and where the
     * paper is. `answers_count` comes off the relation, which joins on the pair
     * because that is what a script is filed against, and the two course counts
     * come off one grouped query rather than one query per row.
     */
    public function index(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;
        $selectedSubjectId = $request->integer('subject_id') ?: null;

        $selectedState = $request->string('is_published')->toString();

        // A state that is not one of the two narrows to nothing rather than
        // falling back to the whole list: a filter that silently does nothing is
        // worse than one that honestly finds nothing. The select's "All" option
        // submits an empty value, which is the only way to ask for no filter.
        $state = $selectedState === '' ? null : $selectedState;

        $exams = ExamQuestion::with(['course:id,name', 'subject:id,name'])
            ->withCount('answers')
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->when($selectedSubjectId, fn ($q) => $q->where('subject_id', $selectedSubjectId))
            ->when($state, fn ($q) => $q->where('is_published', $state))
            // newest day first: an admin is almost always looking at the sittings
            // they have just made or the ones coming up, and both live at the top
            ->orderByDesc('exam_date')
            ->orderByDesc('id')
            ->get();

        $courseIds = $exams->pluck('course_id')->unique();

        return view('admin.exam.index', [
            'courses' => Course::orderBy('name')->get(),
            'exams' => $exams,
            'subjectsByCourse' => $this->subjectsByCourse(),
            'enrolledByCourse' => $this->enrolledByCourse($courseIds),
            'selectedCourseId' => $selectedCourseId,
            'selectedSubjectId' => $selectedSubjectId,
            'selectedState' => $state,
        ]);
    }

    public function createPage(Request $request)
    {
        return view('admin.exam.create', [
            'courses' => Course::orderBy('name')->get(),
            'subjectsByCourse' => $this->subjectsByCourse(),
            // reached from a course's card on the list, so the course is known
            'preselectedCourseId' => $request->integer('course_id') ?: null,
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->validated($request);

        $data['question_file'] = $request->hasFile('question_file')
            ? $this->storePaper($request->file('question_file'))
            : null;

        $exam = ExamQuestion::create($data);

        return redirect()
            ->route('exam.index')
            ->with('success', 'Scheduled ' . $this->describe($exam));
    }

    public function edit($id)
    {
        $exam = ExamQuestion::findOrFail($id);

        // the sitting's own subject is merged back into the course's list, so a
        // row whose subject has since been unlinked can still be opened and
        // corrected rather than 404ing on a subject the select no longer offers
        $subjectsByCourse = $this->subjectsByCourse();
        $subjectsByCourse[$exam->course_id][] = [
            'id' => $exam->subject_id,
            'name' => $exam->subject->name ?? ('Subject #' . $exam->subject_id),
        ];

        return view('admin.exam.edit', [
            'exam' => $exam,
            'courses' => Course::orderBy('name')->get(),
            'subjectsByCourse' => $subjectsByCourse,
        ]);
    }

    public function update(Request $request, $id)
    {
        $exam = ExamQuestion::findOrFail($id);

        $data = $this->validated($request);

        // the paper is optional here, so the window and the publish state can be
        // corrected on their own. A fresh upload replaces the old one and the old
        // file goes with it; the old file is only removed once the new one has
        // been accepted, so a rejected upload cannot leave the sitting with no
        // paper at all.
        if ($request->hasFile('question_file')) {
            $previous = $exam->question_file;

            $exam->fill($data);
            $exam->question_file = $this->storePaper($request->file('question_file'));
            $exam->save();

            StoredFile::delete($previous, ExamQuestion::PAPER_DISK);

            return redirect()
                ->route('exam.index')
                ->with('success', 'Updated ' . $this->describe($exam->fresh()));
        }

        $exam->fill($data)->save();

        return redirect()
            ->route('exam.index')
            ->with('success', 'Updated ' . $this->describe($exam->fresh()));
    }

    public function delete($id)
    {
        $exam = ExamQuestion::findOrFail($id);

        // the paper is stored for this sitting alone, so it goes with the row
        StoredFile::delete($exam->question_file, ExamQuestion::PAPER_DISK);

        $exam->delete();

        return redirect()
            ->route('exam.index')
            ->with('success', 'Deleted the ' . $this->describe($exam) . ' The submitted scripts were kept, because they belong to the subject rather than to this sitting.');
    }

    // ---- helpers ----------------------------------------------------------

    /**
     * Validate the form.
     *
     * Two rules are the model talking rather than the database: the subject has
     * to belong to the course through subject_detail, and the window has to run
     * forwards. The second one matters because both times are plain time-of-day
     * columns read as the sitting's own date, so an end before its start is not
     * an overnight sitting -- it is a sitting that never opens, and the student
     * portal would show it as finished forever.
     */
    private function validated(Request $request): array
    {
        $courseId = (int) $request->input('course_id');
        $start = (string) $request->input('start_time');

        $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
                function ($attribute, $value, $fail) use ($courseId) {
                    $linked = SubjectDetail::where('course_id', $courseId)
                        ->where('subject_id', $value)
                        ->exists();

                    if (! $linked) {
                        $fail('That subject is not linked to the selected course. Link it under Course Sections first.');
                    }
                },
            ],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($start) {
                    if (filled($start) && $value <= $start) {
                        $fail('The end time must be later than the start time — a sitting cannot run backwards.');
                    }
                },
            ],
            'is_published' => ['required', Rule::in(array_keys(ExamQuestion::PUBLISH_STATES))],
            'question_file' => [
                'nullable',
                'file',
                'mimes:' . implode(',', ExamQuestion::PAPER_ACCEPTED),
                'max:' . ExamQuestion::PAPER_MAX_KILOBYTES,
            ],
        ], [
            'course_id.required' => 'Choose a course.',
            'course_id.exists' => 'That course no longer exists.',
            'subject_id.required' => 'Choose a subject.',
            'subject_id.exists' => 'That subject no longer exists.',
            'exam_date.required' => 'Choose the day of the exam.',
            'exam_date.date' => 'The exam date has to be a real date.',
            'start_time.required' => 'Choose a start time.',
            'start_time.date_format' => 'The start time has to be a time, like 09:00.',
            'end_time.required' => 'Choose an end time.',
            'end_time.date_format' => 'The end time has to be a time, like 11:00.',
            'is_published.required' => 'Say whether students can see this exam.',
            'question_file.mimes' => 'The question paper must be a PDF.',
            'question_file.max' => 'The question paper may not be larger than '
                . round(ExamQuestion::PAPER_MAX_KILOBYTES / 1024) . ' MB.',
        ]);

        // only the columns the form owns; the paper is stored by the caller and
        // written as a column of its own rather than validated as an attribute
        return $request->only(['course_id', 'subject_id', 'exam_date', 'start_time', 'end_time', 'is_published']);
    }

    /**
     * Put a question paper on the private disk the paper endpoint reads.
     *
     * The disk is the whole point of the choice: a PDF on the public disk is a
     * PDF at a guessable URL, and the window this feature enforces would be
     * advisory rather than enforced.
     */
    private function storePaper(UploadedFile $file): string
    {
        return StoredFile::store(
            $file,
            ExamQuestion::PAPER_DIRECTORY,
            ExamQuestion::PAPER_ACCEPTED,
            ExamQuestion::PAPER_DISK
        );
    }

    /**
     * `the MICROSOFT EXCEL sitting on 04 Oct 2026, 09:00 - 11:00.` -- what a
     * save did, said the same way after an add and after an edit, and usable
     * inside the delete confirmation.
     */
    private function describe(ExamQuestion $exam): string
    {
        $start = TimeOfDay::format($exam->start_time) ?? '?';
        $end = TimeOfDay::format($exam->end_time) ?? '?';

        return 'the ' . $exam->subject?->name . ' sitting on '
            . ($exam->exam_date?->format('d M Y') ?? 'no date') . ", $start - $end.";
    }

    /**
     * Subjects grouped by course, through subject_detail, in the shape the form's
     * subject select filters with.
     *
     * @return array<int, array<int, array{id: int, name: string}>>
     */
    private function subjectsByCourse(): array
    {
        return SubjectDetail::query()
            ->join('subjects', 'subjects.id', '=', 'subject_detail.subject_id')
            ->orderBy('subjects.name')
            ->get(['subject_detail.course_id', 'subjects.id', 'subjects.name'])
            ->groupBy('course_id')
            ->map(fn ($rows) => $rows
                ->map(fn ($row) => ['id' => (int) $row->id, 'name' => $row->name])
                ->values()
                ->all())
            ->all();
    }

    /**
     * How many students are enrolled in each of the courses on this page.
     *
     * One grouped query for the whole list, because the number is only ever read
     * as "answers so far, out of how many could have answered" and a per-row
     * count would turn a list of thirty sittings into thirty queries.
     *
     * @param  \Illuminate\Support\Collection<int, int>  $courseIds
     * @return array<int, int>
     */
    private function enrolledByCourse($courseIds): array
    {
        if ($courseIds->isEmpty()) {
            return [];
        }

        return StudentEnrollment::query()
            ->whereIn('course_id', $courseIds)
            ->selectRaw('course_id, COUNT(DISTINCT student_id) as total')
            ->groupBy('course_id')
            ->pluck('total', 'course_id')
            ->map(fn ($count) => (int) $count)
            ->all();
    }
}