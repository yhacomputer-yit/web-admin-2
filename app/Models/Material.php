<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The files attached to one subject of one course.
 *
 * Deliberately thin: the three link columns are the whole record. Anything
 * richer (a title, a description, a sort order) belongs in a separate table,
 * because a row here is keyed by the course + subject pair rather than by the
 * file.
 *
 * @property int         $id
 * @property int         $course_id
 * @property int         $subject_id
 * @property string|null $book_link  path on the `public` disk
 * @property string|null $video_link path on the `public` disk
 * @property string|null $zip_link   path on the `public` disk
 */
class Material extends Model
{
    use HasFactory;

    // No $table override: Material pluralises to `materials`, which is the name
    // the table actually has.
    protected $fillable = [
        'course_id',
        'subject_id',
        'book_link',
        'video_link',
        'zip_link',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * True when at least one file is present, so the student pages can skip
     * rows that would render as an empty button.
     */
    public function hasAnyFile(): bool
    {
        return filled($this->book_link) || filled($this->video_link) || filled($this->zip_link);
    }

    /**
     * Only the links that actually have a file, keyed by kind, which is the
     * shape the material list renders.
     *
     * @return array<string, string>
     */
    public function availableLinks(): array
    {
        return array_filter([
            'book' => $this->book_link,
            'video' => $this->video_link,
            'zip' => $this->zip_link,
        ]);
    }
}
