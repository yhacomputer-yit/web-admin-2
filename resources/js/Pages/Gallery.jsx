import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/gallery.css';

export default function Gallery({ prog, graph, ict }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
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
