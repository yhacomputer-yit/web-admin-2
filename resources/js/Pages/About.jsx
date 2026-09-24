import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/about.css';

export default function About({ prog, graph, ict, address }) {
    const addr = address && address.length > 0 ? address[0] : null;

    return (
        <div className="frontend-page">
            <Navigation
                prog={prog}
                graph={graph}
                ict={ict}
                contactInfo={{
                    address: addr?.address || '123 University Street, Tech City',
                    phone: addr?.yphNo || '+1 (555) 123-4567',
                    email: addr?.yEmail || 'info@yhauniversity.edu',
                }}
            />

            {/* ========== About Hero Section ========== */}
            <section className="about-hero-section mt-5">
                <div className="container">
                    <div className="about-hero-wrapper">
                        {/* Left - Image */}
                        <div className="about-hero-image">
                            <img
                                src="/image/logo/logo.png"
                                alt="YHA Computer Training Center"
                            />
                        </div>

                        {/* Right - Content */}
                        <div className="about-hero-content">
                            <h1 className="about-hero-title">
                                Learn about YHA Computer
                            </h1>

                            <h2 className="about-hero-subtitle">
                                YHA Computer Training Center
                            </h2>

                            <p className="about-hero-text">
                                YHA Computer Training Center is a leading technology education
                                institute dedicated to empowering students with practical skills
                                in programming, design, data science, and ICT. Since our founding,
                                we have been committed to delivering industry-relevant training
                                that transforms careers and builds the next generation of tech
                                professionals.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========== Mission / Vision / Values ========== */}
            <section className="about-details-section">
                <div className="container">
                    <div className="about-details-list">
                        {/* Mission */}
                        <div className="about-detail-card">
                            <div className="detail-icon">
                                <i className="fa-solid fa-bullseye"></i>
                            </div>
                            <h3 className="detail-title">Our Mission</h3>
                            <p className="detail-text">
                                To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.
                            </p>
                        </div>

                        {/* Vision */}
                        <div className="about-detail-card">
                            <div className="detail-icon">
                                <i className="fa-solid fa-eye"></i>
                            </div>
                            <h3 className="detail-title">Our Vision</h3>
                            <p className="detail-text">
                                To become the leading destination for technology education,
                                where every student gains the tools and confidence to succeed
                                in a rapidly changing digital world.
                            </p>
                        </div>


                        {/* Why and How choose our class */}
                        <div className="about-detail-card">
                            <div className="detail-icon">
                                <i className="fa-solid fa-eye"></i>
                            </div>
                            <h3 className="detail-title"> ဘာကြောင့် YHA မှာ အတန်းတေ တက်သင့်တာလဲ </h3>
                            <p className="detail-text">
                                To become the leading destination for technology education,
                                where every student gains the tools and confidence to succeed
                                in a rapidly changing digital world.
                            </p>
                        </div>

                    </div>
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
