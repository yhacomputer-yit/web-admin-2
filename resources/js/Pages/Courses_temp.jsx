import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Courses({ monthies }) {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            {/* Hero Section */}
            <section className="courses-hero">
                <div className="container">
                    <h1 className="text-center text-white mb-4">Discover Our Monthly Courses</h1>
                    <p className="text-center text-white-50 mb-5">Master new skills with our comprehensive monthly course programs designed for your success</p>
                    <div className="row text-center">
                        <div className="col-md-4">
                            <div className="stat-item">
                                <span className="stat-number">{monthies?.total || 0}+</span>
                                <span className="stat-label">Courses Available</span>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="stat-item">
                                <span className="stat-number">100%</span>
                                <span className="stat-label">Practical Learning</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Courses Section */}
            <section className="py-5">
                <div className="container">
                    <h2 className="text-center mb-5">Featured Monthly Courses</h2>
                    <p className="text-center text-muted mb-5">Choose from our carefully curated selection of professional courses</p>

                    <div className="row">
                        {monthies && monthies.data && monthies.data.map((monthly) => (
                            <div key={monthly.id} className="col-lg-4 col-md-6 mb-4">
                                <div className="card h-100">
                                    <img src={`/storage/${monthly.m_img}`} className="card-img-top" alt={monthly.course?.name} style={{height: '200px', objectFit: 'cover'}} />
                                    <div className="card-body d-flex flex-column">
                                        <div className="mb-2">
                                            <span className="badge bg-primary">Monthly</span>
                                        </div>
                                        <h5 className="card-title">{monthly.course?.name}</h5>
                                        <p className="card-text text-muted">{monthly.course?.description}</p>
                                        <div className="mb-3">
                                            <small className="text-muted">
                                                <i className="fas fa-clock me-1"></i> 4 Weeks
                                                <span className="ms-3"><i className="fas fa-users me-1"></i> Live Classes</span>
                                                <span className="ms-3"><i className="fas fa-certificate me-1"></i> Certificate</span>
                                            </small>
                                        </div>
                                        <div className="mt-auto">
                                            <div className="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small className="text-muted">Monthly Fee</small>
                                                    <div className="fw-bold text-primary">Ks {Number(monthly.course?.normal_price || 0).toLocaleString()}</div>
                                                </div>
                                                <Link href={`/yha/courses/monthly/${monthly.id}`} className="btn btn-primary">
                                                    View Details
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Pagination */}
                    {monthies?.links && (
                        <div className="d-flex justify-content-center mt-4">
                            <div dangerouslySetInnerHTML={{ __html: monthies.links }} />
                        </div>
                    )}
                </div>
            </section>

            <Footer address={[]} />
        </div>
    );
}
