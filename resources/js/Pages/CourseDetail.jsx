import { Link } from '@inertiajs/react';
import { useRef } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/course-detail.css';

export default function CourseDetail({ course, subjects, relatedCourses = [], prog, graph, ict, address }) {
    const scrollRef = useRef(null);

    const scroll = (direction) => {
        if (scrollRef.current) {
            const amount = 340;
            scrollRef.current.scrollBy({
                left: direction === 'left' ? -amount : amount,
                behavior: 'smooth',
            });
        }
    };

    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />

            {/* ========== Course Hero – New Full Image Style ========== */}
            <section className="cd-hero">
                <div
                    className="cd-hero-bg"
                    style={{ backgroundImage: `url(/storage/${course?.image})` }}
                ></div>
                <div className="cd-hero-overlay"></div>

                <div className="container cd-container">
                    <div className="cd-hero-content">
                        <div className="cd-breadcrumb">
                            <Link href="/">Home</Link>
                            <span>/</span>
                            <Link href="/courses">Courses</Link>
                            <span>/</span>
                            <span className="current">{course?.name}</span>
                        </div>

                        <h1 className="cd-title">{course?.name}</h1>

                    </div>
                </div>
            </section>

            {/* ========== About + Sidebar ========== */}
            <section className="cd-about">
                <div className="container cd-container">
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
                <div className="container cd-container">
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

            {/* ========== Related Courses ========== */}
            {relatedCourses && relatedCourses.length > 0 && (
                <section className="cd-related">
                    <div className="container cd-container">
                        <div className="cd-related-header">
                            <div>
                                <h2 className="cd-section-title mb-1">Related Courses</h2>
                                <p className="cd-section-subtitle text-start mb-0">
                                    Explore more courses you might like
                                </p>
                            </div>

                            <div className="cd-related-arrows">
                                <button
                                    type="button"
                                    className="cd-arrow-btn"
                                    onClick={() => scroll('left')}
                                    aria-label="Scroll left"
                                >
                                    <i className="fas fa-chevron-left"></i>
                                </button>
                                <button
                                    type="button"
                                    className="cd-arrow-btn"
                                    onClick={() => scroll('right')}
                                    aria-label="Scroll right"
                                >
                                    <i className="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <div className="cd-related-track" ref={scrollRef}>
                            {relatedCourses.map((item) => (
                                <div key={item.id} className="cd-related-card">
                                    <div className="cd-related-img-wrap">
                                        <img
                                            src={`/storage/${item.image}`}
                                            alt={item.name}
                                            className="cd-related-img"
                                        />
                                    </div>
                                    <div className="cd-related-body">
                                        <h3 className="cd-related-title">{item.name}</h3>
                                        <p className="cd-related-desc">
                                            {item.code || ''}
                                        </p>
                                        <div className="cd-related-footer">
                                            <div>
                                                <span className="cd-related-fee-label">Course Fee</span>
                                                <span className="cd-related-fee">
                                                    Ks {Number(item.normal_price || 0).toLocaleString()}
                                                </span>
                                            </div>
                                            <Link
                                                href={`/courses/${item.id}`}
                                                className="cd-related-link"
                                            >
                                                Learn More →
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <Footer address={address} />
        </div>
    );
}
