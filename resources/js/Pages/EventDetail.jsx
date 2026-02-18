import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function EventDetail({ event, details }) {
    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    };

    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                /* Hero Section */
                .event-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    position: relative;
                    overflow: hidden;
                }

                .event-hero::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                    opacity: 0.3;
                }

                .event-breadcrumb {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin-bottom: 2rem;
                    font-size: 0.9rem;
                }

                .breadcrumb-link {
                    color: rgba(255, 255, 255, 0.8);
                    text-decoration: none;
                    transition: color 0.3s ease;
                }

                .breadcrumb-link:hover {
                    color: white;
                }

                .breadcrumb-separator {
                    color: rgba(255, 255, 255, 0.6);
                }

                .breadcrumb-current {
                    color: white;
                    font-weight: 600;
                }

                .event-date {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin-bottom: 1.5rem;
                    font-size: 1.1rem;
                    opacity: 0.9;
                }

                .event-date i {
                    color: #ffd700;
                }

                .event-title {
                    font-size: 3rem;
                    font-weight: 800;
                    margin-bottom: 2rem;
                    line-height: 1.2;
                }

                .event-actions {
                    display: flex;
                    gap: 1rem;
                }

                .btn-back {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                    padding: 1rem 2rem;
                    border-radius: 30px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 1rem;
                    transition: all 0.3s ease;
                }

                .btn-back:hover {
                    background: rgba(255, 255, 255, 0.3);
                    color: white;
                    transform: translateY(-2px);
                }

                .event-hero-image {
                    position: relative;
                    height: 400px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .floating-elements {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                }

                .floating-icon {
                    background: rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    width: 80px;
                    height: 80px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 1rem;
                    animation: float 3s ease-in-out infinite;
                }

                .floating-icon i {
                    font-size: 2rem;
                    color: #ffd700;
                }

                .floating-text {
                    background: rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    border-radius: 20px;
                    padding: 0.5rem 1.5rem;
                    font-weight: 600;
                }

                @keyframes float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-20px); }
                }

                /* Event Content Section */
                .event-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .content-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                }

                .section-title {
                    font-size: 2rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    color: #2c3e50;
                }

                .event-description {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #495057;
                }

                .event-description :global(p) {
                    margin-bottom: 1rem;
                }

                .event-description :global(ul), .event-description :global(ol) {
                    margin-bottom: 1rem;
                    padding-left: 2rem;
                }

                .event-description :global(li) {
                    margin-bottom: 0.5rem;
                }

                /* Sidebar */
                .event-sidebar {
                    position: sticky;
                    top: 100px;
                }

                .sidebar-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                }

                .sidebar-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    color: #2c3e50;
                }

                .info-list {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .info-item {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1rem;
                    background: #f8f9fa;
                    border-radius: 10px;
                }

                .info-item i {
                    color: #ff6c0f;
                    font-size: 1.2rem;
                    width: 20px;
                }

                .info-content {
                    display: flex;
                    flex-direction: column;
                    gap: 0.25rem;
                }

                .info-label {
                    font-size: 0.9rem;
                    color: #6c757d;
                }

                .info-value {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #2c3e50;
                }

                /* Event Gallery Section */
                .event-gallery {
                    padding: 80px 0;
                    background: white;
                }

                .section-header {
                    margin-bottom: 3rem;
                }

                .section-subtitle {
                    font-size: 1.1rem;
                    color: #6c757d;
                    max-width: 600px;
                    margin: 0 auto;
                }

                .gallery-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 2rem;
                }

                .gallery-item {
                    border-radius: 15px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease;
                }

                .gallery-item:hover {
                    transform: translateY(-10px);
                }

                .gallery-image {
                    width: 100%;
                    height: 250px;
                    object-fit: cover;
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .event-title {
                        font-size: 2rem;
                    }
                    
                    .event-hero-image {
                        height: 250px;
                    }
                    
                    .floating-icon {
                        width: 60px;
                        height: 60px;
                    }
                    
                    .floating-icon i {
                        font-size: 1.5rem;
                    }
                    
                    .gallery-grid {
                        grid-template-columns: 1fr;
                        gap: 1rem;
                    }
                }
            `}</style>

            {/* Hero Section */}
            <section className="event-hero">
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-6">
                            <div className="event-breadcrumb">
                                <Link href="/" className="breadcrumb-link">
                                    <i className="fas fa-home"></i> Home
                                </Link>
                                <span className="breadcrumb-separator">/</span>
                                <Link href="/yha/event" className="breadcrumb-link">Events</Link>
                                <span className="breadcrumb-separator">/</span>
                                <span className="breadcrumb-current">{event?.title}</span>
                            </div>

                            <div className="event-date">
                                <i className="fas fa-calendar-alt"></i>
                                <span>{formatDate(event?.edate)}</span>
                            </div>

                            <h1 className="event-title">{event?.title}</h1>

                            <div className="event-actions">
                                <Link href="/yha/event" className="btn-back">
                                    <i className="fas fa-arrow-left"></i>
                                    Back to Events
                                </Link>
                            </div>
                        </div>
                        <div className="col-lg-6">
                            <div className="event-hero-image">
                                <div className="floating-elements">
                                    <div className="floating-icon">
                                        <i className="fas fa-champagne-glasses"></i>
                                    </div>
                                    <div className="floating-text">
                                        <span>Special Event</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Event Content Section */}
            <section className="event-content">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-8">
                            <div className="content-card">
                                <h2 className="section-title">Event Details</h2>
                                <div className="event-description" dangerouslySetInnerHTML={{ __html: event?.aboute }} />
                            </div>
                        </div>
                        <div className="col-lg-4">
                            <div className="event-sidebar">
                                <div className="sidebar-card">
                                    <h3 className="sidebar-title">Event Information</h3>
                                    <div className="info-list">
                                        <div className="info-item">
                                            <i className="fas fa-calendar"></i>
                                            <div className="info-content">
                                                <span className="info-label">Date</span>
                                                <span className="info-value">{formatDate(event?.edate)}</span>
                                            </div>
                                        </div>
                                        <div className="info-item">
                                            <i className="fas fa-clock"></i>
                                            <div className="info-content">
                                                <span className="info-label">Time</span>
                                                <span className="info-value">6:00 PM - 9:00 PM</span>
                                            </div>
                                        </div>
                                        <div className="info-item">
                                            <i className="fas fa-map-marker-alt"></i>
                                            <div className="info-content">
                                                <span className="info-label">Location</span>
                                                <span className="info-value">YHA Computer Center</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Event Gallery Section */}
            {details && details.length > 0 && (
                <section className="event-gallery">
                    <div className="container">
                        <div className="section-header text-center">
                            <h2 className="section-title">Event Gallery</h2>
                            <p className="section-subtitle">Capturing the moments from our special event</p>
                        </div>

                        <div className="gallery-grid">
                            {details.map((detail) => (
                                <div key={detail.id} className="gallery-item">
                                    <img src={`/storage/${detail.images}`} alt="Event Image" className="gallery-image" />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </div>
    );
}
