<?php

namespace App\Http\Controllers;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\Student;
use App\Support\StoredFile;
use App\Support\TimeOfDay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * The student exam system: the schedule, the sitting, and the script.
 *
 * Three gates decide everything a student can do with a sitting, and they are
 * deliberately all enforced here rather than in the page:
 *
 *  1. publication -- only a `published` sitting is ever loaded, so a draft (or
 *     an `unpublish`) cannot be seen, listed, or probed by its id;
 *  2. enrollment -- only a course the student is enrolled in is reachable, the
 *     same gate the course materials use;
 *  3. the window -- the paper and the submission both stop at `exam_date` +
 *     `end_time`, checked on every request rather than once at page load.
 *
 * The window is the reason the paper is streamed instead of linked. A URL for a
 * PDF is a URL a student can keep, so the paper lives on the private disk and
 * the only way to read it is this controller, which re-checks the clock. A tab
 * left open past the end time therefore stops working instead of staying a
 * readable copy.
 */
class StudentExamController extends Controller
{
    /**
     * The exam schedule for the courses this student is enrolled in.
     *
     * Scoped to published sittings only: a draft or a closed sitting is an
     * admin's working state and a student must never see one.
     *
     * The sittings come back grouped by subject, because that is how a student
     * holds them in their head: "the MICROSOFT EXCEL exam" rather than a flat
     * list of eleven cards. Answers are loaded once for the whole page rather
     * than per sitting, because a script is per course + subject and a student
     * usually has several sittings of the same subject.
     *
     * The filter narrows that grouping but never widens it: the course and
     * subject lists are built from the sittings the student may see, so there
     * is nothing to select that would then come back empty for a reason the
     * student cannot act on.
     */
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();

        $courseId = $request->integer('course_id') ?: null;
        $subjectId = $request->integer('subject_id') ?: null;

        // one read of everything the student may see, then filtered in memory:
        // the schedule is short, and the same collection has to answer "what
        // may I filter by" as well as "what is in the list", which cannot both
        // be true of two separate queries without risking the two disagreeing
        $all = $this->portalSittings($student)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->orderBy('id')
            ->get();

        $sittings = $all
            ->filter(fn (ExamQuestion $sitting) => ! $courseId || $sitting->course_id === $courseId)
            ->filter(fn (ExamQuestion $sitting) => ! $subjectId || $sitting->subject_id === $subjectId)
            ->values();

        $answers = $this->answersByPair($student, $sittings->pluck('course_id')->unique());

        $now = now();
        $rows = $sittings
            ->map(fn (ExamQuestion $sitting) => $this->examCard($sitting, $answers->get($this->pairKey($sitting)), $now))
            ->values();

