import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function Gallery({ prog, graph, ict }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            <style jsx>{`
                .gallery-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 3rem 0;
                    margin-bottom: 3rem;
                    border-radius: 0 0 30px 30px;
                    color: white;
                    text-align: center;
                }

                .gallery-hero h1 {
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin-bottom: 1rem;
                    letter-spacing: 1px;
                }

                .gallery-hero p {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    max-width: 600px;
                    margin: 0 auto;
                }

                .gallery-content {
                    padding: 60px 0;
                    background: #f8f9fa;
                }

                .gallery-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 2rem;
                    margin-top: 3rem;
                }

                .gallery-item {
                    background: #fff;
                    border-radius: 20px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    overflow: hidden;
                    transition: all 0.3s ease;
                    margin-bottom: 2rem;
                    border: 1px solid rgba(255,107,1,0.1);
                    position: relative;
                }

                .gallery-item:hover {
                    transform: translateY(-8px) scale(1.01);
                    box-shadow: 0 12px 40px rgba(255,107,1,0.15);
                }

                .gallery-image {
                    width: 100%;
                    aspect-ratio: 16/9;
                    height: auto;
                    max-height: 320px;
                    object-fit: cover;
                    transition: transform 0.3s ease, box-shadow 0.3s;
                    border-radius: 16px;
                    box-shadow: 0 2px 12px rgba(255,107,1,0.07);
                    border: 2px solid #fff;
                }

                .gallery-item:hover .gallery-image {
                    transform: scale(1.05);
                    box-shadow: 0 6px 24px rgba(255,107,1,0.13);
                }

                .gallery-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0,0,0,0.7);
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .gallery-item:hover .gallery-overlay {
                    opacity: 1;
                }

                .gallery-overlay i {
                    font-size: 2rem;
                    margin-bottom: 0.5rem;
                }

                .gallery-overlay-text {
                    font-size: 1.2rem;
                    font-weight: 600;
                }

                @media (max-width: 768px) {
                    .gallery-hero {
                        padding: 2rem 0;
                        border-radius: 0 0 20px 20px;
                    }
                    .gallery-hero h1 {
                        font-size: 2rem;
                    }
                    .gallery-hero p {
                        font-size: 1rem;
                    }
                    .gallery-grid {
                        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                        gap: 1.5rem;
                    }
                }
            `}</style>

            {/* Hero Section */}
            <section className="gallery-hero">
                <div className="container">
                    <h1>Gallery</h1>
                    <p>Explore our collection of memorable moments and achievements</p>
                </div>
            </section>

            {/* Gallery Grid */}
            <section className="gallery-content">
                <div className="container">
                    <div className="gallery-grid">
                        {/* Sample Gallery Items - Replace with actual data */}
                        {[1, 2, 3, 4, 5, 6].map((item) => (
                            <div key={item} className="gallery-item">
                                <div className="gallery-image">
                                    <img 
                                        src={`/image/gallery${item}.jpg`}
                                        alt={`Gallery Image ${item}`}
                                    />
                                    <div className="gallery-overlay">
                                        <i className="fas fa-search-plus"></i>
                                        <span className="gallery-overlay-text">View Details</span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            <Footer address={address} />
        </div>
    );
}
