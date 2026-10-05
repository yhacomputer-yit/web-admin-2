<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\DropOut;
use Illuminate\Http\Request;

class DropOutController extends Controller
{
    /**
     * Students who left a course before it finished.
     *
     * The list is read newest drop-out first, because the question an admin opens
     * it with is "who left recently". It is filtered on the row's own course
     * column, which is also what makes each row actionable: saving one closes the
     * enrollment the rest of the portal reads, so the record and the class list
     * cannot drift apart.
     */
    public function index(Request $request)
    {
        $courseId = $request->integer('course_id') ?: null;
        $term = trim((string) $request->input('q', ''));
        $from = trim((string) $request->input('from', ''));

        $dropOuts = DropOut::with(['student', 'course'])
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($from !== '', fn ($q) => $q->onOrAfter($from))
            ->when($term !== '', fn ($q) => $q->whereHas('student', function ($sub) use ($term) {
                $like = "%{$term}%";
                $sub->where('name', 'like', $like)
                    ->orWhere('username', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('nrc', 'like', $like);

                if (ctype_digit($term)) {
                    $sub->orWhere('id', (int) $term);
                }
            }))
            ->orderByDesc('drop_out_date')
            ->orderByDesc('id')
            ->get();

        return view('admin.dropOut.index', [
            'dropOuts' => $dropOuts,
            'courses' => Course::orderBy('name')->get(),
            'selectedCourseId' => $courseId,
            'term' => $term,
            'from' => $from,
        ]);
    }

    public function createPage(Request $request)
    {
        return view('admin.dropOut.create', [
            'courses' => Course::orderBy('name')->get(),
            // reached from the course filter on the list, so the class is known
            'preselectedCourseId' => $request->integer('course_id') ?: null,
        ]);
    }

    /**
     * Record the drop-out and close the enrollment.
     *
     * The two are saved together on purpose. A row with no enrollment behind it
     * leaves the student still counted in the class they left, which is the one
     * outcome this page exists to prevent - so the enrollment is closed in the
     * same request that records why.
     */
    public function create(Request $request)
    {
        $dropOut = DropOut::create($this->validated($request));

        $closed = $dropOut->closeEnrollments();

        return redirect()
            ->route('dropOut.index')
            ->with('success', 'Recorded a drop-out' . $this->closedNote($closed) . '.');
    }

    public function edit($id)
    {
        $dropOut = DropOut::with(['student', 'course'])->findOrFail($id);

        return view('admin.dropOut.edit', [
            'dropOut' => $dropOut,
            'courses' => Course::orderBy('name')->get(),
            'selectedStudent' => $dropOut->student,
        ]);
    }

    /**
     * Correct the record, and keep the enrollment agreeing with it.
     *
     * A changed date moves `complete_date` with it, and a changed student or
     * course reopens whatever the old pair was holding before the new pair is
     * closed - otherwise correcting a mistyped course would close a second class
     * and leave the first one shut for a student who is still in it.
     */
    public function update(Request $request, $id)
    {
        $dropOut = DropOut::findOrFail($id);

        $before = [
            'student_id' => $dropOut->student_id,
            'course_id' => $dropOut->course_id,
            'date' => $dropOut->drop_out_date?->toDateString(),
        ];

        $dropOut->fill($this->validated($request))->save();

        $moved = $before['student_id'] !== $dropOut->student_id || $before['course_id'] !== $dropOut->course_id;

        if ($moved && $before['date'] !== null) {
            DropOut::reopenFor($before['student_id'], $before['course_id'], $before['date']);
        }

        $closed = $dropOut->closeEnrollments();

        return redirect()
            ->route('dropOut.index')
            ->with('success', 'Updated the drop-out for ' . ($dropOut->student?->name ?? 'this student') . $this->closedNote($closed) . '.');
    }

    /**
     * Delete the record and reopen the class.
     *
     * The other half of writing it together: a drop-out that was recorded in error
     * must not leave the student excluded from a class they are still in. reopen()
     * only touches rows this record closed, so an enrollment that was already
     * dropped for another reason stays dropped.
     */
    public function delete($id)
    {
        $dropOut = DropOut::with(['student', 'course'])->findOrFail($id);

        $name = $dropOut->student?->name ?? 'this student';
        $date = $dropOut->drop_out_date?->format('j M Y') ?? 'an unknown date';

        $reopened = $dropOut->reopenEnrollments();

        $dropOut->delete();

        return redirect()
            ->route('dropOut.index')
            ->with('success', 'Deleted the drop-out for ' . $name . ' on ' . $date
                . ($reopened > 0 ? ' and put them back in the class' : '') . '.');
    }

    /**
     * Validate the form.
     *
     * The course is required because it is what the row acts on, and the date is
     * required because a drop-out without a day on it cannot be read back: the
     * list is ordered by it and reopening matches on it. The remark is free text
     * and optional - it is where the reason goes, not the event.
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'drop_out_date' => ['required', 'date'],
            'remark' => ['nullable', 'string', 'max:500'],
        ], [
            'student_id.required' => 'Choose the student who dropped out.',
            'student_id.exists' => 'That student no longer exists.',
            'course_id.required' => 'Choose the course they dropped out of.',
            'course_id.exists' => 'That course no longer exists.',
            'drop_out_date.required' => 'Enter the day the student dropped out.',
            'drop_out_date.date' => 'Enter the drop-out date as a real date.',
            'remark.max' => 'The remark may not be longer than 500 characters.',
        ]);

        return array_intersect_key($validated, array_flip([
            'student_id', 'course_id', 'drop_out_date', 'remark',
        ]));
    }

    /**
     * What closing the enrollment did, said plainly.
     *
     * An admin needs to see whether the class list actually moved. Zero means the
     * student had no enrollment in that course to close, which is worth saying
     * rather than passing over in silence.
     */
    private function closedNote(int $closed): string
    {
        return match (true) {
            $closed > 1 => " and closed {$closed} enrollments",
            $closed === 1 => ' and closed the enrollment',
            default => ' - no enrollment was open in that course to close',
        };
    }
}