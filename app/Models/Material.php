<?php

namespace App\Models;

use App\Support\StoredFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One file a course teaches under one subject.
 *
 * A subject can hold any number of these: several books, several recordings of
 * the same lecture, several exercise archives. That is why the row is keyed by
 * the file and not by the course + subject pair - the pair used to be unique and
 * capped a subject at one file per kind.
 *
 * `file_link` is a path on the `public` disk rather than a URL, so a file is
 * served straight from storage and replacing it never needs a third party to
 * keep working. `title` is the name students and admins see; when it is empty
 * the stored filename is used instead, so no row ever renders as untitled.
 *
 * @property int         $id
 * @property int         $course_id
 * @property int         $subject_id
 * @property string      $type      book | video | zip
 * @property string|null $title     display name, falls back to the filename
 * @property string|null $file_link path on the `public` disk
 * @property string|null $remark    free-text admin note about this file
 */
class Material extends Model
{
    use HasFactory;

    /**
     * The file kinds a row can hold, and everything that varies between them:
     * what the kind is called, how the admin list draws it, what the browser
     * accepts for it and what the validator allows.
     *
     * One map rather than four so a new kind is a single entry and the form, the
     * list and the validation can never disagree about what a book is.
     *
     * The per-file ceilings match what a browser and PHP will realistically
     * accept; a lecture recording is the only kind big enough to need a raised
     * upload_max_filesize, and the form says so next to the field.
     */
    public const KINDS = [
        'book' => [
            'label' => 'Book',
            'form_label' => 'Book (PDF)',
            'icon' => 'bx bx-book',
            'class' => 'text-danger',
            'accept' => '.pdf,application/pdf',
            'hint' => 'PDF, up to 20 MB. The file is attached to the subject, so it appears on the student\'s course page as soon as you save.',
            'mimes' => 'mimes:pdf',
            'max' => 20480,
            'mimes_message' => 'The book must be a PDF.',
            'max_message' => 'The book may not be larger than 20 MB.',
        ],
        'video' => [
            'label' => 'Video',
            'form_label' => 'Lecture recording',
            'icon' => 'bx bx-play-circle',
            'class' => 'text-primary',
            'accept' => 'video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov,.m4v,.ogg',
            'hint' => 'MP4, WebM, MOV, M4V or OGG, up to 500 MB. Large uploads need a raised upload_max_filesize on the server, and the upload does not start until you save.',
            'mimes' => 'mimes:mp4,webm,mov,m4v,ogg',
            'max' => 512000,
            'mimes_message' => 'The lecture recording must be an MP4, WebM, MOV, M4V or OGG file.',
            'max_message' => 'The lecture recording may not be larger than 500 MB.',
        ],
        'zip' => [
            'label' => 'ZIP',
            'form_label' => 'Archive (ZIP)',
            'icon' => 'bx bx-archive',
            'class' => 'text-warning',
            'accept' => '.zip,application/zip,application/x-zip-compressed',
            'hint' => 'ZIP, up to 100 MB. On an edit, leaving the file field empty keeps the archive that is already attached.',
            'mimes' => 'mimes:zip',
            'max' => 102400,
            'mimes_message' => 'The archive must be a ZIP file.',
            'max_message' => 'The archive may not be larger than 100 MB.',
        ],
    ];

    /**
     * Only these may end up in a stored filename. The mimes rule already keeps
     * uploads to these kinds, and repeating the allow-list at save time means a
     * crafted original name can never choose the extension itself.
     */
    public const EXTENSIONS = ['pdf', 'mp4', 'webm', 'mov', 'm4v', 'ogg', 'zip'];

    // The table is `reference`, not the model's own plural; see
    // 2026_10_03_010000_consolidate_materials_into_reference
    protected $table = 'reference';

    protected $fillable = [
        'course_id',
        'subject_id',
        'type',
        'title',
        'file_link',
        'remark',
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
     * What this kind is called, falling back to "File" for a row saved with a
     * kind the map does not know.
     */
    public function typeLabel(): string
    {
        return static::KINDS[$this->type]['label'] ?? 'File';
    }

    /**
     * The name to show: the admin's title when there is one, otherwise the
     * stored filename, so a row uploaded without a title is still readable.
     */
    public function displayTitle(): string
    {
        return filled($this->title)
            ? $this->title
            : (StoredFile::label($this->file_link) ?? 'Untitled');
    }

    /**
     * A ready URL, so no page has to rebuild a storage path.
     */
    public function url(): ?string
    {
        return StoredFile::url($this->file_link);
    }
}