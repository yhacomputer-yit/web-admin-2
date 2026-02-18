import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Reviews({ reviews, rating, sort, prog, graph, ict, address }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            {/* Hero Section */}
            <section className="bg-primary text-white py-5">
                <div className="container">
                    <h1 className="text-center mb-3">Student Reviews</h1>
                    <p className="text-center text-white-50">See what our students have to say about their learning experience</p>
                </div>
            </section>

            {/* Reviews Content */}
            <section className="py-5">
                <div className="container">
                    {/* Filter Section */}
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="d-flex justify-content-center gap-2 flex-wrap">
                                <Link href="/reviews" className={`btn ${!rating ? 'btn-primary' : 'btn-outline-primary'}`}>
                                    All Reviews
                                </Link>
                                <Link href="/reviews?rating=5" className={`btn ${rating == 5 ? 'btn-primary' : 'btn-outline-primary'}`}>
                                    ⭐⭐⭐⭐⭐
                                </Link>
                                <Link href="/reviews?rating=4" className={`btn ${rating == 4 ? 'btn-primary' : 'btn-outline-primary'}`}>
                                    ⭐⭐⭐⭐
                                </Link>
                                <Link href="/reviews?rating=3" className={`btn ${rating == 3 ? 'btn-primary' : 'btn-outline-primary'}`}>
                                    ⭐⭐⭐
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Reviews Grid */}
                    {reviews && reviews.data && reviews.data.length > 0 ? (
                        <>
                            <div className="row">
                                {reviews.data.map((review) => (
                                    <div key={review.id} className="col-lg-4 col-md-6 mb-4">
                                        <div className="card h-100">
                                            <div className="card-body">
                                                <div className="d-flex align-items-center mb-3">
                                                    <div className="rounded-circle bg-light p-3 me-3">
                                                        {review.photo ? (
                                                            <img src={`/storage/${review.photo}`} alt={review.name} className="rounded-circle" style={{width: '60px', height: '60px', objectFit: 'cover'}} />
                                                        ) : (
                                                            <div className="text-center" style={{width: '60px', height: '60px'}}>
                                                                <i className="fas fa-user text-muted"></i>
                                                            </div>
                                                        )}
                                                    </div>
                                                    <div>
                                                        <h5 className="card-title mb-1">{review.name}</h5>
                                                        <div className="text-warning mb-2">
                                                            {[...Array(5)].map((_, i) => (
                                                                <i key={i} className={`fas fa-star ${i < review.rating ? '' : 'text-muted'}`}></i>
                                                            ))}
                                                        </div>
                                                    </div>
                                                </div>
                                                <p className="card-text">{review.review}</p>
                                                <div className="text-muted small">
                                                    {new Date(review.created_at).toLocaleDateString()}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Pagination */}
                            <div className="d-flex justify-content-center">
                                <div dangerouslySetInnerHTML={{ __html: reviews.links }} />
                            </div>
                        </>
                    ) : (
                        <div className="text-center py-5">
                            <div className="card">
                                <div className="card-body">
                                    <i className="fas fa-star fa-3x text-warning mb-3"></i>
                                    <h3>No Reviews Yet</h3>
                                    <p className="text-muted">Be the first to share your experience with us!</p>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
