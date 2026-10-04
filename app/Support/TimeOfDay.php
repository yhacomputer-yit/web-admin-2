<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Formatting for a `time` column, which is a time of day with no date on it.
 *
 * Several tables hold a plain time (`sections.start` / `sections.end`,
 * `exam_questions.start_time` / `end_time`) and every page that shows one wants
 * the same answers: the time to print, the value an input field needs, and null
 * when the stored value is unusable. Eloquent hands the column back as a "H:i:s"
 * string, so formatting is a parse that can fail, and a malformed value must not
 * take a page down with it.
 *
 * The two answers are different formats on purpose. Times are *shown* in 12-hour
 * form, because that is how the school reads and says them, but an
 * `<input type="time">` only accepts a 24-hour value, so a form asking for one
 * has to ask for `value()` or the browser will silently refuse what it is given.
 *
 * The same questions are asked in several controllers and views, which is why the
 * answers live here rather than being copied into each of them.
 */
final class TimeOfDay
{
    /**
     * "9:05 AM" — what a student and an admin read.
     *
     * The meridiem is spelled out and never dropped: "9:05" next to "11:05" on a
     * card grid is ambiguous in a way that "9:05 AM" is not, and an exam that
     * opens at nine in the morning is not the same one that opens at nine at night.
     */
    public static function format(mixed $value): ?string
    {
        return self::render($value, 'g:i A');
    }

    /**
     * "09:05" — what an `<input type="time">` needs.
     *
     * The value is always 24-hour with a leading zero, because that is the only
     * shape the input accepts; a browser handed "9:05 AM" empties the field.
     */
    public static function value(mixed $value): ?string
    {
        return self::render($value, 'H:i');
    }

    /**
     * One parse, one format, null on anything unusable.
     *
     * Anything unparseable falls back to null rather than throwing, because these
     * columns are free-form enough that a stray value should cost one label, not
     * the whole list.
     */
    private static function render(mixed $value, string $pattern): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format($pattern);
        } catch (\Throwable $e) {
            return null;
        }
    }
}