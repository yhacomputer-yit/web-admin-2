import { useEffect, useRef, useState } from "react";

const parse = (value) => {
    const at = Date.parse(value ?? "");
    return Number.isNaN(at) ? null : at;
};

/**
 * A countdown to a deadline the server named, not one the browser worked out.
 *
 * Two things make that necessary:
 *
 *  - skew -- a student's machine can be minutes out, and "01:23:45 remaining"
 *    measured against the wrong clock is worse than no timer at all. So the
 *    server sends both its deadline and its own clock reading (`server_time`),
 *    and the offset between the two is measured once. Everything after that is
 *    local ticks against the corrected clock.
 *
 *  - a tab that was asleep -- timers do not fire in a background tab, so the
 *    remaining time is recalculated from the deadline on every tick rather than
 *    decremented. A laptop waking up an hour later cannot think it still has
 *    time left.
 *
 * The offset is measured on first render and never again: it cannot change while
 * the page is open, and a student who leaves the tab across a deadline is
 * expected to be stopped by the timer reaching zero.
 *
 * @param {string|null} endsAt    ISO timestamp of the deadline, from the server
 * @param {string|null} serverNow ISO timestamp of the server's clock, same moment
 * @returns {{seconds: number, expired: boolean}}
 */
export default function useCountdown(endsAt, serverNow) {
    const [seconds, setSeconds] = useState(() => {
        const end = parse(endsAt);

        return end === null ? 0 : Math.max(0, Math.round((end - Date.now()) / 1000));
    });

    // refs, not state: both are fixed for the life of the page, so changing them
    // must not cause a render of their own
    const end = useRef(parse(endsAt));
    const offset = useRef(parse(serverNow) === null ? 0 : parse(serverNow) - Date.now());

    useEffect(() => {
        end.current = parse(endsAt);
    }, [endsAt]);

    useEffect(() => {
        const tick = () => {
            if (end.current === null) return;

            setSeconds(Math.max(0, Math.round((end.current - (Date.now() + offset.current)) / 1000)));
        };

        tick();

        const id = setInterval(tick, 1000);

        // a tab restored from the background, or a phone waking up, resumes with
        // the deadline re-read rather than with the interval's last tick
        const onVisible = () => {
            if (document.visibilityState === "visible") tick();
        };

        document.addEventListener("visibilitychange", onVisible);

        return () => {
            clearInterval(id);
            document.removeEventListener("visibilitychange", onVisible);
        };
    }, []);

    return { seconds, expired: seconds <= 0 };
}

/**
 * Seconds as "H:MM:SS", or "MM:SS" under an hour.
 *
 * An exam window is long enough that the hours are the part a student actually
 * plans around, so they are never dropped -- but a short window left-padded to
 * hours reads as a three hour exam at a glance.
 */
export function formatCountdown(total) {
    const safe = Math.max(0, Math.floor(total));

    const hours = Math.floor(safe / 3600);
    const minutes = Math.floor((safe % 3600) / 60);
    const seconds = safe % 60;

    const pad = (n) => String(n).padStart(2, "0");

    return hours > 0 ? `${hours}:${pad(minutes)}:${pad(seconds)}` : `${pad(minutes)}:${pad(seconds)}`;
}
