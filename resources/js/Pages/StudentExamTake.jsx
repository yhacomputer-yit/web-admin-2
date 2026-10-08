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


function GracePeriod({ seconds, expired }) {
    const urgent = !expired && seconds <= 300;
    const critical = !expired && seconds <= 60;

    const tone = critical ? "is-critical" : urgent ? "is-urgent" : "";

    return (
        <div className={`se-grace ${tone}`} role="alert">
            <div className="se-grace-icon">
                <i className="fas fa-hourglass-end"></i>
            </div>

            <div className="se-grace-body">
                <div className="se-grace-title">Exam time has ended</div>
                <div className="se-grace-sub">
                    Please click the Submit button below to upload your answers.
                </div>
                <div className="se-grace-clock" role="timer" aria-live="off">
                    <span className="se-grace-label">Submission Time Remaining</span>
                    <span className="se-grace-time">
                        {expired ? "00:00" : formatCountdown(seconds)}
                    </span>
                </div>
            </div>
        </div>
    );
}

export default function StudentExamTake({ exam, server_time, accept, max_kb }) {
    const { url, props } = usePage();


    const { seconds, expired } = useCountdown(exam.ends_at, server_time);
    const submission = useCountdown(exam.submit_closes_at, server_time);

    const questionsOpen = !expired;

    const input = useRef(null);
    const [picked, setPicked] = useState(null);
    const [errors, setErrors] = useState({});
    const [sending, setSending] = useState(false);

    const { flash } = props;

    const showPaper = Boolean(exam.paper_url) && questionsOpen;

    const submitClosed = !exam.submit_open || submission.expired;

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
            <Head>
                <title>{exam?.subject_name || 'Exam'} - Student Portal - YHA ACADEMY OF TECHNOLOGY</title>
                <meta name="description" content={`Take the ${exam?.subject_name || 'exam'} for ${exam?.course_name || 'your course'} at YHA ACADEMY OF TECHNOLOGY Student Portal.`} />
                <meta name="keywords" content="exam, student portal, YHA ACADEMY OF TECHNOLOGY, online exam, test, assessment" />
                <meta property="og:title" content={`${exam?.subject_name || 'Exam'} - YHA ACADEMY OF TECHNOLOGY`} />
                <meta property="og:description" content={`Take the ${exam?.subject_name || 'exam'} at YHA ACADEMY OF TECHNOLOGY Student Portal.`} />
                <meta property="og:image" content="/image/logo/logo.png" />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content="YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content={`${exam?.subject_name || 'Exam'} - YHA ACADEMY OF TECHNOLOGY`} />
                <meta name="twitter:description" content={`Take the ${exam?.subject_name || 'exam'} at YHA ACADEMY OF TECHNOLOGY Student Portal.`} />
                <meta name="twitter:image" content="/image/logo/logo.png" />
                <link rel="canonical" href={window.location.href} />
            </Head>

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

                <section className="se-paper" aria-label="Exam paper">
                    {questionsOpen ? (
                        <>
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
                                    <i className="fas fa-file-circle-question"></i>
                                    <div className="se-paper-gone-title">
                                        No paper has been uploaded
                                    </div>
                                    <div className="se-paper-gone-sub">
                                        Ask your teacher to upload the question paper for this exam.
                                    </div>
                                </div>
                            )}
                        </>
                    ) : (
                        <GracePeriod seconds={submission.seconds} expired={submission.expired} />
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
                    {exam.submitted && (
                        <div className="se-current">
                            <i className="fas fa-circle-check"></i>
                            <div className="se-current-head">
                                Answer submitted{exam.submitted_label ? ` on ${exam.submitted_label}` : ""}
                            </div>
                        </div>
                    )}

                    {submitClosed ? (

                        <div className="se-dark" role="alert">
                            <div className="se-dark-icon">
                                <i className="fas fa-door-closed"></i>
                            </div>

                            <div className="se-dark-body">
                                <div className="se-dark-title">Time is over — this exam is closed</div>

                                <div className="se-dark-sub">
                                    The exam ran from {exam.start_time || "the scheduled time"}{" "}
                                    to {exam.end_time || "the scheduled time"}. The paper and the
                                    upload are both closed, and nothing more can be submitted.
                                </div>
                            </div>
                        </div>
                    ) : (
                        <form onSubmit={submit} className="se-drop">
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

               
                <div className="se-foot">
                    <Link href="/student-portal/exam" className="sa-breadcrumb">
                        <i className="fas fa-chevron-left"></i> Back to exams
                    </Link>

                </div>
            </div>
        </StudentLayout>
    );
}
