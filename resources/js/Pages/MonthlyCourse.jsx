import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function MonthlyCourse() {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                .monthly-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .monthly-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .placeholder {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 20px;
                    border: 2px dashed #dee2e6;
                }

                .placeholder i {
                    font-size: 4rem;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }
            `}</style>

            <section className="monthly-hero">
                <div className="container">
                    <h1>Monthly Course Details</h1>
                    <p>Comprehensive information about our monthly course programs</p>
                </div>
            </section>

            <section className="monthly-content">
                <div className="container">
                    <div className="placeholder">
                        <i className="fas fa-calendar-alt"></i>
                        <h2>Course Details</h2>
                        <p>Detailed monthly course information will be available here</p>
                    </div>
                </div>
            </section>
        </div>
    );
}
