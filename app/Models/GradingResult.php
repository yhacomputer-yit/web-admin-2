<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * One mark: a student's score on one subject of one course, on one day.
 *
 * `grade_id` is nullable because the two halves are done at different times. The
 * mark comes off the script on the day; the grade is settled later, sometimes after
 * the term, and by then it is an update to this same row rather than a new one. A
 * result without a grade is therefore "not graded yet" and nothing else.
 *
 * @property int         $id
 * @property int|null    $grade_id
 * @property int         $student_id
 * @property int         $course_id
 * @property int         $subject_id
 * @property string      $score
 * @property Carbon      $date
 * @property-read Grading|null $grade
 * @property-read Student      $student
 * @property-read Course       $course
 * @property-read Subject      $subject
 */
class GradingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'student_id',
        'course_id',
        'subject_id',
        'score',
        'date',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'date' => 'date',
    ];

    public function grade()
    {
        return $this->belongsTo(Grading::class, 'grade_id');
    }

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

    /**
     * Marks that are still waiting for a band.
     */
    public function scopeUngraded(Builder $query): Builder
    {
        return $query->whereNull('grade_id');
    }

    /**
     * Whether this mark has been put into a band yet.
     */
    public function isGraded(): bool
    {
        return $this->grade_id !== null;
    }

    /**
     * The mark as it should be shown, with no trailing zeros: 70 rather than
     * 70.00, and 70.5 rather than 70.50.
     */
    public function scoreLabel(): string
    {
        return rtrim(rtrim((string) $this->score, '0'), '.');
    }
}