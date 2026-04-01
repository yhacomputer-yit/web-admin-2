import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function About({ prog, graph, ict, address }) {
    // Get address data
    const addr = address && address.length > 0 ? address[0] : null;
    
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} contactInfo={{ 
                address: addr?.address || '123 University Street, Tech City',
                phone: addr?.yphNo || '+1 (555) 123-4567',
                email: addr?.yEmail || 'info@yhauniversity.edu'
            }} />
            
            <style jsx>{`
                /* Premium About Page Styles - Matching Homepage Design */
                .about-section {
                    background: #ffffff;
                    position: relative;
                    overflow: hidden;
                    padding: 6rem 0;
                    margin-bottom: 0;
                    border-bottom: 1px solid #f8f9fa;
                }

                @media (max-width: 768px) {
                    .about-section {
                        padding: 4rem 0;
                    }
                }

                @media (max-width: 576px) {
                    .about-section {
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

                /* Modern About Cards - Premium Design */
                .modern-about-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
                    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                    border: 1px solid rgba(0,0,0,0.06);
                    position: relative;
                    padding: 2.5rem;
                    margin-bottom: 2rem;
                }

                .modern-about-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 8px 20px rgba(0,0,0,0.08);
                    border-color: rgba(0,0,0,0.1);
                }

                .about-section-title {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: #ff6b35;
                    margin-bottom: 1.5rem;
                    line-height: 1.3;
                    letter-spacing: -0.01em;
                    display: flex;
                    align-items: center;
                    gap: 0.8rem;
                }

                .about-section-title i {
                    font-size: 1.2rem;
                }

                .about-content {
                    font-size: 1.05rem;
                    color: #475569;
                    line-height: 1.7;
                    font-weight: 400;
                    margin-bottom: 2rem;
                }

                .about-values-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .about-values-list li {
                    display: flex;
                    align-items: flex-start;
                    gap: 1rem;
                    margin-bottom: 1.5rem;
                    padding: 1.2rem;
                    background: linear-gradient(135deg, #fafbfc, #f8fafc);
                    border-radius: 12px;
                    border: 1px solid rgba(0,0,0,0.06);
                    transition: all 0.3s ease;
                }

                .about-values-list li:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                    border-color: rgba(255,107,53,0.1);
                }

                .value-icon {
                    width: 40px;
                    height: 40px;
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 1rem;
                    flex-shrink: 0;
                    box-shadow: 0 4px 12px rgba(255,107,53,0.2);
                }

                .value-content {
                    flex: 1;
                }

                .value-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #1e293b;
                    margin-bottom: 0.5rem;
                    line-height: 1.4;
                }

                .value-description {
                    font-size: 0.95rem;
                    color: #64748b;
                    line-height: 1.6;
                    margin: 0;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .hit-title {
                        font-size: 1.8rem;
                    }
                    
                    .hit-subtitle {
                        font-size: 0.95rem;
                    }
                    
                    .modern-about-card {
                        padding: 1.5rem;
                    }
                    
                    .about-section-title {
                        font-size: 1.3rem;
                    }
                    
                    .about-content {
                        font-size: 1rem;
                    }
                    
                    .about-values-list li {
                        padding: 1rem;
                    }
                }

                @media (max-width: 576px) {
                    .hit-title {
                        font-size: 1.6rem;
                    }
                    
                    .hit-subtitle {
                        font-size: 0.9rem;
                    }
                    
                    .modern-about-card {
                        padding: 1.2rem;
                    }
                    
                    .about-section-title {
                        font-size: 1.2rem;
                    }
                    
                    .about-content {
                        font-size: 0.95rem;
                    }
                    
                    .about-values-list li {
                        flex-direction: column;
                        text-align: center;
                        padding: 1.2rem;
                    }
                    
                    .value-icon {
                        margin-bottom: 1rem;
                    }
                }
            `}</style>

            {/* About Section - Premium Design */}
            <section className="about-section">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fa-solid fa-graduation-cap"></i>
                            <span>About Us</span>
                        </div>
                        <h1 className="hit-title">
                            <span>Welcome To</span>
                            <span>YHA Computer</span>
                        </h1>
                        <p className="hit-subtitle">Welcome to our organization, where innovation meets excellence. We are a dynamic team of dedicated professionals committed to driving progress in various industries.</p>
                    </div>

                    {/* About Content */}
                    <div className="row justify-content-center">
                        <div className="col-lg-8">
                            {/* Mission Card */}
                            <div className="modern-about-card">
                                <h2 className="about-section-title">
                                    <i className="fa-solid fa-bullseye"></i>
                                    Our Mission
                                </h2>
                                <p className="about-content">
                                    Our mission is to empower individuals and organizations with knowledge and skills needed to thrive in the ever-evolving world of technology. We believe that by fostering a culture of learning and innovation, we can create a brighter future for all.
                                </p>
                            </div>

                            {/* Vision Card */}
                            <div className="modern-about-card">
                                <h2 className="about-section-title">
                                    <i className="fa-solid fa-eye"></i>
                                    Our Vision
                                </h2>
                                <p className="about-content">
                                    At our core, we are driven by a vision to be the go-to destination for those seeking to excel in the ever-evolving landscape of technology. We envision a world where every individual has access to the knowledge and resources they need to succeed, and where businesses thrive on a foundation of innovation and collaboration.
                                </p>
                            </div>

                            {/* Values Card */}
                            <div className="modern-about-card">
                                <h2 className="about-section-title">
                                    <i className="fa-solid fa-heart"></i>
                                    Our Values
                                </h2>
                                <ul className="about-values-list">
                                    <li>
                                        <div className="value-icon">
                                            <i className="fa-solid fa-shield-halved"></i>
                                        </div>
                                        <div className="value-content">
                                            <h3 className="value-title">Integrity</h3>
                                            <p className="value-description">We hold ourselves to the highest standards of honesty, transparency, and ethical conduct.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div className="value-icon">
                                            <i className="fa-solid fa-lightbulb"></i>
                                        </div>
                                        <div className="value-content">
                                            <h3 className="value-title">Creativity</h3>
                                            <p className="value-description">We embrace innovation and constantly seek new ideas to drive progress.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div className="value-icon">
                                            <i className="fa-solid fa-users"></i>
                                        </div>
                                        <div className="value-content">
                                            <h3 className="value-title">Collaboration</h3>
                                            <p className="value-description">We believe in the power of teamwork and celebrate the diversity of perspectives within our organization.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div className="value-icon">
                                            <i className="fa-solid fa-star"></i>
                                        </div>
                                        <div className="value-content">
                                            <h3 className="value-title">Customer Focus</h3>
                                            <p className="value-description">We prioritize delivering exceptional products and services that meet and exceed our customers' expectations.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer address={address} />
        </div>
    );
}
