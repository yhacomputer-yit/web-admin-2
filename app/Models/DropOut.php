<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * A student leaving a course before it finished.
 *
 * The row is the record of the event, not a state on the student: the same person
 * can drop out of one class and still be attending another, so what marks them as
 * gone has to name the date it happened. That is also why nothing here is unique
 * on the student - see the create_drop_outs migration for the sequence a single
 * student can legitimately produce.
 *
 * `course_id` is what makes the record actionable rather than only descriptive.
 * A student attends several courses at once, so the class has to be named before
 * anything else can be moved: closeFor() is how a drop-out reaches the enrollment
 * the rest of the portal reads.
 *
 * @property int         $id
 * @property int         $student_id
 * @property int|null    $course_id
 * @property Carbon      $drop_out_date
 * @property string|null $remark
 * @property-read Student         $student
 * @property-read Course|null     $course
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StudentEnrollment> $enrollments
 */
class DropOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'drop_out_date',
        'remark',
    ];

    protected $casts = [
        'drop_out_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * The enrollments this drop-out speaks for: every row the student holds in
     * this course, whichever section.
     *
     * All of them rather than the first, because a student is in or out of a
     * course - they are not out of one of its sections while still attending
     * another.
     *
     * The course is bound as a value rather than joined, so this same relation is
     * what closeFor() writes through.
     */
    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id')
            ->where('course_id', $this->course_id);
    }

    /**
     * Mark the student as having dropped out of that course.
     *
     * `complete_date` carries the drop-out date because that column is where an
     * enrollment records the day it ended, whatever ended it - and a date the
     * certificate form reads back is exactly what a finished course has.
     *
     * @return int the number of enrollments closed
     */
    public function closeEnrollments(): int
    {
        if (blank($this->course_id) || $this->drop_out_date === null) {
            return 0;
        }

        return static::closeFor($this->student_id, $this->course_id, $this->drop_out_date);
    }

    /**
     * Put back only what closeEnrollments() took away.
     *
     * @return int the number of enrollments reopened
     */
    public function reopenEnrollments(): int
    {
        if (blank($this->course_id) || $this->drop_out_date === null) {
            return 0;
        }

        return static::reopenFor($this->student_id, $this->course_id, $this->drop_out_date->toDateString());
    }

    /**
     * Close a student out of a course, whichever section they hold it in.
     *
     * Takes the pair as arguments rather than reading it off the model because a
     * correction has to close the *new* pair while reopening the old one, and by
     * then the row no longer holds the old values.
     *
     * @return int the number of enrollments closed
     */
    public static function closeFor(int $studentId, ?int $courseId, Carbon $date): int
    {
        if (blank($courseId)) {
            return 0;
        }

        return StudentEnrollment::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->update([
                'status' => StudentEnrollment::STATUS_DROPPED,
                'complete_date' => $date,
            ]);
    }

    /**
     * Reopen only the enrollments closed on this exact day.
     *
     * Matching the date as well as the status is the point: deleting a record has
     * to undo that record, not reopen a course the student had already left for
     * some other reason and happened to leave on the same day.
     *
     * @return int the number of enrollments reopened
     */
    public static function reopenFor(int $studentId, ?int $courseId, string $date): int
    {
        if (blank($courseId)) {
            return 0;
        }

        return StudentEnrollment::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('status', StudentEnrollment::STATUS_DROPPED)
            ->whereDate('complete_date', $date)
            ->update([
                'status' => StudentEnrollment::STATUS_ACTIVE,
                'complete_date' => null,
            ]);
    }

    /**
     * Drop-outs on or after a date, so a list can be narrowed to a term.
     */
    public function scopeOnOrAfter(Builder $query, string $date): Builder
    {
        return $query->whereDate('drop_out_date', '>=', $date);
    }
}