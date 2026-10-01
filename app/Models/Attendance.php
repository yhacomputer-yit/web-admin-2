<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * `attendances.status` is an int column, so the states are defined here as
     * constants rather than free text.
     */
    public const PRESENT = 1;
    public const ABSENT = 2;
    public const LATE = 3;
    public const LEAVE = 4;

    /**
     * Every state a record can hold, with the wording shown to the student.
     *
     * The labels here must stay in step with MARKABLE_STATUSES: the admin grid
     * marks a class with one of those, and the portal table and the CSV exports
     * print these. A present class is called "Attended" in both, so the screen a
     * student reads and the report they download never disagree.
     */
    public const STATUSES = [
        self::PRESENT => 'Attended',
        self::ABSENT => 'Absent',
        self::LATE => 'Late',
        self::LEAVE => 'Leave',
    ];

    /**
     * The three marks an admin can pick on the classroom grid.
     *
     * `LATE` stays in STATUSES so historic rows and the reports keep their own
     * label, but it is not offered as a choice any more.
     */
    public const MARKABLE_STATUSES = [
        self::PRESENT => 'Attended',
        self::ABSENT => 'Absent',
        self::LEAVE => 'Leave',
    ];

    /** states that count as the student being in class */
    public const ATTENDED_STATUSES = [self::PRESENT, self::LATE];

    /**
     * Map a stored status onto one of MARKABLE_STATUSES.
     *
     * A row saved back when "Late" was still a choice has no button on the
     * grid, so it is shown as Attended: a late student was in class, and
     * leaving the card unchecked would block the whole save.
     */
    public static function markableStatus(?int $status): int
    {
        return isset(self::MARKABLE_STATUSES[$status]) ? $status : self::PRESENT;
    }

    /** Label for a stored status, folded onto the three marks that exist now. */
    public static function markableLabel(?int $status): string
    {
        return self::MARKABLE_STATUSES[self::markableStatus($status)];
    }

    protected $fillable = [
        'student_id',
        'course_id',
        'subject_id',
        'section_id',
        'date',
        'status',
        'remark',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    public function isAttended(): bool
    {
        return in_array($this->status, self::ATTENDED_STATUSES, true);
    }

    /** Bootstrap contextual class used by the admin tables and the portal. */
    public function statusColor(): string
    {
        return [
            self::PRESENT => 'success',
            self::ABSENT => 'danger',
            self::LATE => 'warning',
            self::LEAVE => 'secondary',
        ][$this->status] ?? 'light';
    }

    /**
     * Share of records in a given state, as a percentage of the total.
     *
     * Returns 0.0 rather than dividing by zero so callers can render the value
     * directly when a class has no records yet.
     *
     * @param  array<int|string, int|float>  $counts  status value => count
     */
    public static function percentFor(array $counts, int|string $status): float
    {
        $total = array_sum($counts);

        if ($total <= 0) {
            return 0.0;
        }

        return round(((int) ($counts[$status] ?? 0) / $total) * 100, 1);
    }

    /**
     * Count statuses for a set of attendance rows.
     *
     * Always returns every key of STATUSES so callers do not have to guard
     * against a missing bucket.
     *
     * @param  \Illuminate\Support\Collection<int, Attendance>|mixed  $attendances
     * @return array<int, int>
     */
    public static function tally($attendances): array
    {
        $counts = array_fill_keys(array_keys(self::STATUSES), 0);

        foreach ($attendances as $attendance) {
            $status = (int) $attendance->status;
            if (array_key_exists($status, $counts)) {
                $counts[$status]++;
            }
        }

        return $counts;
    }

    /**
     * Per-student summary for the portal dashboard.
     *
     * @return array{present:int, absent:int, late:int, leave:int, total:int, attended_percent:float}
     */
    public function summaryFor(int $studentId): array
    {
        $counts = static::where('student_id', $studentId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $present = (int) ($counts[self::PRESENT] ?? 0);
        $absent = (int) ($counts[self::ABSENT] ?? 0);
        $late = (int) ($counts[self::LATE] ?? 0);
        $leave = (int) ($counts[self::LEAVE] ?? 0);
        $total = $present + $absent + $late + $leave;

        return [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'leave' => $leave,
            'total' => $total,
            // late counts as attended; the denominator is every recorded class
            'attended_percent' => $total > 0
                ? round((($present + $late) / $total) * 100, 1)
                : 0.0,
        ];
    }
}