        return Inertia::render('StudentExams', [
            'groups' => $this->groupBySubject($rows),
            'filters' => [
                'course_id' => $courseId,
                'subject_id' => $subjectId,
            ],
            // the options are the sittings this student can actually see, so a
            // filter can only ever narrow the page it is filtering
            'options' => $this->filterOptions($all, $courseId),
            'summary' => [
                'total' => $rows->count(),
                ExamQuestion::UPCOMING => $rows->where('status', ExamQuestion::UPCOMING)->count(),
                ExamQuestion::ONGOING => $rows->where('status', ExamQuestion::ONGOING)->count(),
                ExamQuestion::FINISHED => $rows->where('status', ExamQuestion::FINISHED)->count(),
                'submitted' => $rows->where('submitted', true)->count(),
            ],
        ]);
    }

    /**
     * The sitting itself: the two info cards, the paper and the submit panel.
     *
     * Reachable while the upload is open: the window itself, plus the
     * grace period after it, because a student whose time ran out
     * mid-sitting still has to reach the page to hand their script in.
     * Before the window opens there is nothing to read, and once the
     * grace period is gone a script would not be marked, so both are
     * sent back to the list with the reason rather than rendered as a
     * page that cannot do its job.
     *
     * The paper URL handed to the page is the streaming endpoint rather
     * than a file path, so the time gate keeps applying after the page
     * has loaded.
     */
    public function show(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();
        $exam = $this->portalExam($student, (int) $examId);

        $now = now();
        $status = $exam->scheduleStatus($now);

        if (! $exam->isSubmitOpen($now)) {
            return redirect()
                ->route('student.exam')
                ->with('error', $this->closedMessage($exam, $status));
        }

        $answer = ExamAnswer::forPair($exam->course_id, $exam->subject_id, $student->id)->first();

        return Inertia::render('StudentExamTake', [
            'exam' => $this->examRow($exam, $answer, $now),
            'server_time' => $now->toIso8601String(),
            // one place decides what a script may be, so the input's accept
            // filter and the validator cannot drift apart
            'accept' => '.' . implode(',.', ExamAnswer::ACCEPTED),
            'max_kb' => ExamAnswer::MAX_KILOBYTES,
        ]);
    }

    /**
     * Stream the paper to the viewer.
     *
     * The endpoint the viewer page points at, and the only way to the file. Every
     * gate is re-applied here because this request is independent of the page
     * that made it: a student who bookmarked the URL, or who had the tab open
     * when the sitting's own time ran out, gets a 403 rather than the paper.
     *
     * The gate is the sitting's session rather than its window. A student is
     * given the paper for as long as they are given the upload, and after that
     * the paper is gone even though the exam is still listed and still open to
     * enter -- otherwise the countdown that closed the viewer on the page would
     * be a courtesy the URL did not keep.
     *
     * The response is not cacheable, for the same reason -- a stored copy in a
     * shared cache or in the browser's back/forward store would outlive the
     * session the gate is protecting.
     */
    public function paper(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();
        $exam = $this->portalExam($student, (int) $examId);

        if (! $exam->isPaperOpen()) {
            abort(403, 'This exam paper is only available while the exam is running.');
        }

        $path = $exam->paperPath();

        abort_if($path === null || ! is_file($path), 404, 'This exam has no paper uploaded.');

        $response = response()->file($path, [
            // inline so the browser's own viewer renders it in place, which is
            // what lets the page close it by unmounting the frame
            'Content-Disposition' => 'inline; filename="' . addslashes($exam->paperLabel() ?? 'exam-paper.pdf') . '"',
        ]);

        // set after the response is built rather than in the constructor's header
        // array: a file response marks itself public while it is constructed, and
        // a public paper is exactly what the gate is here to prevent. Not cacheable
        // for the same reason -- a copy the browser or a shared proxy kept would
        // outlive the window it is only readable inside of.
        $response->headers->set('Cache-Control', 'private, no-store, no-cache, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }

    /**
     * Store a student's answer script.
     *
     * Two deadlines are checked here and not left to the page, because the page is
     * only a page: a student who opened it before the upload closed and pressed
     * submit after it shut must still be refused, and so must one who submits
     * after the window itself has closed.
     *
     * Submitting again replaces the script rather than adding one, because the
     * unique index allows a single script per course + subject, and a student who
     * uploads the wrong file needs to be able to fix it.
     */
    public function submit(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();
        $exam = $this->portalExam($student, (int) $examId);

        $now = now();

        if (! $exam->isSubmitOpen($now)) {
            return redirect()
                ->route('student.exam')
                ->with('error', $this->submitClosedMessage($exam, $now));
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:' . implode(',', ExamAnswer::ACCEPTED), 'max:' . ExamAnswer::MAX_KILOBYTES],
        ], [
            'file.required' => 'Choose a file to submit.',
            'file.mimes' => 'Your answer must be a PDF, JPG, PNG or WEBP file.',
            'file.max' => 'Your answer may not be larger than ' . round(ExamAnswer::MAX_KILOBYTES / 1024) . ' MB.',
        ]);

        $answer = ExamAnswer::forPair($exam->course_id, $exam->subject_id, $student->id)->first();

        // the replaced script goes before the new one is stored, so a rejected
        // upload cannot leave the student with no script at all
        StoredFile::delete($answer?->answer_file);

        $path = StoredFile::store($request->file('file'), 'exam/answers', ExamAnswer::ACCEPTED);

        ExamAnswer::updateOrCreate(
            [
                'course_id' => $exam->course_id,
                'subject_id' => $exam->subject_id,
                'student_id' => $student->id,
            ],
            [
                'answer_file' => $path,
                'submitted_date' => now(),
            ]
        );

        return redirect()
            ->route('student.exam')
            ->with('success', 'Your answer for ' . ($exam->subject?->name ?? 'this exam') . ' was submitted.');
    }

    /**
     * Every published sitting of a course the student is enrolled in.
     *
     * Enrollment is the only gate beyond publication, which is the same gate the
     * course materials use: the portal has no other way of knowing which of a
     * course's subjects a given student is actually taking.
     */
    private function portalSittings(Student $student)
    {
        return ExamQuestion::published()
            ->with(['course:id,name', 'subject:id,name'])
            ->whereIn('course_id', $student->enrollments()->distinct()->pluck('course_id')->all());
    }

    /**
     * The student's own scripts, keyed by course + subject.
     *
     * One query for the whole page. A script is per course + subject rather than
     * per sitting -- the column that would tie it to a sitting does not exist --
     * so the key is exactly those three ids.
     *
     * @param  \Illuminate\Support\Collection<int, mixed>  $courseIds
     * @return \Illuminate\Support\Collection<string, ExamAnswer>
     */
    private function answersByPair(Student $student, $courseIds)
    {
        if ($courseIds->isEmpty()) {
            return collect();
        }

        return ExamAnswer::where('student_id', $student->id)
            ->whereIn('course_id', $courseIds)
            ->get()
            ->keyBy(fn (ExamAnswer $answer) => $this->pairKey($answer));
    }

    /**
     * One sitting a student is allowed to reach: published, and belonging to a
     * course they are enrolled in. Anything else is a 404 rather than a
     * redirect, so a draft sitting cannot be probed by its id.
     */
    private function portalExam(Student $student, int $examId): ExamQuestion
    {
        $exam = $this->portalSittings($student)->find($examId);

        abort_if($exam === null, 404);

        return $exam;
    }

    /**
     * Subject -> sittings, in the order the schedule reads.
     *
     * One level, not course -> subject: the subject is what a student is examined
     * on, so it is the heading the cards sit under. The sittings arrive ordered by
     * date, so the groups appear in the order their first exam falls and the
     * sittings inside each group stay chronological -- both without a second sort.
     *
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    private function groupBySubject($rows): array
    {
        return $rows
            ->groupBy('subject_id')
            ->map(fn ($subjectRows, $subjectId) => [
                'id' => (int) $subjectId,
                'name' => $subjectRows->first()['subject_name'] ?? ('Subject #' . $subjectId),
                'total' => $subjectRows->count(),
                'answered' => $subjectRows->where('submitted', true)->count(),
                'exams' => $subjectRows->values()->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * The courses and subjects the filter offers.
     *
     * Narrowed by the course already chosen, so picking a course replaces the
     * subject list with that course's own subjects rather than leaving a list
     * that can select a combination with nothing behind it.
     *
     * Both lists come from everything the student may see, not from the list on
     * screen -- otherwise choosing a course would empty the subject list and
     * leave no way back.
     *
     * @param  \Illuminate\Support\Collection<int, ExamQuestion>  $visible  every sitting the student may see
     * @return array{courses: array<int, array{id:int, name:?string}>, subjects: array<int, array{id:int, name:?string}>}
     */
    private function filterOptions($visible, ?int $courseId): array
    {
        return [
            'courses' => $this->namedOptions($visible, 'course_id', 'course_name'),
            'subjects' => $this->namedOptions(
                $courseId ? $visible->where('course_id', $courseId) : $visible,
                'subject_id',
                'subject_name'
            ),
        ];
    }

    /**
     * Distinct (id, name) pairs out of the loaded sittings, ready for a select.
     *
     * @param  \Illuminate\Support\Collection<int, ExamQuestion>  $sittings
     * @return array<int, array{id:int, name:?string}>
     */
    private function namedOptions($sittings, string $idKey, string $nameKey): array
    {
        return $sittings
            ->map(fn (ExamQuestion $sitting) => ['id' => (int) $sitting->{$idKey}, 'name' => $sitting->{$nameKey}?->name])
            ->filter(fn (array $option) => filled($option['name']))
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->all();
    }

    /**
     * One sitting as a card on the exam list.
     *
     * Deliberately the bare sitting: a card carries its date, its subject, its
     * course, its window and whether the student has answered -- and nothing
     * else. What the student uploaded, and when, is their own business and is
     * not sent here at all: `exam_answers` details belong to the sitting page,
     * which is the one place a student goes to look at their own script.
     *
     * The status and `can_open` come from the server rather than the page, since
     * "is this still ahead of us" depends on the clock, and a page working it out
     * from a date string would disagree with the database by however long the tab
     * had been open.
     *
     * @return array<string, mixed>
     */
    private function examCard(ExamQuestion $sitting, ?ExamAnswer $answer, Carbon $now): array
    {
        $status = $sitting->scheduleStatus($now);
        $start = TimeOfDay::format($sitting->start_time);
        $end = TimeOfDay::format($sitting->end_time);

        return [
            'id' => $sitting->id,
            'course_id' => $sitting->course_id,
            'course_name' => $sitting->course?->name,
            'subject_id' => $sitting->subject_id,
            'subject_name' => $sitting->subject?->name ?? ('Subject #' . $sitting->subject_id),
            'date' => $sitting->exam_date?->toDateString(),
            'start_time' => $start,
            'end_time' => $end,
            'time_label' => $start && $end ? $start . ' - ' . $end : ($start ?? $end),
            'status' => $status,
            'status_label' => ExamQuestion::SCHEDULE_STATUSES[$status],
            // only while the upload is open -- the window itself plus
            // the grace period after it -- so the list never offers a
            // card the server is going to refuse
            'can_open' => $sitting->isSubmitOpen($now),
            'submitted' => (bool) $answer?->isSubmitted(),
        ];
    }

    /**
     * The sitting itself: a card plus what only the sitting page needs.
     *
     * Three absolute timestamps are what the page is built on:
     * `ends_at` is when the questions go away, `submit_closes_at` is
     * when the grace period ends, and `server_now` is this server's
     * clock at the moment the page was rendered. The browser subtracts
     * its own clock from that pair, so a student whose machine is
     * minutes out still gets a countdown that agrees with the database.
     *
     * @return array<string, mixed>
     */
    private function examRow(ExamQuestion $sitting, ?ExamAnswer $answer, Carbon $now): array
    {
        $status = $sitting->scheduleStatus($now);
        $hasPaper = $sitting->hasPaper();

        return array_merge($this->examCard($sitting, $answer, $now), [
            'starts_at' => $sitting->startsAt()?->toIso8601String(),
            'ends_at' => $sitting->endsAt()?->toIso8601String(),
            'seconds_remaining' => $sitting->secondsRemaining($now),
            // the grace period: the questions are gone by now, but a
            // script can still be handed in until this instant, so
            // what the page shows and what the submit endpoint
            // accepts cannot disagree
            'submit_open' => $sitting->isSubmitOpen($now),
            'submit_closes_at' => $sitting->submissionClosesAt()?->toIso8601String(),
            'submit_seconds_remaining' => $sitting->secondsUntilSubmitCloses($now),
            'has_paper' => $hasPaper,
            // the streaming endpoint, which re-checks the sitting's own
            // time on every hit; null whenever there is no paper or the
            // exam itself has ended -- the grace period is for handing
            // the script in, not for reading the paper again -- so the
            // viewer is never pointed at a request that would 403
            'paper_url' => $hasPaper && $sitting->isPaperOpen($now)
                ? route('student.exam.paper', ['examId' => $sitting->id])
                : null,
            // the moment the script was accepted, and only that: the page says
            // when the answer is in, not what it is called
            'submitted_label' => $answer?->submitted_date?->format('d M Y, H:i'),
        ]);
    }

    /**
     * What to tell a student who reached a sitting outside its window.
     *
     * The same two sentences the list raises when a closed card is pressed, so a
     * student who walks in through the URL hears what one who clicked a card
     * would have been told. Both name the window they are waiting on, because
     * "not yet" without a time leaves nothing to act on.
     */
    private function closedMessage(ExamQuestion $exam, string $status): string
    {
        if ($status === ExamQuestion::UPCOMING) {
            return 'Exam time has not started yet. It opens at '
                . (TimeOfDay::format($exam->start_time) ?? 'the scheduled time') . '.';
        }

        return 'Exam time is over. It closed at '
            . (TimeOfDay::format($exam->end_time) ?? 'the scheduled time')
            . ', and submissions stopped at '
            . (TimeOfDay::format($exam->submissionClosesAt()) ?? 'the same time') . '.';
    }

    /**
     * What to tell a student whose upload arrived too late.
     *
     * The window is the whole contract: the paper, the countdown and
     * the upload stop together, so a late script is always a closed
     * window rather than a closed upload, and the message names the
     * end of it, because "not accepted" without a time leaves
     * nothing to act on.
     */
    private function submitClosedMessage(ExamQuestion $exam, Carbon $now): string
    {
        return $this->closedMessage($exam, $exam->scheduleStatus($now));
    }

    /**
     * The pair key a script is filed under: course + subject.
     *
     * `exam_answers` has no exam_question_id, so a script belongs to a course and
     * a subject rather than to a sitting, and this is how a list row finds the
     * one script that answers it.
     */
    private function pairKey(ExamQuestion|ExamAnswer $row): string
    {
        return $row->course_id . '-' . $row->subject_id;
    }
}
