<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * A certificate issued to a student, and whether they have collected it yet.
 *
 * The row exists from the moment the certificate is issued, so `remark` starts on
 * "not received" rather than on something an admin has to type. Issuing and handing
 * over are two separate events at a real school, and recording the first one must
 * not imply the second.
 *
 * `remark` is a varchar rather than a boolean because the office also wants to
 * write on it - "collected by his brother", "left with the office" - without a
 * second column. STATUSES keeps that from becoming free text: the handover state
 * is read as a value out of the two, anything else is a note that happens to sit
 * in the same column.
 *
 * @property int         $id
 * @property int         $student_id
 * @property Carbon|null $complete_date
 * @property string      $remark
 * @property-read Student $student
 */
class Certificate extends Model
{
    use HasFactory;

    public const RECEIVED = 'received';
    public const NOT_RECEIVED = 'not received';

    /** the handover states, and how the office names them */
    public const STATUSES = [
        self::RECEIVED => 'Received',
        self::NOT_RECEIVED => 'Not received',
    ];

    protected $fillable = [
        'student_id',
        'complete_date',
        'certificate_file',
        'remark',
    ];

    protected $attributes = [
        'remark' => self::NOT_RECEIVED,
    ];

    protected $casts = [
        'complete_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Only certificates that have actually been handed over.
     */
    public function scopeReceived(Builder $query): Builder
    {
        return $query->where('remark', self::RECEIVED);
    }

    /**
     * Whether this certificate has been collected.
     *
     * Compares against the two known states rather than asking whether the column
     * is filled in, so a note typed into `remark` counts as not received instead
     * of quietly reading as handed over.
     */
    public function isReceived(): bool
    {
        return $this->remark === self::RECEIVED;
    }

    /**
     * What to show for the handover state: the state's name when it is one of the
     * two, and the raw text when it is a note.
     */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->remark] ?? (string) $this->remark;
    }

    /**
     * The date to prefill `complete_date` with: the latest enrollment of this
     * student that has actually finished.
     *
     * Null when nothing has finished, which is the honest answer - the form then
     * shows an empty date rather than borrowing the day they enrolled. A completed
     * enrollment and the certificate are two separate records that can disagree,
     * so this only ever fills the input in; it never writes to the enrollment.
     */
    public static function completionDateFor(Student $student): ?string
    {
        $completed = StudentEnrollment::query()
            ->where('student_id', $student->id)
            ->whereNotNull('complete_date')
            ->orderByDesc('complete_date')
            ->orderByDesc('id')
            ->value('complete_date');

        return $completed ? Carbon::parse($completed)->toDateString() : null;
    }
}