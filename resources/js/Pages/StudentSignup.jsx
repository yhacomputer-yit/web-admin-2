import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function StudentSignup() {
    const siteName = 'YHA ACADEMY OF TECHNOLOGY';
    const title = `Student Sign Up - ${siteName}`;
    const description = 'Register as a student at YHA ACADEMY OF TECHNOLOGY. Join our programming, graphic design, and ICT courses today.';
    const ogImage = '/image/logo/logo.png';

    const keywords = [
        'student registration',
        'sign up',
        siteName,
        'course enrollment Myanmar',
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
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content={title} />
                <meta name="twitter:description" content={description} />
                <meta name="twitter:image" content={ogImage} />
                <link rel="canonical" href={window.location.href} />
            </Head>
            <div className="frontend-page">
                <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                .signup-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .signup-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .placeholder {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 20px;
                    border: 2px dashed #dee2e6;
                }

                .placeholder i {
                    font-size: 4rem;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }
            `}</style>

            <section className="signup-hero">
                <div className="container">
                    <h1>Student Signup</h1>
                    <p>Join our community and start your learning journey</p>
                </div>
            </section>

            <section className="signup-content">
                <div className="container">
                    <div className="placeholder">
                        <i className="fas fa-user-plus"></i>
                        <h2>Registration Form</h2>
                        <p>Student registration form will be available here</p>
                    </div>
                </div>
            </section>
        </div>
    </>
    );
}
