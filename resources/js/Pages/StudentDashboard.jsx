import { useState } from "react";
import { Head, Link, router, usePage } from "@inertiajs/react";

const formatDate = (value) => {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return d.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
};

const monthYear = (value) => {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return d.toLocaleDateString("en-GB", { month: "short", year: "numeric" });
};

const avatar = (src) => src ? `/storage/${src}` : "/image/no-image.jpg";

const courseImg = (src) => src ? `/storage/${src}` : "/image/no-image.jpg";

export default function StudentDashboard({ student, enrollments }) {
    const { url } = usePage();
    const [tab, setTab] = useState("courses");

    const logout = (e) => {
        e.preventDefault();
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        router.post('/student-portal/logout', { _token: token }, {
            onFinish: () => { window.location.href = '/login'; },
        });
    };

    const sectionCount = new Set(enrollments.map((e) => e.section_name).filter(Boolean)).size;
    const joined = formatDate(student.register_date);
    const firstCourse = enrollments.length
        ? (enrollments[enrollments.length - 1]?.enroll_date)
        : null;
    const lastEnroll = enrollments.length ? enrollments[0]?.enroll_date : null;

    const account = [
        ["Username", student.username, false],
        ["Email", student.email, true],
        ["Phone", student.phone, true],
    ];

    const personal = [
        ["Full Name", student.name],
        ["Nick Name", student.nickname],
        ["Date of Birth", formatDate(student.date_of_birth)],
        ["Gender", student.gender ? student.gender.charAt(0).toUpperCase() + student.gender.slice(1) : null],
        ["NRC Number", student.nrc],
        ["Education", student.education],
        ["Native Town", student.native_town],
        ["Religious Status", student.religious_status],
        ["Race", student.race],
        ["Address", student.address],
    ];

    return (
        <div className="stu-dash" key={url}>
            <Head title="Student Dashboard" />

            <header className="stu-dash-header">
                <div className="container d-flex align-items-center justify-content-between py-3">
                    <div className="d-flex align-items-center gap-3">
                        <img src="/image/logo/logo.png" alt="YHA" width="40" height="40" className="stu-dash-logo" />
                        <div>
                            <div className="stu-dash-title">Student Dashboard</div>
                            <div className="stu-dash-subtitle">YHA Academy of Technology</div>
                        </div>
                    </div>
                    <div className="d-flex align-items-center gap-2">
                        <span className={`badge ${student.status === "active" ? "text-bg-success" : "text-bg-secondary"}`}>
                            {student.status}
                        </span>
                        <Link href="/" className="btn btn-sm btn-outline-secondary">Home</Link>
                        <button type="button" onClick={logout} className="btn btn-sm btn-outline-danger">
                            <i className="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </div>
                </div>
            </header>

            <main className="container py-4">
                {/* Profile summary */}
                <div className="stu-hero mb-3">
                    <div className="p-3 p-md-4">
                        <div className="row g-3 align-items-center">
                            <div className="col-12 col-sm-auto">
                                <img
                                    src={avatar(student.image)}
                                    onError={(e) => { e.currentTarget.onerror = null; e.currentTarget.src = "/image/no-image.jpg"; }}
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
                            <div className="col-6 col-md-3">
                                <div className="stu-stat">
                                    <div className="stu-stat-label">Sections</div>
                                    <div className="stu-stat-value">{sectionCount}</div>
                                </div>
                            </div>
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

                {/* Tabs */}
                <ul className="nav nav-tabs stu-tabs mb-3">
                    <li className="nav-item">
                        <button
                            className={`nav-link ${tab === "courses" ? "active" : ""}`}
                            onClick={() => setTab("courses")}
                        >
                            My Courses ({enrollments.length})
                        </button>
                    </li>
                    <li className="nav-item">
                        <button
                            className={`nav-link ${tab === "profile" ? "active" : ""}`}
                            onClick={() => setTab("profile")}
                        >
                            My Profile
                        </button>
                    </li>
                </ul>

                {tab === "courses" && (
                    enrollments.length === 0 ? (
                        <div className="stu-empty">
                            <div className="stu-empty-icon"><i className="fas fa-book-open"></i></div>
                            <div className="stu-empty-title">No courses yet</div>
                            <p className="stu-empty-text mt-2 mb-0">
                                You are not enrolled in any course. Please contact the admin to get started.
                            </p>
                        </div>
                    ) : (
                        <div className="row g-3">
                            {enrollments.map((e) => (
                                <div className="col-md-6 col-lg-4" key={e.id}>
                                    <div className="stu-card">
                                        <img
                                            src={courseImg(e.course_image)}
                                            onError={(ev) => { ev.currentTarget.onerror = null; ev.currentTarget.src = "/image/no-image.jpg"; }}
                                            alt={e.course_name}
                                            className="stu-card-img"
                                        />
                                        <div className="stu-card-body">
                                            {e.course_type && (
                                                <span className="badge text-bg-warning mb-2">{e.course_type}</span>
                                            )}
                                            <h3 className="stu-card-title">{e.course_name || "Unknown course"}</h3>
                                            <div className="stu-card-meta">
                                                <div>
                                                    <i className="fas fa-layer-group"></i>
                                                    <span>Section: {e.section_name || "—"}</span>
                                                </div>
                                                <div>
                                                    <i className="fas fa-calendar"></i>
                                                    <span>Enrolled: {formatDate(e.enroll_date) || "—"}</span>
                                                </div>
                                            </div>
                                        </div>
                                        {e.course_id && (
                                            <div className="stu-card-foot">
                                                <Link href={`/course/${e.course_id}`} className="stu-btn">
                                                    View Course <i className="fas fa-arrow-right"></i>
                                                </Link>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    )
                )}

                {tab === "profile" && (
                    <div className="row g-3">
                        <div className="col-12 col-lg-6">
                            <div className="stu-panel h-100">
                                <div className="stu-panel-head"><i className="fas fa-user"></i> Account</div>
                                <div className="stu-panel-body">
                                    {account.map(([label, value, linkable]) => (
                                        <div className="stu-row" key={label}>
                                            <div className="stu-row-label">{label}</div>
                                            <div className="stu-row-value">
                                                {value ? (
                                                    linkable ? (
                                                        value === student.email
                                                            ? <a href={`mailto:${value}`}>{value}</a>
                                                            : <a href={`tel:${value}`}>{value}</a>
                                                    ) : value
                                                ) : (
                                                    <span className="stu-row-blank">Not set</span>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                    <div className="stu-row">
                                        <div className="stu-row-label">Status</div>
                                        <div className="stu-row-value">
                                            {student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="col-12 col-lg-6">
                            <div className="stu-panel h-100">
                                <div className="stu-panel-head"><i className="fas fa-id-card"></i> Personal</div>
                                <div className="stu-panel-body">
                                    {personal.map(([label, value]) => (
                                        <div className="stu-row" key={label}>
                                            <div className="stu-row-label">{label}</div>
                                            <div className="stu-row-value">
                                                {value || <span className="stu-row-blank">—</span>}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </main>
        </div>
    );
}
