import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function StudentSignup() {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                .signup-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .signup-content {
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

            <section className="signup-hero">
                <div className="container">
                    <h1>Student Signup</h1>
                    <p>Join our community and start your learning journey</p>
                </div>
            </section>

            <section className="signup-content">
                <div className="container">
                    <div className="placeholder">
                        <i className="fas fa-user-plus"></i>
                        <h2>Registration Form</h2>
                        <p>Student registration form will be available here</p>
                    </div>
                </div>
            </section>
        </div>
    );
}
