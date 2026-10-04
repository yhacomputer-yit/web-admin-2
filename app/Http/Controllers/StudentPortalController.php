<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Material;
use App\Models\Student;
use App\Support\TimeOfDay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StudentPortalController extends Controller
{
    /**
     * The student's enrollments in the shape the portal pages expect.
     *
     * Shared by the dashboard and the courses list so both stay in step.
     */
    private function portalEnrollments(Student $student)
    {
        return $student->enrollments()
            ->with(['course:id,name,image,type', 'course.courseType:id,name', 'section:id,name,start,end'])
            ->orderByDesc('enroll_date')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'course_id' => $e->course_id,
                'course_name' => $e->course?->name,
                'course_image' => $e->course?->image,
                'course_type' => $e->course?->courseType?->name,
                'section_name' => $e->section?->name,
                'section_start' => TimeOfDay::format($e->section?->start),
                'section_end' => TimeOfDay::format($e->section?->end),
                'enroll_date' => $e->enroll_date?->format('Y-m-d'),
            ])->values();
    }

    /**
     * The reference files a student can reach, grouped course -> subject -> file.
     *
     * Scoped to the courses the student is enrolled in, because a `reference`
     * row is one course's teaching material and the portal has no other gate on
     * it. A course the student is not enrolled in is never loaded, so it cannot
     * leak through this shape either.
     *
     * A subject holds as many rows as it has files, so the same subject shows up
     * once with every one of its files under it. A row whose file has gone from
     * storage is dropped rather than shown as an empty subject: a student should
     * not see a subject with nothing to open.
     *
     * @return array<int, array{course_id:int, course_name:?string, file_count:int, subjects:array}>
     */
    private function portalReferences(Student $student): array
    {
        $courseIds = $student->enrollments()
            ->distinct()
            ->pluck('course_id')
            ->all();

        if ($courseIds === []) {
            return [];
        }

        return Material::with(['course:id,name', 'subject:id,name'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('course_id')
            ->orderBy('subject_id')
            ->orderBy('id')
            ->get()
            ->map(function (Material $material) {
                $file = $this->referenceFile($material);

                return $file === null ? null : [
                    'course_id' => $material->course_id,
                    'course_name' => $material->course?->name,
                    'subject_id' => $material->subject_id,
                    'subject_name' => $material->subject?->name ?? ('Subject #' . $material->subject_id),
                    'file' => $file,
                ];
            })
            ->filter()
            ->groupBy('course_id')
            ->map(function ($files, $courseId) {
                return [
                    'key' => 'c' . $courseId,
                    'course_id' => (int) $courseId,
                    'course_name' => $files->first()['course_name'] ?? ('Course #' . $courseId),
                    'file_count' => $files->count(),
                    'subjects' => $files->groupBy('subject_id')
                        ->map(fn ($subjectFiles) => [
                            'key' => 's' . $subjectFiles->first()['subject_id'],
                            'id' => $subjectFiles->first()['subject_id'],
                            'name' => $subjectFiles->first()['subject_name'],
                            'materials' => $subjectFiles->pluck('file')->values()->all(),
                        ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * One reference row as the file a student can open, or null when it has none.
     *
     * The row is the file, so there is nothing to loop over: `type` is the word
     * the portal pages already group by, and `file` is a ready URL so no page
     * has to rebuild a storage path.
     *
     * @return array<string, mixed>|null
     */
    private function referenceFile(Material $material): ?array
    {
        if (blank($material->file_link)) {
            return null;
        }

        return [
            'key' => 'm' . $material->id,
            'type' => $material->type,
            'title' => $material->displayTitle(),
            'file' => $material->url(),
            // null when the stored path has no file behind it, which the page
            // shows as "size unknown" rather than a wrong number
            'size' => $this->fileSize($material->file_link),
            'description' => $material->remark,
            'updated' => $material->updated_at?->format('Y-m-d'),
        ];
    }

    /**
     * Every reference file of one course, flat, for the course detail page.
     *
     * Scoped by the caller's own enrollment check, so only a course the student
     * is enrolled in ever reaches this.
     */
    private function courseReferenceFiles(int $courseId): array
    {
        return Material::with('subject:id,name')
            ->where('course_id', $courseId)
            ->orderBy('subject_id')
            ->orderBy('id')
            ->get()
            ->map(function (Material $material) {
                $file = $this->referenceFile($material);

                if ($file === null) {
                    return null;
                }

                $subject = $material->subject?->name ?? ('Subject #' . $material->subject_id);

                // the detail page groups by file type and has no subject column,
                // so the subject travels with the file as its description, with
                // the admin's remark behind it when there is one
                $file['description'] = $material->remark
                    ? $subject . ' — ' . $material->remark
                    : $subject;

                return $file;
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * A human file size, or null when the stored file is gone.
     *
     * A row can outlive its upload (a file deleted off disk, a restore that did
     * not include storage), and the dashboard must not fail to render because one
     * size lookup threw, so the lookup is contained here.
     */
    private function fileSize(string $path): ?string
    {
        try {
            $disk = Storage::disk('public');

            if (! $disk->exists($path)) {
                return null;
            }

            $bytes = $disk->size($path);
        } catch (\Throwable $e) {
            return null;
        }

        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, $unit === 'B' ? 0 : 1) . ' ' . $unit;
            }

            $bytes /= 1024;
        }

        return round($bytes, 1) . ' TB';
    }

    // student dashboard: profile + enrolled courses + attendance overview
    public function dashboard(Request $request)
    {
        $student = Auth::guard('student')->user();

        // the overview card and the calendar describe the same month, so they
        // are anchored once and share the query
        $anchor = $this->monthAnchor($request->input('month'));
        $monthStart = $anchor->copy()->startOfMonth()->toDateString();
        $monthEnd = $anchor->copy()->endOfMonth()->toDateString();

        $base = Attendance::where('student_id', $student->id);

        return Inertia::render('StudentDashboard', [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'nickname' => $student->nickname,
                'username' => $student->username,
                'email' => $student->email,
                'phone' => $student->phone,
                'address' => $student->address,
                'date_of_birth' => $student->date_of_birth?->format('Y-m-d'),
                'nrc' => $student->nrc,
                'gender' => $student->gender,
                'education' => $student->education,
                'native_town' => $student->native_town,
                'religious_status' => $student->religious_status,
                'race' => $student->race,
                'image' => $student->image,
                'status' => $student->status,
                'register_date' => ($student->register_date ?: $student->created_at)?->format('Y-m-d'),
            ],
            'enrollments' => $this->portalEnrollments($student),
            'attendance' => [
                'summary' => $this->attendanceSummary(
                    (clone $base)->whereBetween('date', [$monthStart, $monthEnd])
                ),
                'calendar' => $this->attendanceCalendar($base, $anchor),
                'prev_month' => $anchor->copy()->subMonth()->format('Y-m'),
                'next_month' => $anchor->copy()->addMonth()->format('Y-m'),
                'month' => $anchor->format('Y-m'),
            ],
        ]);
    }

    /**
     * The month a calendar should show.
     *
     * Accepts an untrusted `?month=YYYY-MM`, so anything unparseable falls back
     * to the current month rather than throwing.
     */
    private function monthAnchor(?string $month): Carbon
    {
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            return now()->startOfMonth();
        }

        try {
            $date = Carbon::createFromFormat('Y-m', $month);
        } catch (\Throwable $e) {
            return now()->startOfMonth();
        }

        // createFromFormat does not reject an out-of-range month, it overflows
        // it: '2026-13' comes back as January 2027. Round-tripping the value is
        // what actually catches that.
        return $date->format('Y-m') === $month
            ? $date->startOfMonth()
            : now()->startOfMonth();
    }

    /**
     * Day-by-day status for one month, keyed by Y-m-d for the calendar grid.
     *
     * @return array{year:int, month:int, label:string, days:array<string, array{status:int, label:string}>}
     */
    private function attendanceCalendar($base, Carbon $anchor): array
    {
        $rows = (clone $base)
            ->whereBetween('date', [
                $anchor->copy()->startOfMonth()->toDateString(),
                $anchor->copy()->endOfMonth()->toDateString(),
            ])
            ->get(['date', 'status']);

        $days = [];
        foreach ($rows as $row) {
            $days[$row->date->toDateString()] = [
                'status' => (int) $row->status,
                'label' => Attendance::STATUSES[$row->status] ?? 'Unknown',
            ];
        }

        return [
            'year' => $anchor->year,
            'month' => $anchor->month,
            'label' => $anchor->format('F Y'),
            'days' => $days,
        ];
    }

    // enrolled courses, each linking through to its reference files
    public function courses()
    {
        $student = Auth::guard('student')->user();

        return Inertia::render('StudentCourses', [
            'enrollments' => $this->portalEnrollments($student),
            'references' => $this->portalReferences($student),
        ]);
    }

    // assignments list. The page is an empty shell until the assignment tables
    // exist, so nothing is passed to it and it renders no UI at all.
    public function assignments()
    {
        return Inertia::render('StudentAssignments');
    }

    // course detail with its learning reference files
    public function courseDetail(Request $request, $courseId)
    {
        $student = Auth::guard('student')->user();
        $courseId = (int) $courseId;

        $enrollment = $student->enrollments()
            ->with(['course:id,name,image,type,description', 'course.courseType:id,name', 'section:id,name'])
            ->where('course_id', $courseId)
            ->orderByDesc('enroll_date')
            ->first();

        // only courses the student is actually enrolled in are reachable
        abort_if(! $enrollment, 404);

        return Inertia::render('StudentCourseDetail', [
            'course' => [
                'id' => $enrollment->course_id,
                'name' => $enrollment->course?->name,
                'image' => $enrollment->course?->image,
                'type' => $enrollment->course?->courseType?->name,
                'description' => $enrollment->course?->description,
            ],
            'section' => $enrollment->section?->name,
            'materials' => $this->courseReferenceFiles($courseId),
        ]);
    }

    // student attendance: the recent records list only.
    // The summary cards and the calendar live on the dashboard, so this page no
    // longer needs the month anchor -- the list spans all dates.
    public function attendance(Request $request)
    {
        $student = Auth::guard('student')->user();

        $courseId = $request->integer('course_id') ?: null;

        $courses = $student->enrollments()
            ->with('course:id,name')
            ->orderBy('enroll_date')
            ->get()
            ->pluck('course')
            ->filter()
            ->unique('id')
            ->values();

        $base = Attendance::where('student_id', $student->id)
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId));

        $recent = (clone $base)
            ->with(['course:id,name', 'subject:id,name', 'section:id,name'])
            ->orderByDesc('date')
            ->orderBy('subject_id')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Attendance $a) => [
                'id' => $a->id,
                'date' => $a->date?->toDateString(),
                'date_label' => $a->date?->format('d M Y'),
                'course_name' => $a->course?->name,
                'subject_name' => $a->subject?->name,
                'section_name' => $a->section?->name,
                'status' => (int) $a->status,
                'status_label' => $a->statusLabel(),
                'attended' => $a->isAttended(),
                'remark' => $a->remark,
            ]);

        return Inertia::render('StudentAttendance', [
            'recent' => $recent,
            'courses' => $courses->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values(),
            'filters' => [
                'course_id' => $courseId,
            ],
        ]);
    }

    /**
     * Turn a grouped status count into the dashboard summary shape.
     *
     * @return array{present:int, absent:int, late:int, leave:int, total:int, attended_percent:float}
     */
    private function attendanceSummary($query): array
    {
        $counts = (clone $query)
            ->reorder()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $present = (int) ($counts[Attendance::PRESENT] ?? 0);
        $absent = (int) ($counts[Attendance::ABSENT] ?? 0);
        $late = (int) ($counts[Attendance::LATE] ?? 0);
        $leave = (int) ($counts[Attendance::LEAVE] ?? 0);
        $total = $present + $absent + $late + $leave;

        return [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'leave' => $leave,
            'total' => $total,
            // late counts as attended; the denominator is every recorded class
            'attended_percent' => $total > 0 ? round((($present + $late) / $total) * 100, 1) : 0.0,
        ];
    }

    // student logout
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
