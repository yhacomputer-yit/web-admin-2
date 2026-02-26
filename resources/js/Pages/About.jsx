import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function About({ prog, graph, ict, address }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            <style jsx>{`
                .about-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    color: white;
                    padding: 60px 0 40px 0;
                    text-align: center;
                }

                .about-hero h1 {
                    letter-spacing: 1px;
                }

                .about-content-section {
                    background: #f8f9fa;
                    padding: 60px 0;
                }

                .about-card {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 8px 32px rgba(255,107,1,0.08);
                    padding: 2.5rem 2rem;
                    transition: box-shadow 0.2s;
                }

                .about-card:hover {
                    box-shadow: 0 16px 48px rgba(255,107,1,0.13);
                }

                .about-values-list {
                    font-size: 1.08rem;
                    color: #444;
                    padding-left: 1.2rem;
                }

                @media (max-width: 768px) {
                    .about-hero {
                        padding: 36px 0 24px 0;
                    }
                    .about-card {
                        padding: 1.2rem 0.7rem;
                    }
                }
            `}</style>

            {/* Hero Section */}
            <section className="about-hero">
                <div className="container">
                    <div style={{fontSize: '3rem', marginBottom: '1rem'}}>
                        <i className="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h1 className="fw-bold mb-3" style={{fontSize: '2.5rem'}}>About Us</h1>
                    <p style={{opacity: 0.95, maxWidth: '600px', margin: '0 auto', fontSize: '1.15rem'}}>
                        Welcome to our organization, where innovation meets excellence. We are a dynamic team of dedicated professionals committed to driving progress in various industries.
                    </p>
                </div>
            </section>

            {/* About Content Section */}
            <section className="about-content-section">
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-lg-8">
                            <div className="about-card">
                                <h2 className="mb-3" style={{color: '#ff6b01', fontWeight: '700'}}>Our Mission</h2>
                                <p style={{fontSize: '1.1rem', color: '#555'}}>
                                    Our mission is to empower individuals and organizations with knowledge and skills needed to thrive in the ever-evolving world of technology. We believe that by fostering a culture of learning and innovation, we can create a brighter future for all.
                                </p>
                                
                                <h2 className="mb-3 mt-5" style={{color: '#ff6b01', fontWeight: '700'}}>Our Vision</h2>
                                <p style={{fontSize: '1.1rem', color: '#555'}}>
                                    At our core, we are driven by a vision to be the go-to destination for those seeking to excel in the ever-evolving landscape of technology. We envision a world where every individual has access to the knowledge and resources they need to succeed, and where businesses thrive on a foundation of innovation and collaboration.
                                </p>
                                
                                <h2 className="mb-3 mt-5" style={{color: '#ff6b01', fontWeight: '700'}}>Our Values</h2>
                                <ul className="about-values-list">
                                    <li><strong>Integrity:</strong> We hold ourselves to the highest standards of honesty, transparency, and ethical conduct.</li>
                                    <li><strong>Creativity:</strong> We embrace innovation and constantly seek new ideas to drive progress.</li>
                                    <li><strong>Collaboration:</strong> We believe in the power of teamwork and celebrate the diversity of perspectives within our organization.</li>
                                    <li><strong>Customer Focus:</strong> We prioritize delivering exceptional products and services that meet and exceed our customers' expectations.</li>
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
