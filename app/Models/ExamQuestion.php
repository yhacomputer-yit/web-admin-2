<?php

namespace App\Models;

use App\Support\StoredFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * One exam sitting: a course + subject, a day, and the window inside that day.
 *
 * `start_time` / `end_time` are plain time-of-day values, so they only mean
 * anything next to `exam_date`. They are left as strings rather than cast, for
 * the same reason the section start/end are: a `time` column carries no date and
 * no timezone, and turning it into a Carbon would have to invent both.
 *
 * `is_published` is a varchar flag rather than a boolean so the column can grow
 * into a richer state later (draft / published / closed) without a migration.
 * Only `published` is something a student may see, which is what scopePublished
 * is for. Anything else -- including the `unpublish` an admin form may write --
 * is invisible to the portal by construction: there is no code path that loads a
 * sitting without going through the scope.
 *
 * `question_file` is the paper, and unlike an answer script it is deliberately
 * not a public URL. It lives on the private `local` disk and is only ever
 * reached through the paper endpoint, which re-checks publication, enrollment
 * and the window on every request -- that is what makes "no PDF after the end
 * time" true for a student who kept the tab open or bookmarked the file.
 *
 * @property int         $id
 * @property int         $course_id
 * @property int         $subject_id
 * @property string|null $start_time  "H:i:s"
 * @property string|null $end_time    "H:i:s"
 * @property Carbon|null $exam_date
 * @property string      $is_published
 * @property string|null $question_file  path on PAPER_DISK
 */
class ExamQuestion extends Model
{
    use HasFactory;

    public const PUBLISHED = 'published';

    /** the states the flag can hold, and what an admin list would call them */
    public const STATUSES = [
        'draft' => 'Draft',
        self::PUBLISHED => 'Published',
        'closed' => 'Closed',
    ];

    /* what a sitting is right now, as the portal reads it */
    public const UPCOMING = 'upcoming';
    public const ONGOING = 'ongoing';
    public const FINISHED = 'finished';

    public const SCHEDULE_STATUSES = [
        self::UPCOMING => 'Upcoming',
        self::ONGOING => 'In progress',
        self::FINISHED => 'Finished',
    ];

    /**
     * The disk a paper is kept on.
     *
     * `local` rather than `public` is the whole point: a paper on the public
     * disk has a URL anyone can guess, and the time gate on this model would
     * only be advisory. Kept on the private disk the paper has no URL at all --
     * it can only be reached through a controller that checks the clock.
     */
    public const PAPER_DISK = 'local';

    /** a paper is a PDF: nothing a browser cannot render natively */
    public const PAPER_ACCEPTED = ['pdf'];

    /** 20 MB, the same ceiling as an answer script and the course materials */
    public const PAPER_MAX_KILOBYTES = 20480;

    /**
     * How long before the window shuts the upload stops.
     *
     * The paper stays readable until `end_time`, but a script has to be in before
     * the marking starts, so the last stretch of a sitting is for a student
     * reading their own answers rather than for uploading them. Two deadlines
     * rather than one, both of them the same instant to everyone.
     */
    public const SUBMIT_LOCK_MINUTES = 15;

    /** where papers are filed on the private disk */
    public const PAPER_DIRECTORY = 'exam/questions';

    protected $fillable = [
        'course_id',
        'subject_id',
        'start_time',
        'end_time',
        'exam_date',
        'is_published',
        'question_file',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Sittings a student is allowed to see. A draft or a closed sitting is an
     * admin's working state, never a student's.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', self::PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->is_published === self::PUBLISHED;
    }

    /**
     * When the sitting opens, or null when it has no usable window.
     */
    public function startsAt(): ?Carbon
    {
        return $this->atTime($this->start_time);
    }

    /**
     * When the sitting closes, or null when it has no usable window.
     */
    public function endsAt(): ?Carbon
    {
        return $this->atTime($this->end_time);
    }

