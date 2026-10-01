import { useState } from "react";
import { router, usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";


const BADGE = {
    1: "sa-badge-attended",
    2: "sa-badge-absent",
    3: "sa-badge-late",
    4: "sa-badge-leave",
};

/**
 * Recent attendance records only.
 *
 * The five summary cards and the calendar moved to the dashboard, so this page
 * no longer receives `summary`, `calendar`, `prev_month` or `next_month`.
 */
export default function StudentAttendance({ recent, courses, filters }) {
    const { url } = usePage();
    const [courseId, setCourseId] = useState(filters.course_id ?? "");

    const applyFilters = (nextCourse) => {
        const params = new URLSearchParams();
        if (nextCourse) params.set("course_id", nextCourse);
        router.get(`/student-portal/attendance?${params.toString()}`, { preserveState: true });
    };

    // Laravel sends from/to on the paginator, but they are null on an empty page
    // and absent if a future call switches to a simple paginator.
    const perPage = recent.per_page || recent.data.length || 1;
    const from = recent.from ?? (recent.data.length ? (recent.current_page - 1) * perPage + 1 : 0);
    const to = recent.to ?? (recent.data.length ? from + recent.data.length - 1 : 0);

    return (
        <StudentLayout active="attendance" title="My Attendance" key={url}>
            <div className="sa-page">
                <div className="sa-toolbar">
                    <div className="sa-filters">
                        {/* the caret is drawn by the wrapper, not the browser, so the
                            control reads as a dropdown on every platform and can be
                            restyled with the rest of the portal */}
                        <div className="sa-select-wrap">
                            <select
                                className="sa-select"
                                value={courseId}
                                onChange={(e) => { setCourseId(e.target.value); applyFilters(e.target.value); }}
                                aria-label="Filter by course"
                            >
                                <option value="">All courses</option>
                                {courses.map((c) => (
                                    <option key={c.id} value={c.id}>{c.name}</option>
                                ))}
                            </select>
                            <i className="fas fa-chevron-down sa-select-caret" aria-hidden="true"></i>
                        </div>

                        {filters.course_id && (
                            <button type="button" className="sa-month-btn" title="Clear filter"
                                onClick={() => { setCourseId(""); applyFilters(""); }}
                                aria-label="Clear course filter">
                                <i className="fas fa-xmark"></i>
                            </button>
                        )}
                    </div>

                    <span className="sa-toolbar-count">
                        {recent.total} record{recent.total === 1 ? "" : "s"}
                    </span>
                </div>

                <div className="sa-card">
                    <div className="sa-card-head sa-card-head-row">
                        <span className="sa-card-head-title">Recent Attendance</span>
                        <span className="sa-card-head-sub">Most recent first</span>
                    </div>

                    {recent.data.length === 0 ? (
                        <div className="sa-empty">
                            <div className="sa-empty-icon"><i className="fas fa-calendar-check"></i></div>
                            <div className="sa-empty-title">Nothing recorded yet</div>
                            <p className="sa-empty-text">Your attendance will appear here once a class has been marked.</p>
                        </div>
                    ) : (
                        <div className="sa-table-wrap">
                            <table className="sa-table">
                                <caption className="sa-caption">
                                    Attendance records, most recent first
                                </caption>
                                <thead>
                                    <tr>
                                        <th scope="col" className="sa-col-date">Date</th>
                                        <th scope="col">Course</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col" className="sa-col-section">Section</th>
                                        <th scope="col" className="sa-col-status">Status</th>
                                        <th scope="col" className="sa-col-remark">Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {recent.data.map((r) => (
                                        <tr key={r.id}>
                                            {/* data-label is what turns each row into a
                                                labelled card on a phone */}
                                            <td data-label="Date" className="sa-cell-date">{r.date_label}</td>
                                            <td data-label="Course">{r.course_name || "—"}</td>
                                            <td data-label="Subject" className="sa-cell-strong">{r.subject_name || "—"}</td>
                                            <td data-label="Section">{r.section_name || "—"}</td>
                                            <td data-label="Status" className="sa-col-status">
                                                <span className={`sa-badge ${BADGE[r.status]}`}>{r.status_label}</span>
                                            </td>
                                            <td data-label="Remark" className="sa-cell-remark">{r.remark || "—"}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}

                    <div className="sa-pager">
                        <span className="sa-pager-info">
                            Showing <strong>{from}</strong>–<strong>{to}</strong> of <strong>{recent.total}</strong> record{recent.total === 1 ? "" : "s"}
                        </span>

                        {recent.last_page > 1 && (
                            <span className="sa-pager-links">
                                {recent.links.map((link, i) => (
                                    link.url ? (
                                        <button
                                            key={i}
                                            type="button"
                                            className={`sa-pbtn ${link.active ? "active" : ""}`}
                                            aria-current={link.active ? "page" : undefined}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            onClick={() => router.get(link.url, { preserveState: true })}
                                        />
                                    ) : (
                                        <span key={i} className="sa-pbtn disabled"
                                            dangerouslySetInnerHTML={{ __html: link.label }} />
                                    )
                                ))}
                            </span>
                        )}
                    </div>
                </div>
            </div>
        </StudentLayout>
    );
}
