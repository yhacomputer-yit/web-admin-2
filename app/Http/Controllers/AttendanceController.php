<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /** Show a wider classroom grid. */
    private const PER_PAGE = 60;

    /**
     * Mark attendance for one subject of a course + section on a given date.
     * Only students holding an active enrollment for that class are listed.
     */
    public function createPage(Request $request)
    {
        $courses = Course::orderBy('name')->get(['id', 'name']);
        $courseId = $request->integer('course_id') ?: $courses->first()?->id;

        $sections = $courseId ? $this->linkedSections($courseId) : collect();
        $sectionId = $request->integer('section_id') ?: $sections->first()?->id;

        $subjects = $courseId ? $this->linkedSubjects($courseId) : collect();
        $subjectId = $request->integer('subject_id') ?: $subjects->first()?->id;

        $date = $request->input('date', now()->toDateString());

        // a class is closed once no active enrollment is left, which is the
        // same rule StoreAttendanceRequest enforces on save
        $classClosed = false;
        if ($courseId && $sectionId) {
            $classClosed = StudentEnrollment::forClass($courseId, $sectionId)->count() > 0
                && StudentEnrollment::forClass($courseId, $sectionId)->active()->count() === 0;
        }

        return view('admin.attendance.create', [
            'courses' => $courses,
            'sections' => $sections,
            'subjects' => $subjects,
            'selectedCourseId' => $courseId,
            'selectedSectionId' => $sectionId,
            'selectedSubjectId' => $subjectId,
            'date' => $date,
            'classClosed' => $classClosed,
            'roster' => ($courseId && $sectionId) ? $this->roster($courseId, $sectionId, $subjectId, $date) : collect(),
            'attendanceStatuses' => Attendance::MARKABLE_STATUSES,
        ]);
    }

    /**
     * Persist the marks. Uses upsert so re-submitting a day overwrites the
     * previous value instead of inserting a duplicate row.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $courseId = (int) $request->input('course_id');
        $sectionId = (int) $request->input('section_id');
        $subjectId = (int) $request->input('subject_id');
        $date = $request->input('date');

        $now = now();

        DB::transaction(function () use ($request, $courseId, $sectionId, $subjectId, $date) {
            foreach ($request->input('entries', []) as $entry) {
                $remark = isset($entry['remark']) ? trim((string) $entry['remark']) : '';

                Attendance::updateOrCreate(
                    [
                        'student_id' => (int) $entry['student_id'],
                        'course_id' => $courseId,
                        'subject_id' => $subjectId,
                        'section_id' => $sectionId,
                        'date' => $date,
                    ],
                    [
                        'status' => (int) $entry['status'],
                        // an emptied remark should clear the note, not keep the old one
                        'remark' => $remark === '' ? null : $remark,
                    ]
                );
            }
        });

        return redirect()
            ->route('attendance.createPage', [
                'course_id' => $courseId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'date' => $date,
            ])
            ->with('success', 'Saved attendance for ' . count($request->input('entries', [])) . ' student(s).');
    }

    /**
     * Review saved attendance with filters and a summary breakdown.
     */
    public function index(Request $request)
    {
        $courses = Course::orderBy('name')->get(['id', 'name']);
        $courseId = $request->integer('course_id') ?: null;
        $sectionId = $request->integer('section_id') ?: null;
        $subjectId = $request->integer('subject_id') ?: null;
        // drill-down target for the clickable names on the reports page
        $studentId = $request->integer('student_id') ?: null;

        $from = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->input('to') ?: now()->endOfMonth()->toDateString();
        // the filter arrives as a query string, so cast before comparing against
        // the integer keys of STATUSES, otherwise the strict check always fails
        $status = $request->input('status') !== null && $request->input('status') !== ''
            ? (int) $request->input('status')
            : null;
        if ($status !== null && ! array_key_exists($status, Attendance::STATUSES)) {
            $status = null;
        }

        $query = Attendance::query()
            ->with(['student:id,name,username,nrc', 'course:id,name', 'subject:id,name', 'section:id,name'])
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($studentId, fn ($q) => $q->where('student_id', $studentId))
            ->when($status !== null, fn ($q) => $q->where('status', $status))
            ->whereBetween('date', [$from, $to])
            ->orderByDesc('date')
            ->orderBy('student_id');

        $summary = (clone $query)
            ->reorder()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [];
        foreach (Attendance::STATUSES as $value => $label) {
            $counts[$value] = (int) ($summary[$value] ?? 0);
        }
        $total = array_sum($counts);

        // collapse the historic "late" bucket into Attended so the three cards
        // still add up to every mark in range
        $markableCounts = [
            Attendance::PRESENT => $counts[Attendance::PRESENT] + $counts[Attendance::LATE],
            Attendance::ABSENT => $counts[Attendance::ABSENT],
            Attendance::LEAVE => $counts[Attendance::LEAVE],
        ];
        $attended = $markableCounts[Attendance::PRESENT];

        $records = (clone $query)->paginate(25)->withQueryString();

        // distinct people, not rows: one student with 20 records is one student
        $totalStudents = (clone $query)
            ->reorder()
            ->distinct()
            ->count('student_id');

        return view('admin.attendance.index', [
            'courses' => $courses,
            'sections' => $courseId ? $this->linkedSections($courseId) : collect(),
            'subjects' => $courseId ? $this->linkedSubjects($courseId) : collect(),
            'records' => $records,
            'statusCounts' => $markableCounts,
            'statusLabels' => Attendance::MARKABLE_STATUSES,
            'totalRecords' => $total,
            'totalStudents' => $totalStudents,
            'attendedPercent' => $total > 0 ? round(($attended / $total) * 100, 1) : 0.0,
            'filters' => [
                'course_id' => $courseId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'from' => $from,
                'to' => $to,
                'status' => $status,
                'student_id' => $studentId,
            ],
        ]);
    }

    /**
     * Complete a class straight from the marking screen.
     *
     * Delegates to the enrollment action rather than duplicating the status
     * rules, and redirects back to the marking page so the admin keeps their
     * place. StoreAttendanceRequest already refuses marks once nothing is
     * active, so completing here closes attendance for good.
     */
    public function completeClass(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
        ], [
            'course_id.required' => 'Please select a course.',
            'section_id.required' => 'Please select a section.',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        $section = Section::findOrFail($validated['section_id']);

        $enrollments = StudentEnrollment::forClass($course->id, $section->id)
            ->where('status', StudentEnrollment::STATUS_ACTIVE)
            ->get();

        if ($enrollments->isEmpty()) {
            return back()->with('error', 'There are no active enrollments to complete for that class.');
        }

        $today = now();
        $count = 0;
        foreach ($enrollments as $enrollment) {
            if ($enrollment->markAs(StudentEnrollment::STATUS_COMPLETED, $today)) {
                $count++;
            }
        }

        return redirect()
            ->route('attendance.createPage', ['course_id' => $course->id, 'section_id' => $section->id])
            ->with('success', "Completed {$section->name} of {$course->name} for $count student(s). Attendance is now closed for this class.");
    }

    /**
     * Change one student's status for a single day, from the review page.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:' . implode(',', array_keys(Attendance::MARKABLE_STATUSES))],
            'remark' => ['nullable', 'string', 'max:500'],
        ]);

        $record = Attendance::findOrFail($id);
        $record->update([
            'status' => (int) $validated['status'],
            'remark' => $request->input('remark'),
        ]);

        return back()->with('success', "Updated {$record->student?->name}'s attendance to " . Attendance::markableLabel($record->status) . ".");
    }

    /**
     * Export the filtered history as CSV.
     *
     * Same filters and ordering as the review page, but the whole filtered set
     * rather than one page of it, and streamed so a long range does not have
     * to be held in memory.
     */
    public function export(Request $request)
    {
        $from = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->input('to') ?: now()->endOfMonth()->toDateString();
        $status = $request->input('status') !== null && $request->input('status') !== ''
            ? (int) $request->input('status')
            : null;
        if ($status !== null && ! array_key_exists($status, Attendance::STATUSES)) {
            $status = null;
        }

        $rows = Attendance::query()
            ->with(['student:id,name,username', 'course:id,name', 'subject:id,name', 'section:id,name'])
            ->when($request->integer('course_id'), fn ($q) => $q->where('course_id', $request->integer('course_id')))
            ->when($request->integer('section_id'), fn ($q) => $q->where('section_id', $request->integer('section_id')))
            ->when($request->integer('subject_id'), fn ($q) => $q->where('subject_id', $request->integer('subject_id')))
            ->when($status !== null, fn ($q) => $q->where('status', $status))
            ->whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->orderBy('student_id')
            ->cursor();

        $filename = "attendance_{$from}_to_{$to}.csv";

        // streamDownload already sets Content-Type and Content-Disposition from
        // its arguments, so they must not be chained again
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            // Excel assumes a legacy codepage for BOM-less CSV, which mangles
            // the Burmese names and remarks in this export.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['Date', 'Student', 'Username', 'Course', 'Section', 'Subject', 'Status', 'Remark']);

            foreach ($rows as $record) {
                fputcsv($out, [
                    $record->date?->toDateString(),
                    $record->student?->name,
                    $record->student?->username,
                    $record->course?->name,
                    $record->section?->name,
                    $record->subject?->name,
                    $record->statusLabel(),
                    $record->remark,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Sections linked to a course (same source of truth as the enrollment
     * form), returned as JSON for the dependent select.
     */
    public function sectionsForCourse(Request $request, $courseId)
    {
        $linked = $this->linkedSections($courseId);

        return response()->json([
            'course_id' => (int) $courseId,
            'sections' => $linked->map(fn (Section $s) => ['id' => $s->id, 'name' => $s->name])->values(),
            'message' => $linked->isEmpty() ? 'No sections available for this course' : null,
        ]);
    }

    /**
     * Subjects taught by a course, returned as JSON for the dependent select.
     */
    public function subjectsForCourse(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $linked = $this->linkedSubjects($courseId);

        return response()->json([
            'course_id' => (int) $course->id,
            'subjects' => $linked->map(fn (Subject $s) => ['id' => $s->id, 'name' => $s->name])->values(),
            'message' => $linked->isEmpty() ? 'No subjects available for this course' : null,
        ]);
    }

    /**
     * @return Collection<int, Section>
     */
    private function linkedSections(int $courseId): Collection
    {
        return Section::whereIn('id', function ($q) use ($courseId) {
            $q->select('section_id')->from('course_sections')->where('course_id', $courseId);
        })->orderBy('start')->orderBy('id')->get(['id', 'name']);
    }

    /**
     * Subjects attached to a course through subject_detail.
     *
     * @return Collection<int, Subject>
     */
    private function linkedSubjects(int $courseId): Collection
    {
        return Subject::whereHas('courses', fn ($q) => $q->where('courses.id', $courseId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Students with an active enrollment for the class, carrying whatever was
     * already marked for the selected subject/date.
     *
     * @return Collection<int, object>
     */
    private function roster(int $courseId, int $sectionId, ?int $subjectId, string $date): Collection
    {
        $enrollments = StudentEnrollment::forClass($courseId, $sectionId)
            ->active()
            ->with('student:id,name,username,image,nrc')
            ->orderBy('student_id')
            ->get();

        $records = [];
        if ($subjectId) {
            $records = Attendance::where('course_id', $courseId)
                ->where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->whereDate('date', $date)
                ->get(['student_id', 'status', 'remark'])
                ->keyBy('student_id');
        }

        return $enrollments->map(function ($enrollment) use ($records) {
            $student = $enrollment->student;
            $mark = $records->get($enrollment->student_id);

            return (object) [
                'student' => $student,
                // a row saved back when "Late" was still offered maps to
                // Attended, so the grid always has one button pre-selected
                'status' => Attendance::markableStatus($mark ? (int) $mark->status : null),
                'remark' => $mark?->remark,
                'initials' => $this->initials($student?->name),
            ];
        });
    }

    /**
     * Up to two initials for the classroom avatar.
     */
    private function initials(?string $name): string
    {
        $parts = preg_split('/\s+/', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($parts === []) {
            return '?';
        }

        $first = mb_substr($parts[0], 0, 1);
        $last = count($parts) > 1 ? mb_substr($parts[count($parts) - 1], 0, 1) : '';

        return mb_strtoupper($first . $last);
    }
}