    /**
     * Whether the sitting is still ahead, running, or over.
     *
     * `start_time` and `end_time` are NOT NULL time columns, so a window always
     * exists in practice. The fallback only matters if a value cannot be read as
     * a time at all, and then the day itself is the least-wrong answer: a sitting
     * is treated as running until midnight rather than being shown as already
     * over, because "finished" hides it and "upcoming" sends a student to an
     * exam that has already run.
     */
    public function scheduleStatus(?Carbon $now = null): string
    {
        $now ??= now();

        $start = $this->startsAt() ?? $this->exam_date?->copy()->startOfDay();
        $end = $this->endsAt() ?? $this->exam_date?->copy()->endOfDay();

        if ($start === null) {
            return self::FINISHED;
        }

        if ($now->lt($start)) {
            return self::UPCOMING;
        }

        return $end !== null && $now->gt($end) ? self::FINISHED : self::ONGOING;
    }

    /**
     * Whether a student may sit this paper right now.
     *
     * One question with three answers: the paper, the submission and the
     * countdown all stop at the same instant, so they all ask this.
     */
    public function isOpen(?Carbon $now = null): bool
    {
        return $this->scheduleStatus($now) === self::ONGOING;
    }

    /**
     * When the upload stops, which is before the paper does.
     *
     * Null when the window cannot be read, in which case there is nothing to
     * submit into anyway and the upload is closed.
     */
    public function submissionClosesAt(): ?Carbon
    {
        return $this->endsAt()?->copy()->subMinutes(self::SUBMIT_LOCK_MINUTES);
    }

    /**
     * Whether a script can still be handed in.
     *
     * Deliberately not the same question as isOpen(): a student in the last
     * fifteen minutes may still read the paper, and must still be able to leave
     * the page, but the server will not take a script off them. Checked on the
     * submission itself rather than only on the page, because the page is only a
     * page.
     */
    public function isSubmitOpen(?Carbon $now = null): bool
    {
        $now ??= now();

        if (! $this->isOpen($now)) {
            return false;
        }

        $closes = $this->submissionClosesAt();

        return $closes === null || $now->lt($closes);
    }

    /**
     * Whole seconds left before the upload stops, never negative.
     */
    public function secondsUntilSubmitCloses(?Carbon $now = null): int
    {
        $closes = $this->submissionClosesAt();

        if ($closes === null) {
            return 0;
        }

        return max(0, ($now ?? now())->diffInSeconds($closes, false));
    }

    /**
     * Whole seconds left before the window shuts, never negative.
     *
     * The page's countdown is this number subtracted once and then ticked down
     * locally, so a slow tab cannot make the paper close later than it should.
     */
    public function secondsRemaining(?Carbon $now = null): int
    {
        $end = $this->endsAt();

        if ($end === null) {
            return 0;
        }

        return max(0, ($now ?? now())->diffInSeconds($end, false));
    }

    /**
     * Whether a paper is recorded and still behind its path.
     *
     * A row can outlive its upload, so the path being filled in is not the same
     * as the file being readable, and the viewer has to be able to tell.
     */
    public function hasPaper(): bool
    {
        return StoredFile::exists($this->question_file, self::PAPER_DISK);
    }

    /**
     * The paper's name, for the viewer toolbar.
     */
    public function paperLabel(): ?string
    {
        return StoredFile::label($this->question_file);
    }

    /**
     * The absolute path a paper is streamed from, or null when there is no
     * readable file to stream.
     */
    public function paperPath(): ?string
    {
        if (blank($this->question_file)) {
            return null;
        }

        return Storage::disk(self::PAPER_DISK)->path($this->question_file);
    }

    /**
     * The time-of-day column lifted onto the sitting's date.
     *
     * Both columns are free-form strings in practice, so anything unparseable
     * gives null rather than throwing on a page load.
     */
    private function atTime(?string $time): ?Carbon
    {
        if (blank($time) || $this->exam_date === null) {
            return null;
        }

        try {
            return Carbon::parse($this->exam_date->toDateString() . ' ' . $time);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
