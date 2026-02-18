import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function Courses({ monthies }) {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
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
                        {monthies?.data?.map((monthly) => (
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
                                            {monthly.course?.type == 1 ? (
                                                <>
                                                    <i className="fas fa-code"></i>
                                                    <span>Programming</span>
                                                </>
                                            ) : monthly.course?.type == 2 ? (
                                                <>
                                                    <i className="fas fa-palette"></i>
                                                    <span>Graphic Design</span>
                                                </>
                                            ) : monthly.course?.type == 3 ? (
                                                <>
                                                    <i className="fas fa-laptop"></i>
                                                    <span>ICT</span>
                                                </>
                                            ) : (
                                                <>
                                                    <i className="fas fa-graduation-cap"></i>
                                                    <span>Course</span>
                                                </>
                                            )}
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
                        ))}
                    </div>

                    {/* Pagination */}
                    {monthies?.links && (
                        <div className="pagination-wrapper">
                            <div dangerouslySetInnerHTML={{ __html: monthies.links }} />
                        </div>
                    )}
                </div>
            </section>

            {/* Call to Action */}
            <section className="cta-section">
                <div className="container">
                    <div className="cta-content text-center">
                        <h2>Ready to Start Your Learning Journey?</h2>
                        <p>Join thousands of students who have transformed their careers with our courses</p>
                        <a href="#" className="btn-cta">Get Started Today</a>
                    </div>
                </div>
            </section>

            <style jsx>{`
                /* Hero Section */
                .courses-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    position: relative;
                    overflow: hidden;
                }

                .courses-hero::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                    opacity: 0.3;
                }

                .hero-title {
                    font-size: 3.5rem;
                    font-weight: 800;
                    margin-bottom: 1.5rem;
                    line-height: 1.2;
                }

                .hero-subtitle {
                    font-size: 1.2rem;
                    opacity: 0.9;
                    margin-bottom: 2rem;
                    line-height: 1.6;
                }

                .hero-stats {
                    display: flex;
                    gap: 2rem;
                    margin-top: 2rem;
                }

                .stat-item {
                    text-align: center;
                }

                .stat-number {
                    display: block;
                    font-size: 2rem;
                    font-weight: 700;
                    color: #ffd700;
                }

                .stat-label {
                    font-size: 0.9rem;
                    opacity: 0.8;
                }

                .hero-image {
                    position: relative;
                    height: 300px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .floating-card {
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    border-radius: 20px;
                    padding: 2rem;
                    text-align: center;
                    transform: translateY(0);
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-20px); }
                }

                .floating-card i {
                    font-size: 3rem;
                    margin-bottom: 1rem;
                    color: #ffd700;
                }

                .floating-card span {
                    font-size: 1.2rem;
                    font-weight: 600;
                }

                /* Courses Section */
                .courses-section {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .section-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin-bottom: 1rem;
                    color: #2c3e50;
                }

                .section-subtitle {
                    font-size: 1.1rem;
                    color: #6c757d;
                    max-width: 600px;
                    margin: 0 auto;
                }

                /* Course Card */
                .course-card {
                    background: white;
                    border-radius: 15px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    height: 100%;
                }

                .course-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                }

                .course-image {
                    position: relative;
                    height: 200px;
                    overflow: hidden;
                }

                .course-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }

                .course-card:hover .course-image img {
                    transform: scale(1.05);
                }

                .course-badge {
                    position: absolute;
                    top: 15px;
                    right: 15px;
                    background: #ff6c0f;
                    color: white;
                    padding: 5px 15px;
                    border-radius: 25px;
                    font-size: 0.8rem;
                    font-weight: 600;
                }

                .course-content {
                    padding: 1.5rem;
                }

                .course-category {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                    color: #ff6c0f;
                    font-weight: 600;
                    font-size: 0.9rem;
                }

                .course-category i {
                    font-size: 1rem;
                }

                .course-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    margin-bottom: 0.5rem;
                    color: #2c3e50;
                    line-height: 1.4;
                }

                .course-description {
                    color: #6c757d;
                    font-size: 0.9rem;
                    line-height: 1.6;
                    margin-bottom: 1rem;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .course-features {
                    display: flex;
                    gap: 1rem;
                    margin-bottom: 1.5rem;
                    flex-wrap: wrap;
                }

                .feature-item {
                    display: flex;
                    align-items: center;
                    gap: 0.3rem;
                    font-size: 0.8rem;
                    color: #6c757d;
                }

                .feature-item i {
                    color: #ff6c0f;
                    font-size: 0.8rem;
                }

                .course-footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding-top: 1rem;
                    border-top: 1px solid #e9ecef;
                }

                .price-section {
                    text-align: left;
                }

                .price-label {
                    font-size: 0.8rem;
                    color: #6c757d;
                    display: block;
                    margin-bottom: 0.25rem;
                }

                .price {
                    display: flex;
                    align-items: baseline;
                    gap: 0.2rem;
                }

                .currency {
                    font-size: 0.9rem;
                    color: #ff6c0f;
                    font-weight: 600;
                }

                .amount {
                    font-size: 1.3rem;
                    color: #ff6c0f;
                    font-weight: 700;
                }

                .btn-enroll {
                    background: #ff6c0f;
                    color: white;
                    padding: 0.75rem 1.5rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: background 0.3s ease;
                }

                .btn-enroll:hover {
                    background: #e65b00;
                    color: white;
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

                .btn-cta {
                    background: #ff6c0f;
                    color: white;
                    padding: 1rem 2rem;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 1.1rem;
                    display: inline-block;
                    transition: background 0.3s ease;
                }

                .btn-cta:hover {
                    background: #e65b00;
                    color: white;
                }

                /* Pagination */
                .pagination-wrapper {
                    margin-top: 3rem;
                    text-align: center;
                }

                .pagination-wrapper :global(.pagination) {
                    display: flex;
                    justify-content: center;
                    gap: 0.5rem;
                    list-style: none;
                    padding: 0;
                }

                .pagination-wrapper :global(.pagination li a) {
                    padding: 0.5rem 1rem;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    color: #6c757d;
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                .pagination-wrapper :global(.pagination li a:hover) {
                    background: #ff6c0f;
                    color: white;
                    border-color: #ff6c0f;
                }

                .pagination-wrapper :global(.pagination li.active a) {
                    background: #ff6c0f;
                    color: white;
                    border-color: #ff6c0f;
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .hero-title {
                        font-size: 2.5rem;
                    }
                    
                    .hero-stats {
                        justify-content: center;
                    }
                    
                    .course-footer {
                        flex-direction: column;
                        gap: 1rem;
                        align-items: stretch;
                    }
                    
                    .btn-enroll {
                        justify-content: center;
                    }
                }
            `}</style>
        </div>
    );
}
