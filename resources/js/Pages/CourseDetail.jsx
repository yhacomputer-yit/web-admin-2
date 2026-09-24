import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/course-detail.css';

export default function CourseDetail({ course, subjects, prog, graph, ict, address }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />

            {/* ========== Course Hero ========== */}
            <section className="cd-hero">
                <div className="container">
                    <div className="row align-items-center gy-4">
                        <div className="col-lg-6">
                            <div className="cd-breadcrumb">
                                <Link href="/">Home</Link>
                                <span>/</span>
                                <Link href="/courses">Courses</Link>
                                <span>/</span>
                                <span className="current">{course?.name}</span>
                            </div>

                            <h1 className="cd-title">{course?.name}</h1>
                            <p className="cd-desc">{course?.description}</p>

                            <div className="cd-meta">
                                <div className="cd-meta-item">
                                    <i className="fas fa-clock"></i>
                                    <span>{course?.duration} Hours</span>
                                </div>
                                <div className="cd-meta-item">
                                    <i className="fas fa-users"></i>
                                    <span>Live Classes</span>
                                </div>
                                <div className="cd-meta-item">
                                    <i className="fas fa-certificate"></i>
                                    <span>Certificate</span>
                                </div>
                            </div>

                            <div className="cd-actions">
                                <a href="#subjects" className="cd-btn primary">
                                    <i className="fas fa-list"></i>
                                    View Subjects
                                </a>
                            </div>
                        </div>

                        <div className="col-lg-6">
                            <div className="cd-image-wrap">
                                <img
                                    src={`/storage/${course?.image}`}
                                    alt={course?.name}
                                    className="cd-image"
                                />
                                <div className="cd-badge">Featured Course</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========== About + Sidebar ========== */}
            <section className="cd-about">
                <div className="container">
                    <div className="row gy-4">
                        <div className="col-lg-8">
                            <h2 className="cd-section-title">About This Course</h2>
                            <div
                                className="cd-about-text"
                                dangerouslySetInnerHTML={{ __html: course?.about }}
                            />
                        </div>

                        <div className="col-lg-4">
                            <div className="cd-sidebar">
                                <h3 className="cd-sidebar-title">Course Information</h3>

                                <div className="cd-info-item">
                                    <i className="fas fa-calendar"></i>
                                    <div>
                                        <span className="label">Duration</span>
                                        <span className="value">{course?.duration} Hours</span>
                                    </div>
                                </div>

                                <div className="cd-info-item">
                                    <i className="fas fa-dollar-sign"></i>
                                    <div>
                                        <span className="label">Price</span>
                                        <span className="value">
                                            Ks {Number(course?.normal_price || 0).toLocaleString()}
                                        </span>
                                    </div>
                                </div>

                                {course?.special_price && (
                                    <div className="cd-info-item">
                                        <i className="fas fa-tag"></i>
                                        <div>
                                            <span className="label">Special Price</span>
                                            <span className="value special">
                                                Ks {Number(course.special_price).toLocaleString()}
                                            </span>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========== Subjects ========== */}
            <section id="subjects" className="cd-subjects">
                <div className="container">
                    <div className="text-center mb-5">
                        <h2 className="cd-section-title">Course Subjects</h2>
                        <p className="cd-section-subtitle">
                            Comprehensive curriculum designed for your success
                        </p>
                    </div>

                    <div className="row g-4">
                        {subjects && subjects.length > 0 ? (
                            subjects.map((subject, index) => (
                                <div key={subject.id} className="col-lg-4 col-md-6">
                                    <div className="cd-subject-card">
                                        <div className="cd-subject-num">{index + 1}</div>
                                        <h3 className="cd-subject-title">
                                            {subject.subject?.name}
                                        </h3>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="cd-empty">
                                    <i className="fas fa-book-open"></i>
                                    <h3>No Subjects Available</h3>
                                    <p>Course subjects will be updated soon.</p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
