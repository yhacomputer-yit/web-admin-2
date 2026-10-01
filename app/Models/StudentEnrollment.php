<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DROPPED = 'dropped';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
        self::STATUS_DROPPED,
    ];

    protected $table = 'student_enrollments';

    protected $fillable = [
        'student_id',
        'course_id',
        'section_id',
        'enroll_date',
        'status',
        'complete_date',
    ];

    protected $casts = [
        'enroll_date' => 'date',
        'complete_date' => 'datetime',
    ];

    // new enrollments start active and are never complete on creation
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'student_id')
            ->whereColumn('attendances.course_id', 'student_enrollments.course_id')
            ->whereColumn('attendances.section_id', 'student_enrollments.section_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForClass(Builder $query, int $courseId, ?int $sectionId = null): Builder
    {
        return $query->where('course_id', $courseId)
            ->when(
                $sectionId === null,
                fn (Builder $q) => $q->whereNull('section_id'),
                fn (Builder $q) => $q->where('section_id', $sectionId)
            );
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Mark this enrollment as finished. Keeps status and complete_date in step
     * so no row can end up completed without a date (or vice versa).
     */
    public function markAs(string $status, ?\DateTimeInterface $completeDate = null): bool
    {
        $this->status = $status;
        $this->complete_date = $status === self::STATUS_ACTIVE ? null : ($completeDate ?? now());

        return $this->save();
    }
}
