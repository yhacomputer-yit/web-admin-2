import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/about.css';

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
