<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Formatting for a `time` column, which is a time of day with no date on it.
 *
 * Several tables hold a plain time (`sections.start` / `sections.end`,
 * `exam_questions.start_time` / `end_time`) and every page that shows one wants
 * the same two answers: the hour and minute to print, and null when the value is
 * unusable. Eloquent hands the column back as a "H:i:s" string, so formatting is
 * a parse that can fail, and a malformed value must not take a page down with it.
 *
 * The same question is asked in several controllers, which is why the answer
 * lives here rather than being copied into each of them.
 */
final class TimeOfDay
{
    /**
     * "H:i" for a stored time, or null when there is nothing printable.
     *
     * Anything unparseable falls back to null rather than throwing, because these
     * columns are free-form enough that a stray value should cost one label, not
     * the whole list.
     */
    public static function format(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
