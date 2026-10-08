import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/gallery.css';

export default function Gallery({ prog, graph, ict }) {
    const siteName = 'YHA ACADEMY OF TECHNOLOGY';
    const title = `Gallery - ${siteName}`;
    const description = 'View our photo gallery from YHA ACADEMY OF TECHNOLOGY. Campus life, events, student activities, and training sessions.';
    const ogImage = '/image/logo/logo.png';

    const keywords = [
        'gallery Myanmar',
        siteName,
        'photos',
        'campus life',
        'student activities',
        'training photos',
        'Data Science',
        'AI',
        'Machine Learning',
        'Mobile Development',
        'Flutter',
        'Dart',
        'React',
        'Vue',
        'Laravel',
        'PHP',
        'JavaScript',
        'MERN Stack',
        'Web Development',
        'MySQL',
        'MongoDB'
    ].join(', ');

    return (
        <>
            <Head>
                <title>{title}</title>
                <meta name="description" content={description} />
                <meta name="keywords" content={keywords} />
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta property="og:image" content={ogImage} />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content={siteName} />
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:title" content={title} />
                <meta name="twitter:description" content={description} />
                <meta name="twitter:image" content={ogImage} />
                <link rel="canonical" href={window.location.href} />
            </Head>
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
    </>
    );
}
