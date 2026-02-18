import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function Gallery() {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                .gallery-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .gallery-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .gallery-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 2rem;
                    margin-top: 3rem;
                }

                .gallery-item {
                    background: white;
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

                .gallery-placeholder {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 20px;
                    border: 2px dashed #dee2e6;
                }

                .gallery-placeholder i {
                    font-size: 4rem;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }
            `}</style>

            <section className="gallery-hero">
                <div className="container">
                    <h1>Gallery</h1>
                    <p>Explore our collection of memorable moments and achievements</p>
                </div>
            </section>

            <section className="gallery-content">
                <div className="container">
                    <div className="gallery-placeholder">
                        <i className="fas fa-images"></i>
                        <h2>Coming Soon</h2>
                        <p>Our gallery will be updated soon with latest photos and events</p>
                    </div>
                </div>
            </section>
        </div>
    );
}
