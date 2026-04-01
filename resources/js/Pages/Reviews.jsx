import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Reviews({ reviews, rating, sort, prog, graph, ict, address }) {
    const [currentPage, setCurrentPage] = useState(1);
    const reviewsPerPage = 6;
    
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
                /* Modern Review Page Styles - Matching Event Page */
                .reviews-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 3rem 0;
                    margin-bottom: 3rem;
                    border-radius: 0 0 30px 30px;
                    color: white;
                    text-align: center;
                }

                .reviews-hero h1 {
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin-bottom: 1rem;
                    letter-spacing: 1px;
                }

                .reviews-hero p {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    max-width: 600px;
                    margin: 0 auto;
                }

                .review-card {
                    background: #fff;
                    border-radius: 20px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    overflow: hidden;
                    transition: all 0.3s ease;
                    margin-bottom: 2rem;
                    border: 1px solid rgba(255,107,1,0.1);
                    position: relative;
                }

                .review-card:hover {
                    transform: translateY(-8px) scale(1.01);
                    box-shadow: 0 12px 40px rgba(255,107,1,0.15);
                }

                .review-avatar-section {
                    padding: 2rem;
                    background: linear-gradient(135deg, rgba(255,107,1,0.05) 0%, rgba(255,179,71,0.05) 100%);
                    text-align: center;
                    border-bottom: 1px solid rgba(255,107,1,0.1);
                }

                .review-avatar {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    object-fit: cover;
                    border: 3px solid #fff;
                    box-shadow: 0 4px 16px rgba(255,107,1,0.2);
                    margin-bottom: 1rem;
                }

                .review-name {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #333;
                    margin: 0;
                    margin-bottom: 0.5rem;
                }

                .review-rating {
                    color: #ffb347;
                    font-size: 1.1rem;
                    margin-bottom: 0.5rem;
                }

                .review-date {
                    color: #666;
                    font-size: 0.9rem;
                    background: rgba(255,107,1,0.1);
                    padding: 0.3rem 0.8rem;
                    border-radius: 15px;
                    display: inline-block;
                }

                .review-content {
                    padding: 2rem 1.5rem;
                }

                .review-text {
                    color: #555;
                    line-height: 1.6;
                    font-size: 1.05rem;
                    margin-bottom: 1.5rem;
                    font-style: italic;
                    position: relative;
                    padding-left: 2rem;
                }

                .review-text::before {
                    content: '"';
                    position: absolute;
                    left: 0;
                    top: -10px;
                    font-size: 3rem;
                    color: #ffb347;
                    opacity: 0.3;
                    font-family: Georgia, serif;
                }

                .filter-section {
                    background: rgba(255,255,255,0.95);
                    border-radius: 20px;
                    padding: 1.5rem 2rem;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    margin-bottom: 3rem;
                    border: 1px solid rgba(255,107,1,0.1);
                }

                .filter-buttons {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                    justify-content: center;
                    align-items: center;
                }

                .filter-btn {
                    padding: 0.6rem 1.2rem;
                    border: 2px solid #ffe5d0;
                    background: #fff;
                    color: #ff6b01;
                    border-radius: 20px;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    font-weight: 500;
                    font-size: 0.95rem;
                }

                .filter-btn:hover,
                .filter-btn.active {
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    color: white;
                    border-color: #ff6b01;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 16px rgba(255,107,1,0.18);
                }

                .pagination-container {
                    display: flex;
                    justify-content: center;
                    margin-top: 3rem;
                }

                .pagination .page-link {
                    color: #ff6b01;
                    border: 1px solid #ff6b01;
                    margin: 0 0.2rem;
                    border-radius: 8px;
                }

                .pagination .page-item.active .page-link {
                    background: #ff6b01;
                    border-color: #ff6b01;
                    color: white;
                }

                .pagination .page-link:hover {
                    background: #ff6b01;
                    color: white;
                }

                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: rgba(255,255,255,0.95);
                    border-radius: 20px;
                    border: 2px dashed #ffe5d0;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                }

                .empty-state i {
                    font-size: 3rem;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .reviews-hero {
                        padding: 2rem 0;
                        border-radius: 0 0 20px 20px;
                    }

                    .reviews-hero h1 {
                        font-size: 2rem;
                    }

                    .reviews-hero p {
                        font-size: 1rem;
                    }

                    .review-card {
                        border-radius: 14px;
                    }

                    .review-avatar-section {
                        padding: 1.5rem 1rem;
                    }

                    .review-avatar {
                        width: 60px;
                        height: 60px;
                    }

                    .review-name {
                        font-size: 1.1rem;
                    }

                    .review-content {
                        padding: 1.5rem 1rem;
                    }

                    .review-text {
                        font-size: 1rem;
                        padding-left: 1.5rem;
                    }

                    .filter-section {
                        padding: 1rem 1.5rem;
                        margin-bottom: 2rem;
                    }

                    .filter-btn {
                        padding: 0.5rem 1rem;
                        font-size: 0.85rem;
                    }
                }

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

                @media (max-width: 576px) {
                    .reviews-hero h1 {
                        font-size: 1.5rem;
                    }

                    .review-name {
                        font-size: 1rem;
                    }

                    .review-text {
                        font-size: 0.95rem;
                    }
                }
            `}</style>

            {/* Hero Section - Matching Event Page Style */}
            <section className="reviews-hero">
                <div className="container">
                    <h1>Student Reviews</h1>
                    <p>Discover what our students have to say about their learning experience and journey with YHA Computer Training Center.</p>
                </div>
            </section>

            {/* Reviews Content */}
            <section className="main">
                <div className="container">
                    {currentReviews && currentReviews.length > 0 ? (
                        <>
                            {currentReviews.map((review, index) => (
                                <div key={review.id} className="review-card">
                                    <div className="row g-0 align-items-center">
                                        {index % 2 === 0 ? (
                                            <>
                                                {/* First, third items... avatar left, content right */}
                                                <div className="col-lg-4">
                                                    <div className="review-avatar-section">
                                                        {review.photo ? (
                                                            <img src={`/storage/${review.photo}`} className="review-avatar" alt={review.name} />
                                                        ) : (
                                                            <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(review.name)}&background=ffb347&color=fff&size=80`} className="review-avatar" alt={review.name} />
                                                        )}
                                                        <h3 className="review-name">{review.name}</h3>
                                                        <div className="review-rating">
                                                            {[1,2,3,4,5].map((star) => (
                                                                <i key={star} className={star <= review.rating ? "fa-solid fa-star" : "fa-regular fa-star"}></i>
                                                            ))}
                                                        </div>
                                                        <div className="review-date">
                                                            <i className="fa-solid fa-calendar-alt me-2"></i>
                                                            {new Date(review.created_at).toLocaleDateString('en-US', {
                                                                year: 'numeric',
                                                                month: 'short',
                                                                day: 'numeric'
                                                            })}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="col-lg-8">
                                                    <div className="review-content">
                                                        <div className="review-text">{review.review}</div>
                                                    </div>
                                                </div>
                                            </>
                                        ) : (
                                            <>
                                                {/* Second, fourth items... content left, avatar right */}
                                                <div className="col-lg-8 order-lg-2">
                                                    <div className="review-content">
                                                        <div className="review-text">{review.review}</div>
                                                    </div>
                                                </div>
                                                <div className="col-lg-4 order-lg-1">
                                                    <div className="review-avatar-section">
                                                        {review.photo ? (
                                                            <img src={`/storage/${review.photo}`} className="review-avatar" alt={review.name} />
                                                        ) : (
                                                            <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(review.name)}&background=ffb347&color=fff&size=80`} className="review-avatar" alt={review.name} />
                                                        )}
                                                        <h3 className="review-name">{review.name}</h3>
                                                        <div className="review-rating">
                                                            {[1,2,3,4,5].map((star) => (
                                                                <i key={star} className={star <= review.rating ? "fa-solid fa-star" : "fa-regular fa-star"}></i>
                                                            ))}
                                                        </div>
                                                        <div className="review-date">
                                                            <i className="fa-solid fa-calendar-alt me-2"></i>
                                                            {new Date(review.created_at).toLocaleDateString('en-US', {
                                                                year: 'numeric',
                                                                month: 'short',
                                                                day: 'numeric'
                                                            })}
                                                        </div>
                                                    </div>
                                                </div>
                                            </>
                                        )}
                                    </div>
                                </div>
                            ))}

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
                        </>
                    ) : (
                        <div className="empty-state">
                            <i className="fa-solid fa-star"></i>
                            <h3>No Reviews Yet</h3>
                            <p>Be the first to share your experience with us!</p>
                        </div>
                    )}
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
