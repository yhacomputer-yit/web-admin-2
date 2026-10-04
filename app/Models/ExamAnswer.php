<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One submitted answer script per student per course + subject.
 *
 * `answer_file` is a path on the `public` disk, not a blob, so a submitted
 * script is served straight from storage instead of being pulled through PHP.
 * It stays nullable so a row can be reserved for a student who has not turned in
 * yet, which is why "submitted" is not simply "has a file": a script recorded
 * with a time but no file still counts as handed in, and a file with no time
 * still counts too.
 *
 * The unique index on (course_id, subject_id, student_id) is what makes the
 * answer per subject rather than per sitting -- a student sits the same subject's
 * exam more than once and there is one script for it.
 *
 * @property int         $id
 * @property int         $course_id
 * @property int         $subject_id
 * @property int         $student_id
 * @property string|null $answer_file     path on the `public` disk
 * @property \Illuminate\Support\Carbon|null $submitted_date
 */
class ExamAnswer extends Model
{
    use HasFactory;

    /**
     * What an answer script may be, and how large.
     *
     * A script is normally a PDF, but a student who wrote theirs on paper and
     * photographed it is not doing anything wrong, so the common image formats
     * are accepted too. 20 MB is the same ceiling as a material book and is
     * comfortably more than a photographed script needs.
     *
     * Extensions are listed without the dot, for StoredFile::store().
     */
    public const ACCEPTED = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];

    public const MAX_KILOBYTES = 20480;

    protected $table = 'exam_answers';

    protected $fillable = [
        'course_id',
        'subject_id',
        'student_id',
        'answer_file',
        'submitted_date',
    ];

    protected $casts = [
        'submitted_date' => 'datetime',
    ];

    /**
     * The one script a student has for a course + subject, which is what the
     * unique index on those three columns allows.
     *
     * @param  Builder<self>  $query
     */
    public function scopeForPair(Builder $query, int $courseId, int $subjectId, int $studentId): Builder
    {
        return $query
            ->where('course_id', $courseId)
            ->where('subject_id', $subjectId)
            ->where('student_id', $studentId);
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
     * Handed in, whether the script reached the server or only the time did.
     */
    public function isSubmitted(): bool
    {
        return filled($this->answer_file) || $this->submitted_date !== null;
    }
}