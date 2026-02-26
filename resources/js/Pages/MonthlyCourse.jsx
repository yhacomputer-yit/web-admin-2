import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function MonthlyCourse({ prog, graph, ict, address, monthies }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
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
                    {monthies && monthies.length > 0 ? (
                        <div className="row">
                            {monthies.map((monthly) => (
                                <div key={monthly.id} className="col-lg-8 col-md-10 mx-auto">
                                    <div className="card shadow-sm mb-4">
                                        <div className="card-body">
                                            <div className="row align-items-center">
                                                <div className="col-md-4">
                                                    {monthly.m_img && (
                                                        <img 
                                                            src={`/storage/${monthly.m_img}`} 
                                                            alt={monthly.course?.name}
                                                            className="img-fluid rounded"
                                                        />
                                                    )}
                                                </div>
                                                <div className="col-md-8">
                                                    <h3 className="card-title">{monthly.course?.name}</h3>
                                                    <p className="text-muted">{monthly.course?.description}</p>
                                                    
                                                    <div className="row mt-3">
                                                        <div className="col-md-6">
                                                            <strong>Monthly Fee:</strong>
                                                            <div className="text-primary h5">
                                                                Ks {Number(monthly.course?.normal_price || 0).toLocaleString()}
                                                            </div>
                                                        </div>
                                                        <div className="col-md-6">
                                                            <strong>Duration:</strong>
                                                            <div className="text-info">4 Weeks</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div className="mt-3">
                                                        <Link 
                                                            href={`/yha/courses/monthl`} 
                                                            className="btn btn-primary me-2"
                                                        >
                                                            <i className="fas fa-arrow-left"></i> Back to Courses
                                                        </Link>
                                                        {monthly.section && (
                                                            <span className="badge bg-success ms-2">
                                                                {monthly.section.name}
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="placeholder">
                            <i className="fas fa-calendar-alt"></i>
                            <h2>No Course Details Available</h2>
                            <p>Monthly course information is not available at the moment.</p>
                            <Link href="/yha/courses/monthl" className="btn btn-primary mt-3">
                                <i className="fas fa-arrow-left"></i> Back to Courses
                            </Link>
                        </div>
                    )}
                </div>
            </section>
            <Footer address={address} />
        </div>
    );
}
