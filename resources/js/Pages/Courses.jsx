import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Courses({ monthies, prog, graph, ict, address }) {
    const getCategoryIcon = (type) => {
        switch(type) {
            case 1: return { icon: 'fas fa-code', label: 'Programming' };
            case 2: return { icon: 'fas fa-palette', label: 'Graphic Design' };
            case 3: return { icon: 'fas fa-laptop', label: 'ICT' };
            default: return { icon: 'fas fa-graduation-cap', label: 'Course' };
        }
    };

    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
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
                    animation: float 3s ease-in-out infinite;
                }

                .floating-card i {
                    font-size: 3rem;
                    color: #ffd700;
                    margin-bottom: 1rem;
                    display: block;
                }

                .floating-card span {
                    font-size: 1.2rem;
                    font-weight: 600;
                }

                @keyframes float {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-20px); }
                }

                /* Courses Section */
                .courses-section {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .section-header {
                    margin-bottom: 4rem;
                }

                .section-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    color: #333;
                    margin-bottom: 1rem;
                }

                .section-subtitle {
                    font-size: 1.1rem;
                    color: #666;
                    max-width: 600px;
                    margin: 0 auto;
                }

                /* Course Cards */
                .course-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    transition: all 0.3s ease;
                    height: 100%;
                    position: relative;
                }

                .course-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                }

                .course-image {
                    position: relative;
                    overflow: hidden;
                    height: 200px;
                }

                .course-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }

                .course-card:hover .course-image img {
                    transform: scale(1.1);
                }

                .course-badge {
                    position: absolute;
                    top: 15px;
                    right: 15px;
                }

                .badge-text {
                    background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
                    color: white;
                    padding: 0.3rem 0.8rem;
                    border-radius: 20px;
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
                    color: #ff6b01;
                    font-size: 0.9rem;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }

                .course-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #333;
                    margin-bottom: 0.8rem;
                    line-height: 1.3;
                }

                .course-description {
                    color: #666;
                    font-size: 0.9rem;
                    line-height: 1.5;
                    margin-bottom: 1.5rem;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
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
                    color: #666;
                }

                .feature-item i {
                    color: #ff6b01;
                }

                .course-footer {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding-top: 1rem;
                    border-top: 1px solid #eee;
                }

                .price-section {
                    display: flex;
                    flex-direction: column;
                }

                .price-label {
                    font-size: 0.8rem;
                    color: #666;
                    margin-bottom: 0.2rem;
                }

                .price {
                    display: flex;
                    align-items: baseline;
                    gap: 0.2rem;
                }

                .currency {
                    font-size: 0.9rem;
                    color: #ff6b6b;
                    font-weight: 600;
                }

                .amount {
                    font-size: 1.2rem;
                    font-weight: 700;
                    color: #333;
                }

                .btn-enroll {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    background: linear-gradient(45deg, #ff6b01, #ffb347);
                    color: white;
                    padding: 0.8rem 1.5rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                }

                .btn-enroll:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(255, 107, 1, 0.4);
                    color: white;
                }

                /* Pagination */
                .pagination-wrapper {
                    display: flex;
                    justify-content: center;
                    margin-top: 3rem;
                }

                .pagination .page-link {
                    color: #ff6b01;
                    border: 1px solid #ff6b01;
                    margin: 0 0.2rem;
                    border-radius: 8px;
                    padding: 0.5rem 1rem;
                }

                .pagination .page-item.active .page-link {
                    background: #ff6b01;
                    border-color: #ff6b01;
                    color: white;
                }

                .pagination .page-link:hover {
                    background: #ff6b01;
                    color: white;
                }

                /* CTA Section */
                .cta-section {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 60px 0;
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
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    background: white;
                    color: #ff6b01;
                    padding: 1rem 2rem;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 1.1rem;
                    transition: all 0.3s ease;
                }

                .btn-cta:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                    color: #ff6b01;
                }

                /* Responsive Design */
                @media (max-width: 1200px) {
                    .hero-title {
                        font-size: 3rem;
                    }

                    .section-title {
                        font-size: 2.2rem;
                    }
                }

                @media (max-width: 992px) {
                    .hero-title {
                        font-size: 2.8rem;
                    }

                    .hero-subtitle {
                        font-size: 1.1rem;
                    }

                    .hero-stats {
                        gap: 1.5rem;
                    }

                    .course-content {
                        padding: 1.2rem;
                    }

                    .course-title {
                        font-size: 1.2rem;
                    }
                }

                @media (max-width: 768px) {
                    .courses-hero {
                        padding: 60px 0;
                    }

                    .hero-title {
                        font-size: 2.5rem;
                    }

                    .hero-subtitle {
                        font-size: 1rem;
                    }

                    .hero-stats {
                        flex-direction: column;
                        gap: 1rem;
                    }

                    .section-title {
                        font-size: 2rem;
                    }

                    .section-subtitle {
                        font-size: 1rem;
                    }

                    .course-footer {
                        flex-direction: column;
                        gap: 1rem;
                        align-items: stretch;
                    }

                    .btn-enroll {
                        justify-content: center;
                    }

                    .cta-content h2 {
                        font-size: 2rem;
                    }

                    .cta-content p {
                        font-size: 1rem;
                    }

                    .course-features {
                        gap: 0.8rem;
                    }
                }

                @media (max-width: 576px) {
                    .courses-hero {
                        padding: 50px 0;
                    }

                    .hero-title {
                        font-size: 2rem;
                    }

                    .hero-subtitle {
                        font-size: 0.9rem;
                    }

                    .courses-section {
                        padding: 50px 0;
                    }

                    .section-title {
                        font-size: 1.8rem;
                    }

                    .course-content {
                        padding: 1rem;
                    }

                    .course-title {
                        font-size: 1.1rem;
                    }

                    .course-description {
                        font-size: 0.85rem;
                    }

                    .course-features {
                        flex-direction: column;
                        gap: 0.5rem;
                    }

                    .feature-item {
                        font-size: 0.75rem;
                    }

                    .price .amount {
                        font-size: 1.1rem;
                    }

                    .btn-enroll {
                        padding: 0.7rem 1.2rem;
                        font-size: 0.85rem;
                    }

                    .cta-content h2 {
                        font-size: 1.8rem;
                    }

                    .cta-content p {
                        font-size: 0.9rem;
                    }

                    .btn-cta {
                        padding: 0.8rem 1.5rem;
                        font-size: 1rem;
                    }
                }

                @media (max-width: 480px) {
                    .hero-title {
                        font-size: 1.8rem;
                    }

                    .hero-subtitle {
                        font-size: 0.85rem;
                    }

                    .section-title {
                        font-size: 1.6rem;
                    }

                    .course-image {
                        height: 180px;
                    }

                    .course-content {
                        padding: 0.8rem;
                    }

                    .course-title {
                        font-size: 1rem;
                    }

                    .course-description {
                        font-size: 0.8rem;
                        -webkit-line-clamp: 2;
                    }

                    .price .amount {
                        font-size: 1rem;
                    }

                    .btn-enroll {
                        padding: 0.6rem 1rem;
                        font-size: 0.8rem;
                    }

                    .cta-content h2 {
                        font-size: 1.6rem;
                    }

                    .btn-cta {
                        padding: 0.7rem 1.2rem;
                        font-size: 0.9rem;
                    }
                }
            `}</style>
            {/* Hero Section */}
            <section className="courses-hero">
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-6">
                            <h1 className="hero-title">Discover Our Monthly Courses</h1>
                            <p className="hero-subtitle">Master new skills with our comprehensive monthly course programs designed for your success</p>
                            <div className="hero-stats">
                                <div className="stat-item">
                                    <span className="stat-number">{(() => {
                            const coursesData = Array.isArray(monthies) ? monthies : (monthies?.data || []);
                            return coursesData.length || 0;
                        })()}+</span>
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
                        {(() => {
                            const coursesData = Array.isArray(monthies) ? monthies : (monthies?.data || []);
                            return coursesData && coursesData.length > 0 ? (
                                coursesData.map((monthly) => {
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
                                })
                            ) : null;
                        })()}
                    </div>

                    {/* Pagination */}
                    {monthies && (monthies.links || (typeof monthies === 'object' && monthies !== null && 'links' in monthies)) && (
                        <div className="pagination-wrapper">
                            <div dangerouslySetInnerHTML={{ __html: monthies.links }} />
                        </div>
                    )}
                </div>
            </section>

            {/* CTA Section */}
            <section className="cta-section">
                <div className="container">
                    <div className="cta-content text-center">
                        <h2>Ready to Start Your Learning Journey?</h2>
                        <p>Join thousands of students who have transformed their careers with our courses</p>
                        <a href="#" className="btn-cta">Get Started Today</a>
                    </div>
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
