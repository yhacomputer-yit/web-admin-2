import { useRef, useState } from "react";
import { Head, Link, router, usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";
import useCountdown, { formatCountdown } from "../hooks/useCountdown";
import { prettyDate } from "../lib/dates";



const humanSize = (kb) => (kb >= 1024 ? `${Math.round(kb / 1024)} MB` : `${kb} KB`);


function InfoCard({ icon, label, lines, tone = "" }) {
    return (
        <div className={`se-info ${tone}`}>
            <div className="se-info-icon">
                <i className={`fas ${icon}`}></i>
            </div>

            <div className="se-info-body">
                <div className="se-info-label">{label}</div>
                {lines.map((line, i) => (
                    <div className={`se-info-line ${i === 0 ? "is-lead" : ""}`} key={line}>
                        {line}
                    </div>
                ))}
            </div>
        </div>
    );
}

function Timer({ seconds, expired }) {
    // under a minute, then under five: the order matters, because the first
    // test is the tighter of the two and these are exclusive classes
    const critical = !expired && seconds <= 60;
    const urgent = !expired && seconds <= 300;

    const tone = expired ? "is-over" : critical ? "is-critical" : urgent ? "is-urgent" : "";

    return (
        <div className={`se-timer ${tone}`}>
            <div className="se-timer-label">
                <i className="fas fa-hourglass-half"></i>
                {expired ? "Time is over" : "Time remaining"}
            </div>

            <div className="se-timer-clock" role="timer" aria-live={expired ? "assertive" : "off"}>
                {expired ? "00:00:00" : formatCountdown(seconds)}
            </div>
        </div>
    );
}

export default function StudentExamTake({ exam, server_time, accept, max_kb }) {
    const { url, props } = usePage();

    const { seconds, expired } = useCountdown(exam.ends_at, server_time);

    /* The upload has its own, earlier deadline: the paper shuts at end_time, but
       the form shuts SUBMIT_LOCK_MINUTES before it. Both are counted down from
       the server's own timestamps so the page cannot disagree with the endpoint
       that will refuse the upload. */
    const { seconds: submitSeconds, expired: submitExpired } = useCountdown(exam.submit_closes_at, server_time);

    const input = useRef(null);
    const [picked, setPicked] = useState(null);
    const [errors, setErrors] = useState({});
    const [sending, setSending] = useState(false);

    const { flash } = props;

    const showPaper = Boolean(exam.paper_url) && !expired;

    /* the server's own reading on arrival, then the local clock. The pair matters
       at the boundary: a page rendered one second inside the window arrives with
       submit_open false and must not offer a form the endpoint would refuse. */
    const submitClosed = !exam.submit_open || submitExpired;

    /* the upload closes SUBMIT_LOCK_MINUTES before the window, so the countdown
       reaching that many minutes means the closing stretch has begun: the form
       is still there, but it says how long is left to use it. Left alone, the
       strip would sit on screen for the whole sitting with an hour on it. */
    const submitClosingSoon = !submitClosed && submitSeconds <= (exam.submit_lock_minutes ?? 15) * 60;

    const submit = (e) => {
        e.preventDefault();

        if (!picked || sending || submitClosed) return;

        setErrors({});

        const form = new FormData();
        form.append("file", picked);

        setSending(true);

        router.post(`/student-portal/exam/${exam.id}/submit`, form, {
            forceFormData: true,
            onError: (bag) => {
                setErrors(bag);
                setSending(false);
            },
            onFinish: () => setSending(false),
        });
    };

    return (
        <StudentLayout active="exams" title="Exam" key={url}>
            <Head title={`${exam.subject_name} - Exam`} />

            <div className="se-page">
                {flash?.error && (
                    <div className="se-flash se-flash-bad" role="alert">
                        <i className="fas fa-circle-exclamation"></i> {flash.error}
                    </div>
                )}

                {/* band 1: what this exam is, and the clock it is running on */}
                <div className="se-take-head">
                    <InfoCard
                        icon="fa-graduation-cap"
                        label="Course &amp; Subject"
                        lines={[exam.subject_name, exam.course_name || "—"]}
                    />

                    <InfoCard
                        icon="fa-calendar-clock"
                        label="Exam Date &amp; Time"
                        lines={[prettyDate(exam.date), exam.time_label || "—"]}
                        tone="is-time"
                    />

                    <Timer seconds={seconds} expired={expired} />
                </div>

                {/* band 2: the paper */}
                <section className="se-paper" aria-label="Exam paper">
                    <div className="se-paper-bar">
                        <span className="se-paper-name">
                            <i className="fas fa-file-pdf"></i>
                            {exam.subject_name} question paper
                        </span>

                        {showPaper && (
                            <span className="se-paper-links">
                                <a href={exam.paper_url} target="_blank" rel="noopener">
                                    <i className="fas fa-up-right-from-square"></i> Open in a new tab
                                </a>
                            </span>
                        )}
                    </div>

                    {showPaper ? (
                        <iframe
                            key={exam.paper_url}
                            className="se-paper-frame"
                            src={exam.paper_url}
                            title={`${exam.subject_name} question paper`}
                        />
                    ) : (
                        <div className="se-paper-gone">
                            <i className={`fas ${expired ? "fa-lock" : "fa-file-circle-question"}`}></i>
                            <div className="se-paper-gone-title">
                                {expired ? "The paper is closed" : "No paper has been uploaded"}
                            </div>
                            <div className="se-paper-gone-sub">
                                {expired
                                    ? "The exam window closed, so the paper is no longer available."
                                    : "Ask your teacher to upload the question paper for this exam."}
                            </div>
                        </div>
                    )}
                </section>

                {/* band 3: the script */}
                <section className="se-submit" aria-label="Submit your answer">
                    <div className="se-submit-head">
                        <h2 className="se-submit-title">
                            <i className="fas fa-cloud-arrow-up"></i>
                            Upload your answer
                        </h2>

                        {exam.submitted && (
                            <span className="se-badge se-badge-done">
                                <i className="fas fa-circle-check"></i> Answered
                            </span>
                        )}
                    </div>

                    {/* what the panel says and nothing else: the student came to check that their
                        script is in, and the moment it was accepted answers that. A
                        filename and a note about replacing it turn that into a file
                        manager on a page whose job is submitting one. */}
                    {exam.submitted && (
                        <div className="se-current">
                            <i className="fas fa-circle-check"></i>
                            <div className="se-current-head">
                                Answer submitted{exam.submitted_label ? ` on ${exam.submitted_label}` : ""}
                            </div>
                        </div>
                    )}

                    {submitClosingSoon && (
                        <div className="se-closing" role="status">
                            <i className="fas fa-triangle-exclamation"></i>
                            <span>
                                Uploads close in <strong>{formatCountdown(submitSeconds)}</strong> — submit your
                                script before {exam.submit_closes_label || "the exam closes"}.
                            </span>
                        </div>
                    )}

                    {submitClosed ? (
                        /* one dark box for both deadlines. The paper closing and the
                           upload closing are the same thing to a student who has
                           just lost the ability to hand anything in, and they land
                           in the same place on the page. */
                        <div className="se-dark" role="alert">
                            <div className="se-dark-icon">
                                <i className={`fas ${expired ? "fa-door-closed" : "fa-lock"}`}></i>
                            </div>

                            <div className="se-dark-body">
                                <div className="se-dark-title">
                                    {expired
                                        ? "Exam time is over — you cannot enter this exam"
                                        : "Submissions are closed"}
                                </div>

                                <div className="se-dark-sub">
                                    {expired ? (
                                        <>
                                            The window closed at {exam.end_time || "the scheduled time"}. The question
                                            paper is no longer available and nothing can be uploaded.
                                        </>
                                    ) : (
                                        <>
                                            Uploads closed at {exam.submit_closes_label || "the scheduled time"}{" "}
                                            {exam.submit_lock_minutes ?? 15} minutes before the exam ends. You can
                                            still read the paper until {exam.end_time || "the scheduled time"}.
                                        </>
                                    )}
                                </div>
                            </div>
                        </div>
                    ) : (
                        <form onSubmit={submit} className="se-drop">
                            {/* a real file input rather than a styled drop zone
                                that fakes one: a student on a phone gets the
                                native picker, and one who drags a file onto the
                                box gets the same thing. Kept to one compact row:
                                the questions above are what the page is for, and
                                the upload is a step at the end of them. */}
                            <label className="se-drop-label" htmlFor="file">
                                <i className="fas fa-cloud-arrow-up"></i>
                                <span className="se-drop-title">Choose your answer script</span>
                                <span className="se-drop-hint">
                                    PDF, JPG, PNG or WEBP · up to {humanSize(max_kb)}
                                </span>
                            </label>

                            <input
                                ref={input}
                                id="file"
                                type="file"
                                name="file"
                                accept={accept}
                                required
                                className="visually-hidden-input"
                                onChange={(e) => {
                                    setPicked(e.target.files?.[0] ?? null);
                                    setErrors({});
                                }}
                            />

                            {picked && (
                                <div className="se-picked">
                                    <i className="fas fa-file"></i>
                                    <span className="se-picked-name">{picked.name}</span>
                                    <span className="se-picked-size">{humanSize(Math.ceil(picked.size / 1024))}</span>
                                    <button
                                        type="button"
                                        className="se-picked-clear"
                                        onClick={() => {
                                            if (input.current) input.current.value = "";
                                            setPicked(null);
                                        }}
                                        aria-label="Remove the chosen file"
                                    >
                                        <i className="fas fa-xmark"></i>
                                    </button>
                                </div>
                            )}

                            {errors.file && <div className="se-error">{errors.file}</div>}

                            <div className="se-actions">
                                <Link href="/student-portal/exam" className="se-btn is-quiet">
                                    Cancel
                                </Link>
                                <button type="submit" className="se-btn" disabled={!picked || sending}>
                                    <i className="fas fa-paper-plane"></i>
                                    {sending
                                        ? "Uploading…"
                                        : exam.submitted
                                          ? "Replace answer"
                                          : "Submit answer"}
                                </button>
                            </div>
                        </form>
                    )}
                </section>

                {/* the way out sits under everything else rather than above it: a
                    student reading the paper has not finished with the page, and
                    a link at the top invites a click before the reading starts */}
                <div className="se-foot">
                    <Link href="/student-portal/exam" className="sa-breadcrumb">
                        <i className="fas fa-chevron-left"></i> Back to exams
                    </Link>

                </div>
            </div>
        </StudentLayout>
    );
}
