/**
 * Dates in the portal, in the one shape the pages print them.
 *
 * The server sends dates as "Y-m-d" and times as "H:i" rather than as a
 * preformatted label, so that a page can lay them out wherever it likes and a
 * change to the wording does not need a migration of stored strings. The two
 * exam pages both need the same two things out of those values -- a day badge
 * split into a number and a month, and a readable line -- so they are answered
 * here once.
 */

export const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

/**
 * A "Y-m-d" string split for display, or null when there is nothing to show.
 *
 * Returned as parts rather than as a string because the list shows the day and
 * month on their own lines under a badge, while the sitting page shows the whole
 * date on one line.
 */
export function dateParts(value) {
    if (!value) return null;

    const [year, month, day] = String(value).split("-");
    if (!year || !month || !day) return null;

    return { day, month: MONTHS[Number(month) - 1] || month, year };
}

/**
 * "04 Oct 2026", or an em dash when the date is missing.
 */
export function prettyDate(value) {
    const date = dateParts(value);

    return date ? `${date.day} ${date.month} ${date.year}` : "—";
}
