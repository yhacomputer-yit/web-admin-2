import { router, usePage } from "@inertiajs/react";
import { Head } from '@inertiajs/react';
import StudentLayout from "../Layouts/StudentLayout";


const WEEKDAYS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

// three statuses are shown: attended (present), absent, and leave (late or
// leave) -- status 4 (leave) and status 3 (late) share the leave dot
const DOT = {
    1: "sa-dot-attend",
    2: "sa-dot-absent",
    3: "sa-dot-leave",
    4: "sa-dot-leave",
};

const formatDate = (value) => {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return d.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
};

const avatar = (src) => (src ? `/storage/${src}` : "/image/logo/student-placeholder.svg");


const pct = (value, total) => (total > 0 ? Math.round((value / total) * 1000) / 10 : 0);

export default function StudentDashboard({ student, enrollments, attendance }) {
    const { url } = usePage();

    const sectionCount = new Set(enrollments.map((e) => e.section_name).filter(Boolean)).size;
    const joined = formatDate(student.register_date);
    const firstCourse = enrollments.length ? enrollments[enrollments.length - 1]?.enroll_date : null;
    const latest = enrollments[0];

    // the attendance overview is optional so the dashboard still renders if the
    // prop is absent (e.g. a cached page from before the move)
    const summary = attendance?.summary;
    const calendar = attendance?.calendar;

    // Monday-first grid; getDay() is Sunday-first, so shift by 6
    const firstWeekday = calendar ? (new Date(calendar.year, calendar.month - 1, 1).getDay() + 6) % 7 : 0;
    const daysInMonth = calendar ? new Date(calendar.year, calendar.month, 0).getDate() : 0;
    const cells = calendar
        ? [...Array(firstWeekday).fill(null), ...Array.from({ length: daysInMonth }, (_, i) => i + 1)]
        : [];

    const gotoMonth = (month) => {
        router.get(`/student-portal/dashboard?month=${month}`, { preserveState: true });
    };

    const cards = summary
        ? [
            { key: "attended", label: "Attended", value: summary.present + summary.late, color: "sa-green", pct: `${summary.attended_percent}%` },
            { key: "absent", label: "Absent", value: summary.absent, color: "sa-red" },
            { key: "leave", label: "Leave", value: summary.leave, color: "sa-orange" },
            { key: "total", label: "Total Classes", value: summary.total, color: "sa-blue" },
        ]
        : [];

    return (
        <StudentLayout active="dashboard" title="Dashboard" status={student.status} key={url}>
            <Head>
                <title>Dashboard - Student Portal - YHA ACADEMY OF TECHNOLOGY</title>
                <meta name="description" content="Student dashboard at YHA ACADEMY OF TECHNOLOGY. View your courses, attendance, assignments, exams, and progress." />
                <meta name="keywords" content="student dashboard, student portal, YHA ACADEMY OF TECHNOLOGY, courses, attendance, assignments, exams, progress tracking" />
                <meta property="og:title" content="Dashboard - YHA ACADEMY OF TECHNOLOGY" />
                <meta property="og:description" content="Student dashboard at YHA ACADEMY OF TECHNOLOGY. View your courses, attendance, assignments, exams, and progress." />
                <meta property="og:image" content="/image/logo/logo.png" />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content="YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content="Dashboard - YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:description" content="Student dashboard at YHA ACADEMY OF TECHNOLOGY. View your courses, attendance, assignments, exams, and progress." />
                <meta name="twitter:image" content="/image/logo/logo.png" />
                <link rel="canonical" href={window.location.href} />
            </Head>
            {/* Profile summary */}
            <div className="stu-hero mb-3">
                <div className="p-3 p-md-4">
                    <div className="row g-3 align-items-center">
                        <div className="col-12 col-sm-auto">
                            <img
                                src={avatar(student.image)}
                                onError={(e) => { e.currentTarget.onerror = null; e.currentTarget.src = "/image/logo/student-placeholder.svg"; }}
                                alt={student.name}
                                className="stu-hero-avatar"
                            />
                        </div>
                        <div className="col-12 col-sm">
                            <h1 className="stu-hero-name">{student.name}</h1>
                            <div className="stu-hero-handle">
                                {student.nickname ? `"${student.nickname}"` : "Student"}
                                {" · "}
                                {student.username
                                    ? <code>{student.username}</code>
                                    : <span className="fst-italic">no username</span>}
                                {" · ID "}{student.id}
                            </div>
                        </div>
                    </div>

                    <div className="row g-2 mt-1">
                        <div className="col-6 col-md-3">
                            <div className="stu-stat">
                                <div className="stu-stat-label">Courses</div>
                                <div className="stu-stat-value">{enrollments.length}</div>
                            </div>
                        </div>
                        {/* <div className="col-6 col-md-3">
                            <div className="stu-stat">
                                <div className="stu-stat-label">Sections</div>
                                <div className="stu-stat-value">{sectionCount}</div>
                            </div>
                        </div> */}
                        <div className="col-6 col-md-3">
                            <div className="stu-stat">
                                <div className="stu-stat-label">Joined</div>
                                <div className="stu-stat-value">{joined || "—"}</div>
                            </div>
                        </div>
                        <div className="col-6 col-md-3">
                            <div className="stu-stat">
                                <div className="stu-stat-label">First enrolled</div>
                                <div className="stu-stat-value">{formatDate(firstCourse) || "—"}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* ---------- attendance overview (moved from My Attendance) ---------- */}
            {summary && (
                <>
                    <div className="sa-stat-row sa-stat-row-4 mb-3">
                        {cards.map((c) => (
                            <div className={`sa-stat ${c.color}`} key={c.key}>
                                <div className="sa-stat-label">{c.label}</div>
                                <div className="sa-stat-value">{c.value}</div>
                                <div className="sa-stat-pct">
                                    {c.pct || `${pct(c.value, summary.total)}%`}
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="sa-card">
                        <div className="sa-card-head">Attendance Calendar</div>

                        <div className="sa-toolbar">
                            <div className="sa-filters">
                                <div className="sa-month-nav">
                                    <button type="button" className="sa-month-btn"
                                        onClick={() => gotoMonth(attendance.prev_month)} aria-label="Previous month">
                                        <i className="fas fa-chevron-left"></i>
                                    </button>
                                    <span className="sa-month-label">{calendar.label}</span>
                                    <button type="button" className="sa-month-btn"
                                        onClick={() => gotoMonth(attendance.next_month)} aria-label="Next month">
                                        <i className="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div className="sa-legend">
                                <span><i className={`sa-dot ${DOT[1]}`}></i>Attended</span>
                                <span><i className={`sa-dot ${DOT[2]}`}></i>Absent</span>
                                <span><i className="sa-dot sa-dot-leave"></i>Leave</span>
                            </div>
                        </div>

                        <div className="sa-cal">
                            {WEEKDAYS.map((d) => (
                                <div key={d} className="sa-cal-head">{d}</div>
                            ))}
                            {cells.map((day, i) => {
                                if (day === null) return <div key={`pad-${i}`} className="sa-cal-cell sa-cal-pad" />;
                                const key = `${calendar.year}-${String(calendar.month).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
                                const rec = calendar.days[key];
                                const isToday = key === new Date().toISOString().slice(0, 10);
                                return (
                                    <div
                                        key={key}
                                        className={`sa-cal-cell ${isToday ? "sa-cal-today" : ""} ${rec ? "sa-cal-marked" : ""}`}
                                        title={rec ? rec.label : "No class recorded"}
                                    >
                                        <span className="sa-cal-day">{day}</span>
                                        {rec && <i className={`sa-dot-cl ${DOT[rec.status]}`}></i>}
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </>
            )}

        </StudentLayout>
    );
}
