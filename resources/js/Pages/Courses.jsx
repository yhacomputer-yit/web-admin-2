import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Courses({ monthies }) {
    const getCategoryIcon = (type) => {
        switch(type) {
            case 1: return { icon: 'fas fa-code', label: 'Programming' };
            case 2: return { icon: 'fas fa-palette', label: 'Graphic Design' };
            case 3: return { icon: 'fas fa-laptop', label: 'ICT' };
            default: return { icon: 'fas fa-graduation-cap', label: 'Course' };
        }
    };

    return (
        <>
            {/* Hero Section */}
            <section className="courses-hero">
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-6">
                            <h1 className="hero-title">Discover Our Monthly Courses</h1>
                            <p className="hero-subtitle">Master new skills with our comprehensive monthly course programs designed for your success</p>
                            <div className="hero-stats">
                                <div className="stat-item">
                                    <span className="stat-number">{monthies?.total || 0}+</span>
                                    <span className="stat-label">Courses Available</span>
                                </div>
                                <div className="stat-item">
                                    <span className="stat-number">100%</span>
                                    <span className="stat-label">Practical Learning</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-6">
                            <div className="hero-image">
                                <div className="floating-card">
                                    <i className="fas fa-graduation-cap"></i>
                                    <span>Learn & Grow</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Courses Section */}
            <section className="courses-section">
                <div className="container">
                    <div className="section-header text-center mb-5">
                        <h2 className="section-title">Featured Monthly Courses</h2>
                        <p className="section-subtitle">Choose from our carefully curated selection of professional courses</p>
                    </div>

                    <div className="row">
                        {monthies && monthies.data && monthies.data.map((monthly) => {
                            const category = getCategoryIcon(monthly.course?.type);
                            return (
                                <div key={monthly.id} className="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
                                    <div className="course-card">
                                        <div className="course-image">
                                            <img src={`/storage/${monthly.m_img}`} alt={monthly.course?.name} className="w-100" />
                                            <div className="course-badge">
                                                <span className="badge-text">Monthly</span>
                                            </div>
                                        </div>

                                        <div className="course-content">
                                            <div className="course-category">
                                                <i className={category.icon}></i>
                                                <span>{category.label}</span>
                                            </div>

                                            <h3 className="course-title">{monthly.course?.name}</h3>

                                            <p className="course-description">{monthly.course?.description}</p>

                                            <div className="course-features">
                                                <div className="feature-item">
                                                    <i className="fas fa-clock"></i>
                                                    <span>4 Weeks</span>
                                                </div>
                                                <div className="feature-item">
                                                    <i className="fas fa-users"></i>
                                                    <span>Live Classes</span>
                                                </div>
                                                <div className="feature-item">
                                                    <i className="fas fa-certificate"></i>
                                                    <span>Certificate</span>
                                                </div>
                                            </div>

                                            <div className="course-footer">
                                                <div className="price-section">
                                                    <span className="price-label">Monthly Fee</span>
                                                    <div className="price">
                                                        <span className="currency">Ks</span>
                                                        <span className="amount">{Number(monthly.course?.normal_price || 0).toLocaleString()}</span>
                                                    </div>
                                                </div>

                                                <Link href={`/yha/courses/monthly/${monthly.id}`} className="btn-enroll">
                                                    <span>View Details</span>
                                                    <i className="fas fa-arrow-right"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>

                    {/* Pagination */}
                    {monthies?.links && (
                        <div className="pagination-wrapper">
                            <div dangerouslySetInnerHTML={{ __html: monthies.links }} />
                        </div>
                    )}
                </div>
            </section>

            {/* CTA Section */}
            <section className="cta-section">
                <div className="container">
                    <div className="cta-content">
                        <h2>Ready to Start Your Learning Journey?</h2>
                        <p>Join thousands of students who have transformed their careers with our courses</p>
                        <Link href="/yha/courses/monthl" className="btn-cta">
                            <i className="fas fa-rocket"></i>
                            <span>Explore All Courses</span>
                        </Link>
                    </div>
                </div>
            </section>

            <Footer address={[]} />
        </>
    );
}
