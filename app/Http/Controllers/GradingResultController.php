<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Grading;
use App\Models\GradingResult;
use App\Models\SubjectDetail;
use Illuminate\Http\Request;

class GradingResultController extends Controller
{
    /**
     * The marks: a student, a subject of a course, a score and a day.
     *
     * This is the page a teacher actually works from, so it is filtered the way
     * marks are looked up - by course, then by subject, then by grade - and it
     * separates the marks that are still ungraded, because those are the ones
     * waiting for somebody to decide. Defaults to the newest sitting first.
     */
    public function index(Request $request)
    {
        $courseId = $request->integer('course_id') ?: null;
        $subjectId = $request->integer('subject_id') ?: null;
        $gradeId = $request->integer('grade_id') ?: null;
        $ungradedOnly = $request->boolean('ungraded');
        $term = trim((string) $request->input('q', ''));

        $results = GradingResult::with(['student', 'course', 'subject', 'grade'])
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($gradeId, fn ($q) => $q->where('grade_id', $gradeId))
            ->when($ungradedOnly, fn ($q) => $q->ungraded())
            ->when($term !== '', fn ($q) => $q->whereHas('student', function ($sub) use ($term) {
                $like = "%{$term}%";
                $sub->where('name', 'like', $like)
                    ->orWhere('username', 'like', $like);

                if (ctype_digit($term)) {
                    $sub->orWhere('id', (int) $term);
                }
            }))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('admin.gradingResult.index', [
            'results' => $results,
            'courses' => Course::orderBy('name')->get(),
            'gradings' => Grading::orderByDesc('score')->get(),
            'subjectsByCourse' => $this->subjectsByCourse(),
            'selectedCourseId' => $courseId,
            'selectedSubjectId' => $subjectId,
            'selectedGradeId' => $gradeId,
            'ungradedOnly' => $ungradedOnly,
            'term' => $term,
        ]);
    }

    public function createPage(Request $request)
    {
        return view('admin.gradingResult.create', [
            'courses' => Course::orderBy('name')->get(),
            'gradings' => Grading::orderByDesc('score')->get(),
            'subjectsByCourse' => $this->subjectsByCourse(),
            // reached from a course row on the list, so the course is already known
            'preselectedCourseId' => $request->integer('course_id') ?: null,
            'preselectedSubjectId' => $request->integer('subject_id') ?: null,
        ]);
    }

    public function create(Request $request)
    {
        GradingResult::create($this->validated($request));

        return redirect()
            ->route('gradingResult.index')
            ->with('success', 'Recorded a mark.');
    }

    public function edit($id)
    {
        return view('admin.gradingResult.edit', [
            'result' => GradingResult::with('student')->findOrFail($id),
            'courses' => Course::orderBy('name')->get(),
            'gradings' => Grading::orderByDesc('score')->get(),
            'subjectsByCourse' => $this->subjectsByCourse(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = GradingResult::findOrFail($id);

        $result->fill($this->validated($request))->save();

        return redirect()
            ->route('gradingResult.index')
            ->with('success', 'Updated the mark for ' . ($result->student?->name ?? 'this student') . '.');
    }

    public function delete($id)
    {
        $result = GradingResult::with(['student', 'subject'])->findOrFail($id);

        $name = $result->student?->name ?? 'this student';
        $subject = $result->subject?->name ?? 'this subject';

        $result->delete();

        return redirect()
            ->route('gradingResult.index')
            ->with('success', 'Deleted ' . $name . "'s mark for " . $subject . '.');
    }

    /**
     * Validate the form.
     *
     * `grade_id` is optional on purpose: a mark is read off the script on the day
     * and put into a band later, so an empty pick is "not graded yet" rather than
     * a missing value. Everything else is required - a mark with no student, no
     * subject or no day cannot be found again.
     *
     * The subject has to belong to the course, through subject_detail. That is the
     * same rule the reference module uses, and it is what stops a mark from being
     * filed against a subject the class never teaches.
     */
    private function validated(Request $request): array
    {
        $courseId = (int) $request->input('course_id');

        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
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
                        $fail('That subject is not linked to the selected course. Link it under Course Subjects first.');
                    }
                },
            ],
            'grade_id' => ['nullable', 'integer', 'exists:gradings,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:9999'],
            'date' => ['required', 'date'],
        ], [
            'student_id.required' => 'Choose the student this mark belongs to.',
            'student_id.exists' => 'That student no longer exists.',
            'course_id.required' => 'Choose the course.',
            'course_id.exists' => 'That course no longer exists.',
            'subject_id.required' => 'Choose the subject.',
            'subject_id.exists' => 'That subject no longer exists.',
            'grade_id.exists' => 'That grade band no longer exists.',
            'score.required' => 'Enter the mark.',
            'score.numeric' => 'The mark must be a number.',
            'score.min' => 'A mark cannot be below 0.',
            'score.max' => 'That mark looks too large - check the number.',
            'date.required' => 'Enter the day the mark is for.',
            'date.date' => 'Enter the date as a real date.',
        ]);

        // grade_id stays null when the band has not been decided yet, which the
        // ungraded filter on the list reads back
        return array_intersect_key($validated, array_flip([
            'student_id', 'course_id', 'subject_id', 'grade_id', 'score', 'date',
        ]));
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
}