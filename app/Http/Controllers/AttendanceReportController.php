<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Read-only attendance analytics.
 *
 * Every report shares one date window and one optional course filter, so the
 * whole page describes a single period rather than five unrelated ones.
 */
class AttendanceReportController extends Controller
{
    /** How many rows the "most absent" leaderboard shows. */
    private const TOP_ABSENT = 10;

    public function index(Request $request)
    {
        [$from, $to, $courseId] = $this->window($request);

        $base = Attendance::query()
            ->when($courseId, fn ($q) => $q->where('attendances.course_id', $courseId))
            // qualified because the joined student/course tables have their own
            // date and status columns
            ->whereBetween('attendances.date', [$from, $to]);

        return view('admin.attendance.report', [
            'courses' => Course::orderBy('name')->get(['id', 'name']),
            'from' => $from,
            'to' => $to,
            'selectedCourseId' => $courseId,
            'totalRecords' => (clone $base)->count(),
            'courseBreakdown' => $this->courseBreakdown($base),
            'studentBreakdown' => $this->studentBreakdown($base),
            'mostAbsent' => $this->mostAbsent($base),
            'subjectBreakdown' => $this->subjectBreakdown($base),
            'monthlyBreakdown' => $this->monthlyBreakdown($base),
        ]);
    }

    /**
     * Raw aggregate expressions, shared by every report query.
     *
     * Two things force the awkward syntax here:
     *  - `leave` is a reserved word in MySQL, so the alias must be backticked.
     *  - `status` and `date` exist on `students` as well, so the expressions and
     *    the date filter must be qualified to the attendances table or the
     *    joined query is ambiguous.
     */
    private const TOTALS = 'COUNT(*) AS total, SUM(attendances.status = 1) AS `present`, SUM(attendances.status = 2) AS `absent`, SUM(attendances.status = 3) AS `late`, SUM(attendances.status = 4) AS `leave`';

