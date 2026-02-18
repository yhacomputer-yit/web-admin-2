import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Reviews({ reviews, rating, sort, prog, graph, ict, address }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            <style jsx>{`
                .reviews-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .reviews-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .review-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    margin-bottom: 2rem;
                    transition: transform 0.3s ease;
                }

                .review-card:hover {
                    transform: translateY(-5px);
                }

                .review-header {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    margin-bottom: 1.5rem;
                }

                .review-avatar {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    object-fit: cover;
                    background: #f8f9fa;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                    color: #ff6b01;
                }

                .review-info h4 {
                    margin: 0;
                    color: #2c3e50;
                    font-size: 1.1rem;
                }

                .review-rating {
                    color: #ffd700;
                    margin-bottom: 0.5rem;
                }

                .review-text {
                    color: #495057;
                    line-height: 1.6;
                    margin-bottom: 1rem;
                }

                .review-date {
                    color: #6c757d;
                    font-size: 0.9rem;
                }

                .filter-section {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    margin-bottom: 3rem;
                }

                .filter-buttons {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .filter-btn {
                    padding: 0.5rem 1rem;
                    border: 2px solid #e9ecef;
                    background: #f8f9fa;
                    color: #495057;
                    border-radius: 20px;
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                .filter-btn:hover,
                .filter-btn.active {
                    background: #ff6b01;
                    color: white;
                    border-color: #ff6b01;
                }

                .pagination {
                    display: flex;
                    justify-content: center;
                    margin-top: 3rem;
                }

                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 15px;
                    border: 2px dashed #dee2e6;
                }

                .empty-state i {
                    font-size: 3rem;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }
            `}</style>

            <section className="reviews-hero">
                <div className="container">
                    <h1>Student Reviews</h1>
                    <p>See what our students have to say about their learning experience</p>
                </div>
            </section>

            <section className="reviews-content">
                <div className="container">
                    <div className="filter-section">
                        <div className="filter-buttons">
                            <Link href="/reviews" className={`filter-btn ${!rating ? 'active' : ''}`}>
                                All Reviews
                            </Link>
                            <Link href="/reviews?rating=5" className={`filter-btn ${rating == 5 ? 'active' : ''}`}>
                                ⭐⭐⭐⭐⭐
                            </Link>
                            <Link href="/reviews?rating=4" className={`filter-btn ${rating == 4 ? 'active' : ''}`}>
                                ⭐⭐⭐⭐
                            </Link>
                            <Link href="/reviews?rating=3" className={`filter-btn ${rating == 3 ? 'active' : ''}`}>
                                ⭐⭐⭐
                            </Link>
                        </div>
                    </div>

                    {reviews && reviews.data && reviews.data.length > 0 ? (
                        <>
                            {reviews.data.map((review) => (
                                <div key={review.id} className="review-card">
                                    <div className="review-header">
                                        <div className="review-avatar">
                                            {review.photo ? (
                                                <img src={`/storage/${review.photo}`} alt={review.name} />
                                            ) : (
                                                <i className="fas fa-user"></i>
                                            )}
                                        </div>
                                        <div className="review-info">
                                            <h4>{review.name}</h4>
                                            <div className="review-rating">
                                                {[...Array(5)].map((_, i) => (
                                                    <i key={i} className={`fas fa-star ${i < review.rating ? '' : 'text-muted'}`}></i>
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                    <div className="review-text">{review.review}</div>
                                    <div className="review-date">
                                        {new Date(review.created_at).toLocaleDateString()}
                                    </div>
                                </div>
                            ))}

                            <div className="pagination">
                                <div dangerouslySetInnerHTML={{ __html: reviews.links }} />
                            </div>
                        </>
                    ) : (
                        <div className="empty-state">
                            <i className="fas fa-star"></i>
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
