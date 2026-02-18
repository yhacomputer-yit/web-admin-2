import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function About() {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                .about-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .about-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .about-card {
                    background: white;
                    border-radius: 20px;
                    padding: 3rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }

                .about-card h2 {
                    color: #2c3e50;
                    margin-bottom: 1.5rem;
                }

                .about-card p {
                    color: #495057;
                    line-height: 1.8;
                    font-size: 1.1rem;
                }
            `}</style>

            <section className="about-hero">
                <div className="container">
                    <h1>About Us</h1>
                    <p>Learn more about YHA Computer Training Center</p>
                </div>
            </section>

            <section className="about-content">
                <div className="container">
                    <div className="about-card">
                        <h2>Welcome to YHA Computer Training Center</h2>
                        <p>We are dedicated to providing quality computer education and training to help students build successful careers in technology.</p>
                        <p>Our mission is to empower individuals with the skills and knowledge needed to thrive in the digital age.</p>
                    </div>
                </div>
            </section>
        </div>
    );
}
