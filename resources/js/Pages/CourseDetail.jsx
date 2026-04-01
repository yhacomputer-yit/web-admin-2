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
                                <Link href="/courses/" className="breadcrumb-link">Courses</Link>
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
                                {/* {course?.links && (
                                    // <a href={course.links} className="btn-primary" target="_blank" rel="noopener noreferrer">
                                    //     <i className="fas fa-external-link-alt"></i>
                                    //     View Details
                                    // </a>
                                )} */}
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
                            subjects.map((subject) => (
                                <div key={subject.id} className="mb-4 col-lg-4 col-md-6">
                                    <div className="subject-card">
                                        <div className="subject-number">{subjects.indexOf(subject) + 1}</div>
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

            <style jsx>{`
                /* Premium Course Detail Styles - Matching Homepage Design */
                .course-detail-section {
                    background: #ffffff;
                    position: relative;
                    overflow: hidden;
                    padding: 6rem 0;
                    margin-bottom: 0;
                    border-bottom: 1px solid #f8f9fa;
                }

                @media (max-width: 768px) {
                    .course-detail-section {
                        padding: 4rem 0;
                    }
                }

                @media (max-width: 576px) {
                    .course-detail-section {
                        padding: 3rem 0 !important;
                    }
                }

                /* Section Header - Premium Design */
                .hit-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.6rem;
                    background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
                    color: white;
                    padding: 0.6rem 1.8rem;
                    border-radius: 50px;
                    font-size: 0.85rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 1.2px;
                    margin-bottom: 2rem;
                    box-shadow: 0 4px 20px rgba(255,107,53,0.25);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                }

                .hit-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    line-height: 1.2;
                    letter-spacing: -0.02em;
                    color: #1e293b;
                }

                .hit-title span {
                    display: block;
                }

                .hit-title span:first-child {
                    color: #1e293b;
                }

                .hit-title span:last-child {
                    color: #ff6b35;
                }

                .hit-subtitle {
                    font-size: 1.1rem;
                    color: #64748b;
                    max-width: 700px;
                    margin: 0 auto;
                    line-height: 1.7;
                    font-weight: 400;
                    letter-spacing: 0.01em;
                }

                /* Course Hero - Premium Design */
                .course-hero {
                    background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
                    padding: 4rem 0;
                    color: white;
                    position: relative;
                    overflow: hidden;
                    border-radius: 0 0 30px 30px;
                    margin-bottom: 4rem;
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
                    position: relative;
                    z-index: 1;
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
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin-bottom: 1.5rem;
                    line-height: 1.2;
                    position: relative;
                    z-index: 1;
                }

                .course-description {
                    font-size: 1.1rem;
                    opacity: 0.95;
                    margin-bottom: 2rem;
                    line-height: 1.6;
                    position: relative;
                    z-index: 1;
                }

                .course-meta {
                    display: flex;
                    gap: 2rem;
                    margin-bottom: 2rem;
                    flex-wrap: wrap;
                    position: relative;
                    z-index: 1;
                }

                .meta-item {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    background: rgba(255, 255, 255, 0.1);
                    padding: 0.5rem 1rem;
                    border-radius: 20px;
                    backdrop-filter: blur(10px);
                }

                .meta-item i {
                    color: #ffd700;
                    font-size: 1rem;
                }

                .course-actions {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                    position: relative;
                    z-index: 1;
                }

                .btn-primary, .btn-secondary {
                    padding: 0.8rem 1.5rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: all 0.3s ease;
                    font-size: 0.9rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .btn-primary {
                    background: white;
                    color: #ff6b35;
                    box-shadow: 0 4px 15px rgba(255,255,255,0.2);
                }

                .btn-primary:hover {
                    background: #f8f9fa;
                    color: #ff6b35;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(255,255,255,0.3);
                }

                .btn-secondary {
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    backdrop-filter: blur(10px);
                }

                .btn-secondary:hover {
                    background: rgba(255, 255, 255, 0.3);
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(255,255,255,0.2);
                }

                .course-image-wrapper {
                    position: relative;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    transition: transform 0.3s ease;
                }

                .course-image-wrapper:hover {
                    transform: translateY(-5px);
                }

                .course-image {
                    width: 100%;
                    height: 400px;
                    object-fit: cover;
                    transition: transform 0.6s ease;
                }

                .course-image-wrapper:hover .course-image {
                    transform: scale(1.05);
                }

                .course-badge {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    background: linear-gradient(135deg, #ffd700, #ffed4e);
                    color: #2c3e50;
                    padding: 0.5rem 1rem;
                    border-radius: 25px;
                    font-size: 0.8rem;
                    font-weight: 700;
                    box-shadow: 0 4px 15px rgba(255,215,0,0.3);
                }

                /* Course About Section - Premium Design */
                .course-about {
                    padding: 4rem 0;
                    background: #f8f9fa;
                    position: relative;
                }

                .section-title {
                    font-size: 2rem;
                    font-weight: 700;
                    margin-bottom: 2rem;
                    color: #ff6b35;
                    line-height: 1.3;
                    letter-spacing: -0.01em;
                }

                .about-text {
                    font-size: 1.05rem;
                    line-height: 1.8;
                    color: #475569;
                    font-weight: 400;
                }

                .about-text :global(p) {
                    margin-bottom: 1.5rem;
                }

                .about-text :global(ul), .about-text :global(ol) {
                    margin-bottom: 1.5rem;
                    padding-left: 2rem;
                }

                .about-text :global(li) {
                    margin-bottom: 0.75rem;
                }

                /* Sidebar - Premium Design */
                .course-sidebar {
                    position: sticky;
                    top: 100px;
                }

                .sidebar-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
                    border: 1px solid rgba(0,0,0,0.06);
                    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                }

                .sidebar-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 8px 20px rgba(0,0,0,0.08);
                    border-color: rgba(0,0,0,0.1);
                }

                .sidebar-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    color: #1e293b;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .sidebar-title i {
                    color: #ff6b35;
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
                    padding: 1.2rem;
                    background: linear-gradient(135deg, #fafbfc, #f8fafc);
                    border-radius: 12px;
                    border: 1px solid rgba(0,0,0,0.06);
                    transition: all 0.3s ease;
                }

                .info-item:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                    border-color: rgba(255,107,53,0.1);
                }

                .info-item i {
                    color: #ff6b35;
                    font-size: 1.2rem;
                    width: 20px;
                }

                .info-content {
                    display: flex;
                    flex-direction: column;
                    gap: 0.25rem;
                }

                .info-label {
                    font-size: 0.85rem;
                    color: #64748b;
                    font-weight: 500;
                }

                .info-value {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #1e293b;
                }

                .info-value.special {
                    color: #10b981;
                }

                /* Subjects Section - Premium Design */
                .subjects-section {
                    padding: 4rem 0;
                    background: white;
                }

                .section-header {
                    margin-bottom: 3rem;
                }

                .section-subtitle {
                    font-size: 1.1rem;
                    color: #64748b;
                    max-width: 600px;
                    margin: 0 auto;
                    line-height: 1.6;
                }

                .subject-card {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1.5rem;
                    background: white;
                    border-radius: 16px;
                    border: 1px solid rgba(0,0,0,0.06);
                    border-left: 4px solid #ff6b35;
                    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
                }

                .subject-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 8px 20px rgba(0,0,0,0.08);
                    border-color: rgba(255,107,53,0.2);
                }

                .subject-number {
                    width: 50px;
                    height: 50px;
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    color: white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 700;
                    font-size: 1.1rem;
                    flex-shrink: 0;
                    box-shadow: 0 4px 15px rgba(255,107,53,0.3);
                }

                .subject-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #1e293b;
                    margin: 0;
                    line-height: 1.4;
                }

                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    border: 1px solid rgba(255,107,53,0.08);
                }

                .empty-state i {
                    font-size: 3rem;
                    color: #ff6b35;
                    margin-bottom: 1rem;
                    opacity: 0.7;
                }

                .empty-state h3 {
                    font-size: 1.5rem;
                    margin-bottom: 0.5rem;
                    color: #1e293b;
                }

                .empty-state p {
                    color: #64748b;
                    margin: 0;
                }

                /* Responsive Design */
                @media (max-width: 1200px) {
                    .course-hero {
                        padding: 3rem 0;
                    }

                    .course-title {
                        font-size: 2.2rem;
                    }
                }

                @media (max-width: 992px) {
                    .course-hero {
                        padding: 2.5rem 0;
                    }

                    .course-title {
                        font-size: 2rem;
                    }

                    .course-description {
                        font-size: 1rem;
                    }

                    .course-image {
                        height: 350px;
                    }

                    .course-meta {
                        gap: 1rem;
                    }
                }

                @media (max-width: 768px) {
                    .course-hero {
                        padding: 2rem 0;
                        margin-bottom: 3rem;
                    }

                    .course-title {
                        font-size: 1.8rem;
                    }

                    .course-description {
                        font-size: 0.95rem;
                    }

                    .course-meta {
                        justify-content: center;
                        gap: 1rem;
                    }

                    .course-actions {
                        justify-content: center;
                    }

                    .course-image {
                        height: 250px;
                    }

                    .course-image-wrapper {
                        margin-top: 2rem;
                    }

                    .sidebar-card {
                        margin-top: 2rem;
                    }

                    .info-item {
                        padding: 1rem;
                    }

                    .subject-card {
                        padding: 1rem;
                    }

                    .subject-number {
                        width: 40px;
                        height: 40px;
                        font-size: 1rem;
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

                    .course-about {
                        padding: 3rem 0;
                    }

                    .subjects-section {
                        padding: 3rem 0;
                    }
                }

                @media (max-width: 576px) {
                    .course-hero {
                        padding: 1.5rem 0;
                        margin-bottom: 2rem;
                    }

                    .course-title {
                        font-size: 1.6rem;
                    }

                    .course-description {
                        font-size: 0.9rem;
                    }

                    .course-breadcrumb {
                        font-size: 0.8rem;
                        margin-bottom: 1rem;
                    }

                    .course-meta {
                        flex-direction: column;
                        align-items: center;
                        gap: 0.5rem;
                    }

                    .meta-item {
                        padding: 0.4rem 0.8rem;
                        font-size: 0.85rem;
                    }

                    .course-actions {
                        flex-direction: column;
                        align-items: center;
                        gap: 0.5rem;
                    }

                    .btn-primary, .btn-secondary {
                        width: 100%;
                        max-width: 200px;
                        font-size: 0.8rem;
                        padding: 0.6rem 1rem;
                    }

                    .course-image {
                        height: 200px;
                    }

                    .course-image-wrapper {
                        margin-top: 1.5rem;
                    }

                    .course-badge {
                        top: 15px;
                        right: 15px;
                        padding: 0.4rem 0.8rem;
                        font-size: 0.7rem;
                    }

                    .sidebar-card {
                        padding: 1.5rem;
                        margin-top: 1.5rem;
                    }

                    .sidebar-title {
                        font-size: 1.1rem;
                    }

                    .info-item {
                        padding: 0.8rem;
                        flex-direction: column;
                        text-align: center;
                        gap: 0.5rem;
                    }

                    .info-value {
                        font-size: 1rem;
                    }

                    .section-title {
                        font-size: 1.5rem;
                    }

                    .about-text {
                        font-size: 0.95rem;
                    }

                    .subject-card {
                        padding: 1rem;
                        flex-direction: column;
                        text-align: center;
                    }

                    .subject-number {
                        width: 35px;
                        height: 35px;
                        font-size: 0.9rem;
                        margin-bottom: 0.5rem;
                    }

                    .subject-title {
                        font-size: 1rem;
                    }

                    .empty-state {
                        padding: 2rem 1rem;
                    }

                    .empty-state i {
                        font-size: 2.5rem;
                    }

                    .empty-state h3 {
                        font-size: 1.3rem;
                    }

                    .course-about {
                        padding: 2rem 0;
                    }

                    .subjects-section {
                        padding: 2rem 0;
                    }
                }

                @media (max-width: 480px) {
                    .course-title {
                        font-size: 1.4rem;
                    }

                    .course-description {
                        font-size: 0.85rem;
                    }

                    .course-image {
                        height: 180px;
                    }

                    .sidebar-card {
                        padding: 1rem;
                    }

                    .info-item {
                        padding: 0.6rem;
                    }

                    .subject-card {
                        padding: 0.8rem;
                    }

                    .subject-number {
                        width: 30px;
                        height: 30px;
                        font-size: 0.8rem;
                    }

                    .btn-primary, .btn-secondary {
                        font-size: 0.75rem;
                        padding: 0.5rem 0.8rem;
                    }
                }
            `}</style>
            <Footer address={address} />
        </div>
    );
}
