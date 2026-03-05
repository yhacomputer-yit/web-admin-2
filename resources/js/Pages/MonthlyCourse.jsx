import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function MonthlyCourse({ prog, graph, ict, address, monthies }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            <style jsx>{`
                .course-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    color: white;
                    padding: 60px 0 30px 0;
                    text-align: center;
                }

                .main {
                    width: 100%;
                    margin-top: 0;
                }

                .card { 
                    background: #fff; 
                }
                
                .list-group-item { 
                    border: none; 
                    padding-left: 0; 
                }

                @media (max-width: 991.98px) {
                    .card { margin-bottom: 1.5rem; }
                }

                @media (max-width: 767.98px) {
                    .main .card.h-100 { margin-top: 1.2rem; }
                }

                .enroll-btn {
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    color: #fff !important;
                    box-shadow: 0 2px 8px rgba(255,107,1,0.10);
                    border: none;
                    transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
                }

                .enroll-btn:hover {
                    background: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
                    color: #fff !important;
                    transform: translateY(-2px) scale(1.04);
                    box-shadow: 0 6px 18px rgba(255,107,1,0.18);
                }

                .contact-btn {
                    background: #fff;
                    color: #ff6b01 !important;
                    border: 2px solid #ff6b01;
                    transition: background 0.2s, color 0.2s, border 0.2s, transform 0.2s;
                }

                .contact-btn:hover {
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    color: #fff !important;
                    border-color: #ff6b01;
                    transform: translateY(-2px) scale(1.04);
                }
            `}</style>

            {/* Hero/Header Section */}
            <section className="course-hero">
                <div className="container">
                    <h1 className="fw-bold mb-2" style={{fontSize: '2.2rem'}}>Monthly Courses Detail</h1>
                    <p style={{opacity: 0.9, maxWidth: '600px', margin: '0 auto'}}>Explore our monthly courses with flexible schedules and hands-on learning. Find the right course for you and enroll today!</p>
                </div>
            </section>

            <div className="main w-100 mt-0">
                <div className="container">
                    {monthies && monthies.length > 0 ? (
                        monthies.map((monthly) => (
                            <div key={monthly.id}>
                                <div className="row w-100 mb-5 d-flex justify-content-between align-items-center">
                                    {/* course image */}
                                    <div className="col-12 col-lg-6 mb-4">
                                        <div className="card shadow-sm border-0 rounded-4 h-100">
                                            {monthly.m_img && (
                                                <img 
                                                    className="w-100 rounded-4" 
                                                    src={`/storage/${monthly.m_img}`}
                                                    alt=""
                                                    style={{objectFit: 'cover', maxHeight: '340px'}}
                                                />
                                            )}
                                        </div>
                                    </div>

                                    {/* course detail */}
                                    <div className="col-12 col-lg-6">
                                        <div className="card shadow-sm border-0 rounded-4 p-4 h-100">
                                            <h2 className="fw-bold mb-3" style={{color: '#ff6b01'}}>{monthly.course?.name}</h2>
                                            <ul className="list-unstyled mb-4">
                                                <li className="mb-2">
                                                    <i className="fa-solid fa-money-bill-wave me-2" style={{color: '#ff6b01'}}></i> 
                                                    <strong>Price:</strong> Ks {Number(monthly.course?.normal_price || 0).toLocaleString()}
                                                </li>
                                                <li className="mb-2">
                                                    <i className="fa-solid fa-calendar-days me-2" style={{color: '#ff6b01'}}></i> 
                                                    <strong>Date:</strong> {monthly.start_date} / {monthly.end_date}
                                                </li>
                                                <li className="mb-2">
                                                    <i className="fa-solid fa-clock me-2" style={{color: '#ff6b01'}}></i> 
                                                    <strong>Time:</strong> {monthly.section?.start} - {monthly.section?.end} ({monthly.section?.name})
                                                </li>
                                                <li className="mb-2">
                                                    <i className="fa-solid fa-users me-2" style={{color: '#ff6b01'}}></i> 
                                                    <strong>Limited Seat:</strong> {monthly.limited_seat} Student
                                                </li>
                                            </ul>
                                            
                                            <div className="mb-4">
                                                <h5 className="fw-bold mb-2" style={{color: '#ff6b01'}}>Subjects</h5>
                                                <ul className="list-group list-group-flush">
                                                    {monthly.course?.subjects && monthly.course.subjects.length > 0 ? (
                                                        monthly.course.subjects.map((subject) => (
                                                            <li key={subject.id} className="list-group-item">{subject.name}</li>
                                                        ))
                                                    ) : (
                                                        <li className="list-group-item text-muted">No subjects available.</li>
                                                    )}
                                                </ul>
                                            </div>
                                            
                                            <div className="d-flex gap-3 align-items-center justify-content-center mt-3 flex-wrap">
                                                <a href="#" className="btn enroll-btn px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                                                    <i className="fa-solid fa-user-plus"></i> Enroll Now
                                                </a>
                                                <a href="#" className="btn contact-btn px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                                                    <i className="fa-solid fa-phone"></i> Contact Us
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* course description session */}
                                <div className="row w-100 px-md-5 px-2 mb-4">
                                    <div className="col-12 w-100">
                                        <div className="border p-4 w-100 shadow-sm rounded-4 bg-white">
                                            <h4 className="primary-color mb-3" style={{color: '#ff6b01'}}>Description</h4>
                                            <p className="lh-lg mb-0" style={{color: '#333'}} dangerouslySetInnerHTML={{ __html: monthly.m_desc || 'No description available.' }}></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="row w-100 mb-5 d-flex justify-content-between align-items-center">
                            <div className="col-12">
                                <div className="card shadow-sm border-0 rounded-4 p-4 h-100">
                                    <h2 className="fw-bold mb-3" style={{color: '#ff6b01'}}>No Course Details Available</h2>
                                    <p>Monthly course information is not available at the moment.</p>
                                    <div className="d-flex gap-3 align-items-center justify-content-center mt-3 flex-wrap">
                                        <a href="/yha/courses/monthl" className="btn enroll-btn px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                                            <i className="fa-solid fa-arrow-left"></i> Back to Courses
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                    <hr className="d-block" />
                </div>
            </div>
            <Footer address={address} />
        </div>
    );
}
