import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function CourseDetail({ course, subjects, prog, graph, ict, address }) {
    const ctaCourseUrl = course?.links && !course.links.startsWith('http') 
        ? `https://${course.links}` 
        : course?.links;

    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            {/* Hero Section */}
            <section className="course-hero">
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-6">
                            <div className="course-breadcrumb">
                                <Link href="/" className="breadcrumb-link">
                                    <i className="fas fa-home"></i> Home
                                </Link>
                                <span className="breadcrumb-separator">/</span>
                                <Link href="/yha/courses/monthl" className="breadcrumb-link">Courses</Link>
                                <span className="breadcrumb-separator">/</span>
                                <span className="breadcrumb-current">{course?.name}</span>
                            </div>

                            <h1 className="course-title">{course?.name}</h1>
                            <p className="course-description">{course?.description}</p>

                            <div className="course-meta">
                                <div className="meta-item">
                                    <i className="fas fa-clock"></i>
                                    <span>{course?.duration} Hours</span>
                                </div>
                                <div className="meta-item">
                                    <i className="fas fa-users"></i>
                                    <span>Live Classes</span>
                                </div>
                                <div className="meta-item">
                                    <i className="fas fa-certificate"></i>
                                    <span>Certificate</span>
                                </div>
                            </div>

                            <div className="course-actions">
                                {course?.links && (
                                    <a href={course.links} className="btn-primary" target="_blank" rel="noopener noreferrer">
                                        <i className="fas fa-external-link-alt"></i>
                                        View Details
                                    </a>
                                )}
                                <a href="#subjects" className="btn-secondary">
                                    <i className="fas fa-list"></i>
                                    View Subjects
                                </a>
                            </div>
                        </div>
                        <div className="col-lg-6">
                            <div className="course-image-wrapper">
                                <img src={`/storage/${course?.image}`} alt={course?.name} className="course-image" />
                                <div className="course-badge">
                                    <span>Featured Course</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Course About Section */}
            <section className="course-about">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-8">
                            <div className="about-content">
                                <h2 className="section-title">About This Course</h2>
                                <div className="about-text" dangerouslySetInnerHTML={{ __html: course?.about }} />
                            </div>
                        </div>
                        <div className="col-lg-4">
                            <div className="course-sidebar">
                                <div className="sidebar-card">
                                    <h3 className="sidebar-title">Course Information</h3>
                                    <div className="info-list">
                                        <div className="info-item">
                                            <i className="fas fa-calendar"></i>
                                            <div className="info-content">
                                                <span className="info-label">Duration</span>
                                                <span className="info-value">{course?.duration} Hours</span>
                                            </div>
                                        </div>
                                        <div className="info-item">
                                            <i className="fas fa-dollar-sign"></i>
                                            <div className="info-content">
                                                <span className="info-label">Price</span>
                                                <span className="info-value">Ks {Number(course?.normal_price || 0).toLocaleString()}</span>
                                            </div>
                                        </div>
                                        {course?.special_price && (
                                            <div className="info-item">
                                                <i className="fas fa-tag"></i>
                                                <div className="info-content">
                                                    <span className="info-label">Special Price</span>
                                                    <span className="info-value special">Ks {Number(course.special_price).toLocaleString()}</span>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Subjects Section */}
            <section id="subjects" className="subjects-section">
                <div className="container">
                    <div className="text-center section-header">
                        <h2 className="section-title">Course Subjects</h2>
                        <p className="section-subtitle">Comprehensive curriculum designed for your success</p>
                    </div>

                    <div className="row">
                        {subjects && subjects.length > 0 ? (
                            subjects.map((subject, index) => (
                                <div key={subject.id} className="mb-4 col-lg-4 col-md-6">
                                    <div className="subject-card">
                                        <div className="subject-number">{index + 1}</div>
                                        <div className="subject-content">
                                            <h3 className="subject-title">{subject.subject?.name}</h3>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="empty-state">
                                    <i className="fas fa-book-open"></i>
                                    <h3>No Subjects Available</h3>
                                    <p>Course subjects will be updated soon. Please check back later.</p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Call to Action */}
            <section className="cta-section">
                <div className="container">
                    <div className="text-center cta-content">
                        <h2>Ready to Start Learning?</h2>
                        <p>Join our course and take the first step towards your career goals</p>
                        <div className="cta-buttons">
                            <Link href="/yha/courses/monthl" className="btn-primary">
                                <i className="fas fa-arrow-left"></i>
                                Back to Courses
                            </Link>
                            {course?.links && (
                                <a href={ctaCourseUrl} className="btn-secondary" target="_blank" rel="noopener noreferrer">
                                    <i className="fas fa-external-link-alt"></i>
                                    Learn More
                                </a>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            <style jsx>{`
                /* Hero Section */
                .course-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    position: relative;
                    overflow: hidden;
                }

                .course-hero::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                    opacity: 0.3;
                }

                .course-breadcrumb {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin-bottom: 2rem;
                    font-size: 0.9rem;
                }

                .breadcrumb-link {
                    color: rgba(255, 255, 255, 0.8);
                    text-decoration: none;
                    transition: color 0.3s ease;
                }

                .breadcrumb-link:hover {
                    color: white;
                }

                .breadcrumb-separator {
                    color: rgba(255, 255, 255, 0.6);
                }

                .breadcrumb-current {
                    color: #ffd700;
                    font-weight: 600;
                }

                .course-title {
                    font-size: 3rem;
                    font-weight: 800;
                    margin-bottom: 1.5rem;
                    line-height: 1.2;
                }

                .course-description {
                    font-size: 1.2rem;
                    opacity: 0.9;
                    margin-bottom: 2rem;
                    line-height: 1.6;
                }

                .course-meta {
                    display: flex;
                    gap: 2rem;
                    margin-bottom: 2rem;
                    flex-wrap: wrap;
                }

                .meta-item {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .meta-item i {
                    color: #ffd700;
                    font-size: 1.1rem;
                }

                .course-actions {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                }

                .btn-primary, .btn-secondary {
                    padding: 0.75rem 1.5rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: all 0.3s ease;
                }

                .btn-primary {
                    background: white;
                    color: #ff6c0f;
                }

                .btn-primary:hover {
                    background: #f8f9fa;
                    color: #ff6c0f;
                    transform: translateY(-2px);
                }

                .btn-secondary {
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                    border: 1px solid rgba(255, 255, 255, 0.3);
                }

                .btn-secondary:hover {
                    background: rgba(255, 255, 255, 0.3);
                    color: white;
                    transform: translateY(-2px);
                }

                .course-image-wrapper {
                    position: relative;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
                }

                .course-image {
                    width: 100%;
                    height: 400px;
                    object-fit: cover;
                }

                .course-badge {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    background: #ffd700;
                    color: #2c3e50;
                    padding: 0.5rem 1rem;
                    border-radius: 25px;
                    font-size: 0.8rem;
                    font-weight: 700;
                }

                /* Course About Section */
                .course-about {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .section-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin-bottom: 2rem;
                    color: #2c3e50;
                }

                .about-text {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #495057;
                }

                .about-text :global(p) {
                    margin-bottom: 1rem;
                }

                .about-text :global(ul), .about-text :global(ol) {
                    margin-bottom: 1rem;
                    padding-left: 2rem;
                }

                .about-text :global(li) {
                    margin-bottom: 0.5rem;
                }

                /* Sidebar */
                .course-sidebar {
                    position: sticky;
                    top: 100px;
                }

                .sidebar-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                }

                .sidebar-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    color: #2c3e50;
                }

                .info-list {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .info-item {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1rem;
                    background: #f8f9fa;
                    border-radius: 10px;
                }

                .info-item i {
                    color: #ff6c0f;
                    font-size: 1.2rem;
                    width: 20px;
                }

                .info-content {
                    display: flex;
                    flex-direction: column;
                    gap: 0.25rem;
                }

                .info-label {
                    font-size: 0.9rem;
                    color: #6c757d;
                }

                .info-value {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #2c3e50;
                }

                .info-value.special {
                    color: #28a745;
                }

                /* Subjects Section */
                .subjects-section {
                    padding: 80px 0;
                    background: white;
                }

                .section-header {
                    margin-bottom: 3rem;
                }

                .section-subtitle {
                    font-size: 1.1rem;
                    color: #6c757d;
                    max-width: 600px;
                    margin: 0 auto;
                }

                .subject-card {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1.5rem;
                    background: #f8f9fa;
                    border-radius: 15px;
                    border-left: 4px solid #ff6c0f;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .subject-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                }

                .subject-number {
                    width: 40px;
                    height: 40px;
                    background: #ff6c0f;
                    color: white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 700;
                    flex-shrink: 0;
                }

                .subject-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #2c3e50;
                    margin: 0;
                }

                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    color: #6c757d;
                }

                .empty-state i {
                    font-size: 4rem;
                    margin-bottom: 1rem;
                    opacity: 0.5;
                }

                .empty-state h3 {
                    font-size: 1.5rem;
                    margin-bottom: 0.5rem;
                }

                /* CTA Section */
                .cta-section {
                    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .cta-content h2 {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin-bottom: 1rem;
                }

                .cta-content p {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    margin-bottom: 2rem;
                    max-width: 600px;
                    margin-left: auto;
                    margin-right: auto;
                }

                .cta-buttons {
                    display: flex;
                    justify-content: center;
                    gap: 1rem;
                    flex-wrap: wrap;
                }

                .cta-buttons .btn-primary {
                    background: #ff6c0f;
                    color: white;
                }

                .cta-buttons .btn-primary:hover {
                    background: #e65b00;
                }

                .cta-buttons .btn-secondary {
                    background: transparent;
                    color: white;
                    border: 2px solid white;
                }

                .cta-buttons .btn-secondary:hover {
                    background: white;
                    color: #2c3e50;
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .course-title {
                        font-size: 2rem;
                    }
                    
                    .course-meta {
                        justify-content: center;
                    }
                    
                    .course-actions {
                        justify-content: center;
                    }
                    
                    .course-image {
                        height: 250px;
                    }
                    
                    .info-item {
                        padding: 0.75rem;
                    }
                    
                    .cta-buttons {
                        flex-direction: column;
                        align-items: center;
                    }
                    
                    .cta-buttons .btn-primary,
                    .cta-buttons .btn-secondary {
                        width: 200px;
                        justify-content: center;
                    }
                }
            `}</style>
            <Footer address={address} />
        </div>
    );
}
