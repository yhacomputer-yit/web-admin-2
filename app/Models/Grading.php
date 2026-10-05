<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A grade band: a name and the ceiling of the marks that fall in it.
 *
 * `score` is the top of the band, not a mark of its own, so A carries 100 and D
 * carries 50. That makes the bands a lookup table an admin sets up once and then
 * picks from per result, which is why GradingResult::grade_id exists at all.
 *
 * @property int    $id
 * @property string $name
 * @property string $score
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GradingResult> $results
 */
class Grading extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    /**
     * The marks that have been filed under this band.
     */
    public function results()
    {
        return $this->hasMany(GradingResult::class, 'grade_id');
    }

    /**
     * The ceiling as it should be shown, with no trailing zeros: 100 rather than
     * 100.00, and 99.5 rather than 99.50.
     */
    public function scoreLabel(): string
    {
        return rtrim(rtrim((string) $this->score, '0'), '.');
    }

    /**
     * "A (up to 100)" - the band and its ceiling, for a select an admin reads.
     */
    public function label(): string
    {
        return $this->name . ' (up to ' . $this->scoreLabel() . ')';
    }
}