import { useState } from "react";
import { Head, Link, router, usePage } from "@inertiajs/react";

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

    const detail = [
        ["Full Name", student.name],
        ["Nick Name", student.nickname],
        ["Username", student.username],
        ["Email", student.email],
        ["Phone", student.phone],
        ["Address", student.address],
        ["Date of Birth", student.date_of_birth],
        ["NRC Number", student.nrc],
        ["Gender", student.gender],
        ["Education", student.education],
        ["Native Town", student.native_town],
        ["Religious Status", student.religious_status],
        ["Race", student.race],
    ];

    return (
        <div className="stu-dash" key={url}>
            <Head title="Student Dashboard" />

            <header className="stu-dash-header">
                <div className="container d-flex align-items-center justify-content-between py-3">
                    <div className="d-flex align-items-center gap-3">
                        <img src="/image/logo/logo.png" alt="YHA" width="42" height="42" />
                        <div>
                            <div className="fw-bold">Student Dashboard</div>
                            <small className="text-muted">YHA Academy of Technology</small>
                        </div>
                    </div>
                    <div className="d-flex align-items-center gap-3">
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
                {/* Profile card */}
                <div className="card shadow-sm mb-4">
                    <div className="card-body d-flex flex-wrap align-items-center gap-4">
                        <img
                            src={student.image ? `/storage/${student.image}` : "/image/no-image.jpg"}
                            onError={(e) => { e.currentTarget.onerror = null; e.currentTarget.src = "/image/no-image.jpg"; }}
                            alt={student.name}
                            width="110"
                            height="110"
                            className="rounded-circle object-fit-cover border"
                        />
                        <div className="flex-grow-1">
                            <h4 className="mb-1">{student.name}</h4>
                            <p className="text-muted mb-2">
                                {student.nickname ? `"${student.nickname}"` : "Student"} &middot; {student.username}
                            </p>
                            <div className="d-flex flex-wrap gap-3 small text-muted">
                                <span><i className="fas fa-graduation-cap me-1"></i>{enrollments.length} course(s)</span>
                                <span><i className="fas fa-calendar me-1"></i>Joined per enrollments below</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Tabs */}
                <ul className="nav nav-tabs mb-3">
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
                    <div className="row g-3">
                        {enrollments.length === 0 ? (
                            <div className="col-12">
                                <div className="card shadow-sm">
                                    <div className="card-body text-center py-5">
                                        <i className="fas fa-book-open fa-3x text-muted mb-3"></i>
                                        <h5>No courses yet</h5>
                                        <p className="text-muted mb-0">
                                            You are not enrolled in any course. Please contact the admin.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            enrollments.map((e) => (
                                <div className="col-md-6 col-lg-4" key={e.id}>
                                    <div className="card shadow-sm h-100">
                                        <img
                                            src={e.course_image ? `/storage/${e.course_image}` : "/image/no-image.jpg"}
                                            onError={(ev) => { ev.currentTarget.onerror = null; ev.currentTarget.src = "/image/no-image.jpg"; }}
                                            alt={e.course_name}
                                            className="card-img-top"
                                            style={{ height: "160px", objectFit: "cover" }}
                                        />
                                        <div className="card-body">
                                            {e.course_type && (
                                                <span className="badge text-bg-warning mb-2">{e.course_type}</span>
                                            )}
                                            <h6 className="card-title">{e.course_name}</h6>
                                            <ul className="list-unstyled small text-muted mb-0">
                                                <li><i className="fas fa-layer-group me-2"></i>Section: {e.section_name || "-"}</li>
                                                <li><i className="fas fa-calendar me-2"></i>Enrolled: {e.enroll_date || "-"}</li>
                                            </ul>
                                        </div>
                                        {e.course_id && (
                                            <div className="card-footer bg-transparent border-0">
                                                <Link href={`/course/${e.course_id}`} className="btn btn-sm btn-outline-primary">
                                                    View Course <i className="fas fa-arrow-right ms-1"></i>
                                                </Link>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                )}

                {tab === "profile" && (
                    <div className="card shadow-sm">
                        <div className="card-body">
                            <div className="row g-3">
                                {detail.map(([label, value]) => (
                                    <div className="col-md-6 col-lg-4" key={label}>
                                        <div className="small text-muted">{label}</div>
                                        <div className="fw-semibold">{value || "-"}</div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                )}
            </main>
        </div>
    );
}
