import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Reviews({ reviews, rating, sort, prog, graph, ict, address }) {
    const [currentPage, setCurrentPage] = useState(1);
    const reviewsPerPage = 4;
    
    // Handle reviews data structure
    const reviewsData = Array.isArray(reviews) ? reviews : (reviews?.data || []);
    const totalPages = Math.ceil(reviewsData.length / reviewsPerPage);
    
    // Pagination logic
    const indexOfLastReview = currentPage * reviewsPerPage;
    const indexOfFirstReview = indexOfLastReview - reviewsPerPage;
    const currentReviews = reviewsData.slice(indexOfFirstReview, indexOfLastReview);
    
    const paginate = (pageNumber) => setCurrentPage(pageNumber);
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />

            <style jsx>{`
                /* Premium Reviews Page Styles - Matching Homepage Design */
                .reviews-section {
                    background: #ffffff;
                    position: relative;
                    overflow: hidden;
                    padding: 6rem 0;
                    margin-bottom: 0;
                    border-bottom: 1px solid #f8f9fa;
                }

                @media (max-width: 768px) {
                    .reviews-section {
                        padding: 4rem 0;
                    }
                }

                @media (max-width: 576px) {
                    .reviews-section {
                        padding: 3rem 0 !important;
                    }
                }

                /* Section Header - Premium Design */
                .hit-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.6rem;
                    background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
                    color: white;
                    padding: 0.6rem 1.8rem;
                    border-radius: 50px;
                    font-size: 0.85rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 1.2px;
                    margin-bottom: 2rem;
                    box-shadow: 0 4px 20px rgba(255,107,53,0.25);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                }

                .hit-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    line-height: 1.2;
                    letter-spacing: -0.02em;
                    color: #1e293b;
                }

                .hit-title span {
                    display: block;
                }

                .hit-title span:first-child {
                    color: #1e293b;
                }

                .hit-title span:last-child {
                    color: #ff6b35;
                }

                .hit-subtitle {
                    font-size: 1.1rem;
                    color: #64748b;
                    max-width: 700px;
                    margin: 0 auto;
                    line-height: 1.7;
                    font-weight: 400;
                    letter-spacing: 0.01em;
                }

                /* Modern Review Cards - Premium Design */
                .modern-review-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
                    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                    border: 1px solid rgba(0,0,0,0.06);
                    position: relative;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 2rem;
                }

                .modern-review-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 8px 20px rgba(0,0,0,0.08);
                    border-color: rgba(0,0,0,0.1);
                }

                .review-header {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1.5rem;
                    background: linear-gradient(135deg, #fafbfc, #f8fafc);
                    border-bottom: 1px solid rgba(0,0,0,0.06);
                }

                .review-avatar {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    object-fit: cover;
                    border: 3px solid rgba(255,107,53,0.1);
                }

                .review-info {
                    flex: 1;
                }

                .review-name {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #1e293b;
                    margin: 0;
                    line-height: 1.4;
                    letter-spacing: -0.01em;
                }

                .review-rating {
                    display: flex;
                    gap: 0.25rem;
                    margin: 0.5rem 0;
                }

                .review-rating i {
                    color: #ff6b35;
                    font-size: 0.9rem;
                }

                .review-date {
                    font-size: 0.8rem;
                    color: #64748b;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .review-content {
                    padding: 1.5rem;
                    flex: 1;
                }

                .review-text {
                    font-size: 0.95rem;
                    color: #475569;
                    line-height: 1.7;
                    font-weight: 400;
                    margin: 0;
                }

                .modern-no-reviews {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    border: 1px solid rgba(255,107,53,0.08);
                }

                .modern-no-reviews-icon {
                    width: 80px;
                    height: 80px;
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 2rem;
                    color: white;
                    font-size: 2rem;
                    box-shadow: 0 8px 20px rgba(255,107,53,0.3);
                }

                .modern-no-reviews h3 {
                    font-size: 1.8rem;
                    font-weight: 800;
                    color: #1e293b;
                    margin-bottom: 1rem;
                }

                .modern-no-reviews p {
                    font-size: 1rem;
                    color: #64748b;
                    margin-bottom: 2rem;
                    max-width: 400px;
                    margin-left: auto;
                    margin-right: auto;
                }

                .modern-browse-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.8rem;
                    padding: 0.8rem 1.5rem;
                    background: white;
                    color: #ff6b35;
                    border: 2px solid #ff6b35;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                }

                .modern-browse-btn:hover {
                    background: #ff6b35;
                    color: white;
                    border-color: transparent;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(255,107,53,0.3);
                }

                .modern-view-all {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.8rem;
                    padding: 1rem 2rem;
                    background: white;
                    color: #ff6b35;
                    border: 2px solid #ff6b35;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 700;
                    font-size: 1rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                }

                .modern-view-all:hover {
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    color: white;
                    border-color: transparent;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(255,107,53,0.3);
                }

                .modern-view-all i {
                    font-size: 0.9rem;
                    transition: transform 0.3s ease;
                }

                .modern-view-all:hover i {
                    transform: translateX(4px);
                }

                /* Pagination Styles - Matching Homepage */
                .pagination-container {
                    display: flex;
                    justify-content: center;
                    margin-top: 3rem;
                }

                .pagination-container .btn {
                    min-width: 40px;
                    height: 40px;
                    padding: 0.5rem 0.75rem;
                    border-radius: 8px;
                    font-size: 0.875rem;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }

                .pagination-container .btn:hover:not(:disabled) {
                    transform: translateY(-1px);
                }

                .pagination-container .btn:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }

                .pagination-container .btn-primary {
                    background: linear-gradient(135deg, #ff6b01, #ffb347);
                    border-color: #ff6b01;
                    color: white;
                }

                .pagination-container .btn-outline-primary {
                    background: transparent;
                    border-color: #ff6b01;
                    color: #ff6b01;
                }

                .pagination-container .btn-outline-primary:hover {
                    background: #ff6b01;
                    color: white;
                }

                .pagination-container .btn-outline-secondary {
                    background: transparent;
                    border-color: #6c757d;
                    color: #6c757d;
                }

                .pagination-container .btn-outline-secondary:hover:not(:disabled) {
                    background: #6c757d;
                    color: white;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .hit-title {
                        font-size: 1.8rem;
                    }
                    
                    .hit-subtitle {
                        font-size: 0.95rem;
                    }
                    
                    .review-header {
                        padding: 1rem;
                    }
                    
                    .review-content {
                        padding: 1rem;
                    }
                    
                    .review-text {
                        font-size: 0.9rem;
                    }
                    
                    .modern-no-reviews {
                        padding: 3rem 1rem;
                    }
                }

                @media (max-width: 576px) {
                    .hit-title {
                        font-size: 1.6rem;
                    }
                    
                    .hit-subtitle {
                        font-size: 0.9rem;
                    }
                    
                    .review-avatar {
                        width: 50px;
                        height: 50px;
                    }
                    
                    .review-name {
                        font-size: 1rem;
                    }
                    
                    .review-text {
                        font-size: 0.85rem;
                    }
                }
            `}</style>

            {/* Reviews Section - Premium Design */}
            <section className="reviews-section">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fa-solid fa-star"></i>
                            <span>Student Reviews</span>
                        </div>
                        <h1 className="hit-title">
                            <span>What Our Students Say</span>
                            <span>About Us</span>
                        </h1>
                        <p className="hit-subtitle">Discover what our students have to say about their learning experience and journey with YHA Computer Training Center.</p>
                    </div>

                    {/* Modern Reviews Grid */}
                    <div className="row g-4">
                        {currentReviews && currentReviews.length > 0 ? (
                            currentReviews.map((review) => (
                                <div key={review.id} className="col-xl-6 col-lg-6 col-md-12">
                                    <div className="modern-review-card">
                                        {/* Review Header */}
                                        <div className="review-header">
                                            {review.photo ? (
                                                <img src={`/storage/${review.photo}`} className="review-avatar" alt={review.name} />
                                            ) : (
                                                <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(review.name)}&background=ff6b35&color=fff&size=80`} className="review-avatar" alt={review.name} />
                                            )}
                                            <div className="review-info">
                                                <h3 className="review-name">{review.name}</h3>
                                                <div className="review-rating">
                                                    {[1,2,3,4,5].map((star) => (
                                                        <i key={star} className={star <= review.rating ? "fa-solid fa-star" : "fa-regular fa-star"}></i>
                                                    ))}
                                                </div>
                                                <div className="review-date">
                                                    <i className="fa-solid fa-calendar-alt"></i>
                                                    {new Date(review.created_at).toLocaleDateString('en-US', {
                                                        year: 'numeric',
                                                        month: 'short',
                                                        day: 'numeric'
                                                    })}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Review Content */}
                                        <div className="review-content">
                                            <p className="review-text">{review.review}</p>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="modern-no-reviews">
                                    <div className="modern-no-reviews-icon">
                                        <i className="fa-solid fa-star"></i>
                                    </div>
                                    <h3>No Reviews Yet</h3>
                                    <p>Be the first to share your experience with us!</p>
                                    <Link href="/contact" className="modern-browse-btn">
                                        <i className="fa-solid fa-message"></i>
                                        <span>Leave a Review</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Pagination */}
                    {(totalPages > 1 || (reviewsData && reviewsData.length > 0)) && (
                        <div className="pagination-container">
                            <div className="d-flex justify-content-center align-items-center gap-2">
                                <button
                                    className="btn btn-outline-secondary"
                                    onClick={() => paginate(currentPage - 1)}
                                    disabled={currentPage === 1}
                                >
                                    <i className="fa-solid fa-chevron-left"></i>
                                </button>

                                <div className="d-flex gap-1">
                                    {[...Array(totalPages)].map((_, index) => (
                                        <button
                                            key={index + 1}
                                            className={`btn ${currentPage === index + 1 ? 'btn-primary' : 'btn-outline-primary'}`}
                                            onClick={() => paginate(index + 1)}
                                        >
                                            {index + 1}
                                        </button>
                                    ))}
                                </div>

                                <button
                                    className="btn btn-outline-secondary"
                                    onClick={() => paginate(currentPage + 1)}
                                    disabled={currentPage === totalPages}
                                >
                                    <i className="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    )}

                    {/* Pagination Info */}
                    {(totalPages > 1 || (reviewsData && reviewsData.length > 0)) && (
                        <div className="text-center mt-3">
                            <small className="text-muted">
                                Showing {indexOfFirstReview + 1}-{Math.min(indexOfLastReview, reviewsData.length)} of {reviewsData.length} reviews
                            </small>
                        </div>
                    )}
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
