import { useEffect, useMemo, useRef, useState } from "react";
import { Link, router, usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";
import { dateParts, prettyDate } from "../lib/dates";


const blockedMessage = (exam) =>
    exam.status === "upcoming"
        ? {
              title: "Exam time has not started yet",
              body: `This exam opens at ${exam.start_time || "the scheduled time"} on ${prettyDate(exam.date)}.`,
          }
        : {
              title: "Exam time is over",
              body: `This exam closed at ${exam.end_time || "the scheduled time"} on ${prettyDate(exam.date)}.`,
          };

function BlockedDialog({ exam, onClose }) {
    const closeRef = useRef(null);
    const restoreRef = useRef(null);
    const message = blockedMessage(exam);

    useEffect(() => {
        restoreRef.current = document.activeElement;
        closeRef.current?.focus();

        const onKey = (e) => {
            if (e.key === "Escape") onClose();
        };

        document.addEventListener("keydown", onKey);
        document.body.classList.add("sl-no-scroll");

        return () => {
            document.removeEventListener("keydown", onKey);
            document.body.classList.remove("sl-no-scroll");
            restoreRef.current?.focus?.();
        };
    }, [onClose]);

    return (
        <div className="se-modal" role="presentation" onMouseDown={(e) => e.target === e.currentTarget && onClose()}>
            <div className="se-modal-box" role="dialog" aria-modal="true" aria-labelledby="se-modal-title">
                <div className={`se-modal-icon is-${exam.status}`}>
                    <i className={`fas ${exam.status === "upcoming" ? "fa-clock" : "fa-lock"}`}></i>
                </div>

                <h2 className="se-modal-title" id="se-modal-title">
                    {message.title}
                </h2>

                <p className="se-modal-text">{message.body}</p>

                <dl className="se-modal-facts">
                    <div>
                        <dt>Exam</dt>
                        <dd>{exam.subject_name}</dd>
                    </div>
                    <div>
                        <dt>Date</dt>
                        <dd>{prettyDate(exam.date)}</dd>
                    </div>
                    <div>
                        <dt>Time</dt>
                        <dd>{exam.time_label || "—"}</dd>
                    </div>
                </dl>

                <div className="se-modal-actions">
                    <button type="button" className="se-btn" ref={closeRef} onClick={onClose}>
                        Got it
                    </button>
                </div>
            </div>
        </div>
    );
}


function ExamCard({ exam, onBlocked }) {
    const date = dateParts(exam.date);

    return (
        <article className={`se-card se-card-${exam.status} ${exam.can_open ? "is-open" : "is-locked"}`}>
            <div className="se-card-top">
                <div className="se-date" aria-hidden="true">
                    <span className="se-date-day">{date?.day ?? "--"}</span>
                    <span className="se-date-mon">{date?.month ?? "—"}</span>
                </div>

                <div className="se-card-head">
                    <h3 className="se-card-title">{exam.subject_name}</h3>
                    <div className="se-card-course">
                        <i className="fas fa-book-open"></i>
                        {exam.course_name || "—"}
                    </div>
                </div>

                {/* the answer state is the one thing on the card a student came
                    here to check, so it keeps its own column on the right */}
                <div className="se-status">
                    {exam.submitted ? (
                        <span className="se-status-pill is-done">
                            <i className="fas fa-circle-check"></i> Answered
                        </span>
                    ) : (
                        <span className="se-status-pill is-todo">
                            <i className="fas fa-circle-xmark"></i> Not answered
                        </span>
                    )}
                </div>
            </div>

            <div className="se-card-meta">
                <span className="se-meta-item">
                    <i className="fas fa-clock"></i>
                    {exam.time_label || "—"}
                </span>
                <span className="se-meta-item">
                    <i className="fas fa-calendar"></i>
                    {prettyDate(exam.date)}
                </span>
            </div>

            {exam.can_open ? (
                <Link
                    href={`/student-portal/exam/${exam.id}`}
                    className="se-stretch"
                    aria-label={`Open the ${exam.subject_name} exam`}
                />
            ) : (
                <button
                    type="button"
                    className="se-stretch"
                    onClick={() => onBlocked(exam)}
                    aria-label={`Why can I not open the ${exam.subject_name} exam?`}
                />
            )}
        </article>
    );
}

function FilterSelect({ label, value, options, onChange, allLabel }) {
    return (
        <label className="se-filter">
            <span className="se-filter-label">{label}</span>
            <select
                className="se-filter-select"
                value={value ?? ""}
                onChange={(e) => onChange(e.target.value)}
            >
                <option value="">{allLabel}</option>
                {options.map((option) => (
                    <option key={option.id} value={option.id}>
                        {option.name}
                    </option>
                ))}
            </select>
        </label>
    );
}

export default function StudentExams({ groups, filters, options, summary }) {
    const { url, flash } = usePage();

    const [blocked, setBlocked] = useState(null);
    const counts = summary ?? {};

    /* the server sends the sittings grouped by subject; the grid is flat, so the
       grouping is dropped here rather than rendered as a section per subject */
    const exams = useMemo(() => (groups ?? []).flatMap((subject) => subject.exams ?? []), [groups]);

    const filterTo = (next) => {
        router.get(
            "/student-portal/exam",
            Object.fromEntries(Object.entries(next).filter(([, v]) => v)),
            { preserveState: true, preserveScroll: true, replace: true }
        );
    };

    const filtered = Boolean(filters?.course_id || filters?.subject_id);

    return (
        <StudentLayout active="exams" title="Exam" key={url}>
            <div className="se-page">
                {flash?.success && (
                    <div className="se-flash se-flash-ok" role="status">
                        <i className="fas fa-circle-check"></i> {flash.success}
                    </div>
                )}

                {flash?.error && (
                    <div className="se-flash se-flash-bad" role="alert">
                        <i className="fas fa-circle-exclamation"></i> {flash.error}
                    </div>
                )}

                {(options?.courses?.length > 0) && (
                    <div className="se-filters">
                        <FilterSelect
                            label="Course"
                            value={filters?.course_id}
                            options={options.courses}
                            allLabel="All courses"
                            onChange={(value) => filterTo({ course_id: value, subject_id: "" })}
                        />

                        <FilterSelect
                            label="Subject"
                            value={filters?.subject_id}
                            options={options.subjects}
                            allLabel="All subjects"
                            onChange={(value) => filterTo({ course_id: filters?.course_id ?? "", subject_id: value })}
                        />

                        <div className="se-filters-count">
                            {filtered ? `${counts.total ?? 0} shown` : `${counts.total ?? 0} exam${counts.total === 1 ? "" : "s"}`}
                        </div>

                        {filtered && (
                            <Link href="/student-portal/exam" className="se-btn is-quiet" scroll={false}>
                                <i className="fas fa-xmark"></i>
                                Clear
                            </Link>
                        )}
                    </div>
                )}

                {exams.length === 0 ? (
                    <div className="stu-empty">
                        <div className="stu-empty-icon"><i className="fas fa-clipboard-list"></i></div>
                        <div className="stu-empty-title">
                            {filtered ? "Nothing matches this filter" : "No exams scheduled"}
                        </div>
                        <p className="stu-empty-text mt-2 mb-0">
                            {filtered
                                ? "Try another course or subject."
                                : "Exam dates your teachers publish will show up here."}
                        </p>
                    </div>
                ) : (
                    <div className="se-grid">
                        {exams.map((exam) => (
                            <ExamCard exam={exam} key={exam.id} onBlocked={setBlocked} />
                        ))}
                    </div>
                )}
            </div>

            {blocked && <BlockedDialog exam={blocked} onClose={() => setBlocked(null)} />}
        </StudentLayout>
    );
}
