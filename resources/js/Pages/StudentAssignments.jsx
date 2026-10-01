import { useMemo, useState } from "react";
import { usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";
import { ASSIGNMENT_STATUS, MOCK_ASSIGNMENTS } from "./studentPortalMock";


const STATUS = {
    [ASSIGNMENT_STATUS.PENDING]: { label: "Pending", className: "sa-badge-pending", icon: "fas fa-clock" },
    [ASSIGNMENT_STATUS.SUBMITTED]: { label: "Submitted", className: "sa-badge-submitted", icon: "fas fa-paper-plane" },
    [ASSIGNMENT_STATUS.LATE]: { label: "Late", className: "sa-badge-late", icon: "fas fa-triangle-exclamation" },
    [ASSIGNMENT_STATUS.GRADED]: { label: "Graded", className: "sa-badge-graded", icon: "fas fa-circle-check" },
};

const FILTERS = [
    { key: "all", label: "All" },
    { key: ASSIGNMENT_STATUS.PENDING, label: "Pending" },
    { key: ASSIGNMENT_STATUS.SUBMITTED, label: "Submitted" },
    { key: ASSIGNMENT_STATUS.LATE, label: "Late" },
    { key: ASSIGNMENT_STATUS.GRADED, label: "Graded" },
];

const formatDate = (value) => {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return d.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
};

/** Deadline urgency drives the countdown text next to the date. */


const deadlineInfo = (value) => {
    if (!value) return { text: "No deadline", tone: "muted" };
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return { text: "No deadline", tone: "muted" };

    // compare on date boundaries so a deadline today is not "in 0 days"
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const target = new Date(d);
    target.setHours(0, 0, 0, 0);
    const days = Math.round((target - today) / 86400000);

    if (days < 0) return { text: `${Math.abs(days)} day${Math.abs(days) === 1 ? "" : "s"} overdue`, tone: "danger" };
    if (days === 0) return { text: "Due today", tone: "danger" };
    if (days === 1) return { text: "Due tomorrow", tone: "warn" };
    if (days <= 3) return { text: `${days} days left`, tone: "warn" };
    return { text: `${days} days left`, tone: "muted" };
};

export default function StudentAssignments({ assignments }) {
    const { url } = usePage();
    const [filter, setFilter] = useState("all");
    const [query, setQuery] = useState("");

    const list = assignments?.length ? assignments : MOCK_ASSIGNMENTS;

    const counts = useMemo(() => {
        const out = { all: list.length };
        for (const a of list) out[a.status] = (out[a.status] || 0) + 1;
        return out;
    }, [list]);

    const visible = useMemo(() => {
        const q = query.trim().toLowerCase();
        return list.filter((a) => {
            const matchesStatus = filter === "all" || a.status === filter;
            const matchesQuery = !q
                || a.title.toLowerCase().includes(q)
                || (a.course_name || "").toLowerCase().includes(q);
            return matchesStatus && matchesQuery;
        });
    }, [list, filter, query]);

    const graded = list.filter((a) => a.status === ASSIGNMENT_STATUS.GRADED);
    const average = graded.length
        ? Math.round(graded.reduce((sum, a) => sum + (a.score || 0), 0) / graded.length)
        : null;

    return (
        <StudentLayout active="assignments" title="Assignment" key={url}>
            <div className="sa-page">
                <div className="sa-stat-row mb-3">
                    <div className="sa-stat sa-blue">
                        <div className="sa-stat-label">Total</div>
                        <div className="sa-stat-value">{list.length}</div>
                    </div>
                    <div className="sa-stat sa-orange">
                        <div className="sa-stat-label">Pending</div>
                        <div className="sa-stat-value">{counts[ASSIGNMENT_STATUS.PENDING] || 0}</div>
                    </div>
                    <div className="sa-stat sa-blue">
                        <div className="sa-stat-label">Submitted</div>
                        <div className="sa-stat-value">{counts[ASSIGNMENT_STATUS.SUBMITTED] || 0}</div>
                    </div>
                    <div className="sa-stat sa-red">
                        <div className="sa-stat-label">Late</div>
                        <div className="sa-stat-value">{counts[ASSIGNMENT_STATUS.LATE] || 0}</div>
                    </div>
                    <div className="sa-stat sa-green">
                        <div className="sa-stat-label">Average</div>
                        <div className="sa-stat-value">{average === null ? "—" : `${average}%`}</div>
                    </div>
                </div>

                <div className="sa-toolbar">
                    <div className="sa-filters">
                        {FILTERS.map((f) => (
                            <button
                                key={f.key}
                                type="button"
                                onClick={() => setFilter(f.key)}
                                className={`sa-chip ${filter === f.key ? "active" : ""}`}
                            >
                                {f.label}
                                <span className="sa-chip-count">{counts[f.key] || 0}</span>
                            </button>
                        ))}
                    </div>

                    <div className="sa-search">
                        <i className="fas fa-magnifying-glass"></i>
                        <input
                            type="search"
                            className="sa-search-input"
                            placeholder="Search assignments"
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            aria-label="Search assignments"
                        />
                    </div>
                </div>

                {visible.length === 0 ? (
                    <div className="stu-empty">
                        <div className="stu-empty-icon"><i className="fas fa-file-lines"></i></div>
                        <div className="stu-empty-title">
                            {list.length === 0 ? "No assignments yet" : "Nothing matches this filter"}
                        </div>
                        <p className="stu-empty-text mt-2 mb-0">
                            {list.length === 0
                                ? "Your assignments will appear here once a teacher publishes them."
                                : "Try a different status or clear the search box."}
                        </p>
                        {list.length > 0 && (
                            <button
                                type="button"
                                className="stu-btn mt-3 stu-btn-auto"
                                onClick={() => { setFilter("all"); setQuery(""); }}
                            >
                                Clear filters
                            </button>
                        )}
                    </div>
                ) : (
                    <div className="sa-assign-list">
                        {visible.map((a) => {
                            const s = STATUS[a.status] || STATUS[ASSIGNMENT_STATUS.PENDING];
                            const d = deadlineInfo(a.deadline);
                            const canSubmit = a.status === ASSIGNMENT_STATUS.PENDING;

                            return (
                                <article className="sa-assign" key={a.id}>
                                    <div className="sa-assign-main">
                                        <div className="sa-assign-top">
                                            <h3 className="sa-assign-title">{a.title}</h3>
                                            <span className={`sa-badge ${s.className}`}>
                                                <i className={s.icon}></i> {s.label}
                                            </span>
                                        </div>

                                        <div className="sa-assign-course">
                                            <i className="fas fa-book-open"></i>
                                            {a.course_name || "—"}
                                        </div>

                                        <div className="sa-assign-foot">
                                            <span className="sa-assign-deadline">
                                                <i className="fas fa-calendar"></i>
                                                Due {formatDate(a.deadline) || "—"}
                                                <span className={`sa-due sa-due-${d.tone}`}>{d.text}</span>
                                            </span>

                                            {a.status === ASSIGNMENT_STATUS.GRADED && a.score !== null && (
                                                <span className="sa-score">
                                                    <span className="sa-score-label">Score</span>
                                                    <span className="sa-score-value">{a.score}%</span>
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <div className="sa-assign-action">
                                        {canSubmit ? (
                                            <button type="button" className="stu-btn stu-btn-auto">
                                                <i className="fas fa-upload me-1"></i> Submit
                                            </button>
                                        ) : (
                                            <button type="button" className="stu-btn stu-btn-auto stu-btn-ghost" disabled>
                                                {a.status === ASSIGNMENT_STATUS.GRADED ? "Reviewed" : "Awaiting review"}
                                            </button>
                                        )}
                                    </div>
                                </article>
                            );
                        })}
                    </div>
                )}
            </div>
        </StudentLayout>
    );
}