    /**
     * Export the raw records behind the reports, so the numbers can be
     * re-checked or pivoted in a spreadsheet.
     */
    public function export(Request $request)
    {
        [$from, $to, $courseId] = $this->window($request);

        $rows = Attendance::query()
            ->with(['student:id,name,username', 'course:id,name', 'section:id,name', 'subject:id,name'])
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->orderBy('student_id')
            ->cursor();

        $filename = "attendance_records_{$from}_to_{$to}.csv";

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
     * Resolve the shared date window. Defaults to the current month, matching
     * the review page so the two do not disagree about "today's" data.
     *
     * @return array{0: string, 1: string, 2: int|null}
     */
    private function window(Request $request): array
    {
        $from = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->input('to') ?: now()->endOfMonth()->toDateString();
        $courseId = $request->integer('course_id') ?: null;

        // a reversed range would silently match nothing
        if (Carbon::parse($from)->gt(Carbon::parse($to))) {
            [$from, $to] = [$to, $from];
        }

        return [$from, $to, $courseId];
    }

    /**
     * Attendance percentage per course.
     *
     * @return Collection<int, object>
     */
    private function courseBreakdown($base): Collection
    {
        $rows = (clone $base)
            ->join('courses', 'courses.id', '=', 'attendances.course_id')
            ->groupBy('attendances.course_id', 'courses.name')
            ->selectRaw('courses.name AS name')
            ->selectRaw(self::TOTALS)
            ->orderBy('name')
            ->get();

        return $rows->map(function ($row) {
            $counts = $this->countsOf($row);

            return (object) [
                'name' => $row->name,
                'total' => (int) $row->total,
                'present' => (int) $row->present,
                'attended' => (int) $row->present + (int) $row->late,
                'absent' => (int) $row->absent,
                'late' => (int) $row->late,
                'leave' => (int) $row->leave,
                'percent' => $this->attendedPercent($counts),
            ];
        });
    }

    /**
     * Attendance percentage per student, best first so the weak students are
     * easy to spot without sorting.
     *
     * @return Collection<int, object>
     */
    private function studentBreakdown($base): Collection
    {
        $rows = (clone $base)
            ->join('students', 'students.id', '=', 'attendances.student_id')
            ->groupBy('attendances.student_id', 'students.name', 'students.username')
            ->selectRaw('attendances.student_id AS student_id')
            ->selectRaw('students.name AS name')
            ->selectRaw('students.username AS username')
            ->selectRaw(self::TOTALS)
            ->orderBy('total', 'desc')
            ->get();

        return $rows->map(function ($row) {
            $counts = $this->countsOf($row);

            return (object) [
                'student_id' => (int) $row->student_id,
                'name' => $row->name,
                'username' => $row->username,
                'total' => (int) $row->total,
                'present' => (int) $row->present,
                'attended' => (int) $row->present + (int) $row->late,
                'absent' => (int) $row->absent,
                'late' => (int) $row->late,
                'leave' => (int) $row->leave,
                'percent' => $this->attendedPercent($counts),
            ];
        });
    }

    /**
     * The most absent students, ranked by absence rate then absolute count so a
     * student with one absence in one class does not outrank someone absent 8
     * times.
     *
     * @return Collection<int, object>
     */
    private function mostAbsent($base): Collection
    {
        $rows = $this->studentBreakdown($base)
            ->filter(fn ($row) => $row->absent > 0)
            // absence rate first, then how many, then the larger sample wins ties
            ->sortByDesc(fn ($row) => $row->absent / max(1, $row->total))
            ->sortByDesc(fn ($row) => $row->absent)
            ->sortByDesc(fn ($row) => $row->total)
            ->values()
            ->take(self::TOP_ABSENT);

        return $rows->map(fn ($row, $i) => (object) [
            'rank' => $i + 1,
            'student_id' => $row->student_id,
            'name' => $row->name,
            'username' => $row->username,
            'absent' => $row->absent,
            'total' => $row->total,
            'absent_percent' => $row->total > 0 ? round(($row->absent / $row->total) * 100, 1) : 0.0,
        ]);
    }

    /**
     * Attendance percentage per subject.
     *
     * @return Collection<int, object>
     */
    private function subjectBreakdown($base): Collection
    {
        $rows = (clone $base)
            ->join('subjects', 'subjects.id', '=', 'attendances.subject_id')
            ->groupBy('attendances.subject_id', 'subjects.name')
            ->selectRaw('subjects.name AS name')
            ->selectRaw(self::TOTALS)
            ->orderBy('name')
            ->get();

        return $rows->map(function ($row) {
            $counts = $this->countsOf($row);

            return (object) [
                'name' => $row->name,
                'total' => (int) $row->total,
                'present' => (int) $row->present,
                'attended' => (int) $row->present + (int) $row->late,
                'absent' => (int) $row->absent,
                'late' => (int) $row->late,
                'leave' => (int) $row->leave,
                'percent' => $this->attendedPercent($counts),
            ];
        });
    }

    /**
     * One row per calendar month in the window.
     *
     * Grouped in SQL on the date column so months with no classes are simply
     * absent rather than padded with empty rows.
     *
     * @return Collection<int, object>
     */
    private function monthlyBreakdown($base): Collection
    {
        $rows = (clone $base)
            ->groupByRaw('DATE_FORMAT(attendances.date, "%Y-%m")')
            ->selectRaw('DATE_FORMAT(attendances.date, "%Y-%m") AS ym')
            ->selectRaw(self::TOTALS)
            ->orderBy('ym')
            ->get();

        return $rows->map(function ($row) {
            $counts = $this->countsOf($row);
            $month = Carbon::createFromFormat('Y-m', $row->ym);

            return (object) [
                'label' => $month ? $month->format('F Y') : $row->ym,
                'total' => (int) $row->total,
                'present' => (int) $row->present,
                'attended' => (int) $row->present + (int) $row->late,
                'absent' => (int) $row->absent,
                'late' => (int) $row->late,
                'leave' => (int) $row->leave,
                'percent' => $this->attendedPercent($counts),
            ];
        });
    }

    /**
     * Normalise a grouped row into the four status buckets.
     *
     * @return array<int, int>
     */
    private function countsOf($row): array
    {
        return [
            Attendance::PRESENT => (int) ($row->present ?? 0),
            Attendance::ABSENT => (int) ($row->absent ?? 0),
            Attendance::LATE => (int) ($row->late ?? 0),
            Attendance::LEAVE => (int) ($row->leave ?? 0),
        ];
    }

    /**
     * Present + late over the total, matching the portal's definition of
     * "attended".
     *
     * @param  array<int, int>  $counts
     */
    private function attendedPercent(array $counts): float
    {
        $total = array_sum($counts);

        if ($total <= 0) {
            return 0.0;
        }

        $attended = $counts[Attendance::PRESENT] + $counts[Attendance::LATE];

        return round(($attended / $total) * 100, 1);
    }
}
