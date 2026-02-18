import { useEffect, useState } from 'react';
import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Homepage({
    abouts,
    aboutDesc,
    address,
    monthies,
    events,
    projects,
    homeReviews,
    sliders,
    teacher,
    prog,
    graph,
    ict
}) {
    const [typedText, setTypedText] = useState('');
    const fullText = "Unlock your full potential through curiosity";

    useEffect(() => {
        let index = 0;
        const timer = setInterval(() => {
            if (index <= fullText.length) {
                setTypedText(fullText.slice(0, index));
                index++;
            } else {
                clearInterval(timer);
            }
        }, 60);
        return () => clearInterval(timer);
    }, []);

    // Get address data
    const addr = address && address.length > 0 ? address[0] : null;

    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            {/* Hero Slider Section */}
            <section id="home">
                <div className="container">
                    <div id="carouselExample" className="carousel slide hero-slider" data-bs-ride="carousel">
                        <div className="carousel-inner">
                            {sliders && sliders.map((slider, index) => (
                                <div key={slider.id} className={`carousel-item ${index === 0 ? 'active' : ''}`}>
                                    <img
                                        src={`/storage/${slider.image}`}
                                        className="d-block w-100"
                                        alt={`Slide ${index + 1}`}
                                    />
                                </div>
                            ))}
                        </div>
                        <div className="hero-glass-overlay"></div>
                        <div className="hero-caption">
                            <img className="hero-logo" src="/image/logo/logo.png" alt="YHA Logo" />
                            <h1>Build Your Future With Technology</h1>
                            <p>Join YHA Computer Training Center to learn programming, design, and ICT skills from the best instructors. Start your journey today!</p>
                            <Link href="/yha/courses/monthl" className="hero-btn">Explore Courses</Link>
                        </div>
                        <button className="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span className="visually-hidden">Previous</span>
                        </button>
                        <button className="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span className="carousel-control-next-icon" aria-hidden="true"></span>
                            <span className="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </section>

            {/* About Section */}
            <section id="about1">
                <div className="container">
                    <div className="mb-4">
                        <h3 className="typewriter-heading">
                            <span id="typewriter-text">{typedText}</span>
                        </h3>
                    </div>
                    <div className="flex-wrap row justify-content-center align-items-center g-5 flex-lg-nowrap">
                        {abouts && abouts.length > 0 && (
                            <div className="col-lg-6 d-flex justify-content-center">
                                <div className="about-imgs-flex">
                                    {abouts.slice(0, 3).map((about, index) => (
                                        <div key={index} className="about-img-card-modern">
                                            <img src={`/storage/${about.image}`} alt={`About image ${index+1}`} />
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {aboutDesc && aboutDesc.length > 0 && (
                            <div className="col-lg-6 d-flex justify-content-center">
                                <div className="about-text-modern glass-effect">
                                    <h2 className="mb-3">Welcome to YHA Programming Training Center!</h2>
                                    <div className="section" dangerouslySetInnerHTML={{ __html: aboutDesc[0].desc }}></div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Courses Section */}
            <section id="course">
                <div className="container">
                    <div className="section-header text-center">
                        <h2 className="section-title">Our Courses</h2>
                        <p className="section-subtitle">Choose from our comprehensive course offerings</p>
                    </div>
                    <div className="row">
                        {prog && prog.slice(0, 3).map((course) => (
                            <div key={course.id} className="col-md-4 mb-4">
                                <div className="course-card-modern">
                                    <div className="course-image-wrapper">
                                        <img src={`/storage/${course.image}`} alt={course.name} />
                                        <div className="course-overlay">
                                            <h3>{course.name}</h3>
                                            <Link href={`/yha/course/${course.id}`} className="course-btn">Learn More</Link>
                                        </div>
                                    </div>
                                    <div className="course-content">
                                        <h4>{course.name}</h4>
                                        <p>{course.description}</p>
                                        <div className="course-meta">
                                            <span className="duration">{course.duration} Hours</span>
                                            <span className="price">Ks {course.normal_price}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Events Section */}
            <section id="event">
                <div className="container">
                    <div className="section-header text-center">
                        <h2 className="section-title">Upcoming Events</h2>
                        <p className="section-subtitle">Join our workshops and seminars</p>
                    </div>
                    <div className="row">
                        {events && events.slice(0, 3).map((event) => (
                            <div key={event.id} className="col-md-4 mb-4">
                                <div className="event-card-modern">
                                    <div className="event-image-wrapper">
                                        <img src={`/storage/${event.image}`} alt={event.title} />
                                    </div>
                                    <div className="event-content">
                                        <h4>{event.title}</h4>
                                        <p className="event-date">{new Date(event.edate).toLocaleDateString()}</p>
                                        <Link href={`/yha/eventDetail/${event.id}`} className="event-btn">View Details</Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Projects Section */}
            <section id="project">
                <div className="container">
                    <div className="section-header text-center">
                        <h2 className="section-title">Student Projects</h2>
                        <p className="section-subtitle">See what our students have created</p>
                    </div>
                    <div className="row">
                        {projects && projects.slice(0, 3).map((project) => (
                            <div key={project.id} className="col-md-4 mb-4">
                                <div className="project-card-modern">
                                    <div className="project-image-wrapper">
                                        <img src={`/storage/${project.image}`} alt={project.title} />
                                    </div>
                                    <div className="project-content">
                                        <h4>{project.title}</h4>
                                        <p>{project.desc}</p>
                                        <div className="project-links">
                                            {project.github && (
                                                <a href={project.github} className="project-link github-link" target="_blank" rel="noopener noreferrer">
                                                    <i className="fab fa-github"></i> GitHub
                                                </a>
                                            )}
                                            {project.demo && (
                                                <a href={project.demo} className="project-link demo-link" target="_blank" rel="noopener noreferrer">
                                                    <i className="fas fa-play"></i> Demo
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Reviews Section */}
            <section id="reviews">
                <div className="container">
                    <div className="section-header text-center">
                        <h2 className="section-title">Student Reviews</h2>
                        <p className="section-subtitle">What our students say about us</p>
                    </div>
                    <div className="row justify-content-center">
                        {homeReviews && homeReviews.map((review) => (
                            <div key={review.id} className="col-lg-4 col-md-6 mb-4">
                                <div className="review-modern-card">
                                    <div className="review-modern-avatar-wrap">
                                        {review.photo ? (
                                            <img src={`/storage/${review.photo}`} alt={review.name} className="review-modern-avatar" />
                                        ) : (
                                            <div className="review-modern-avatar d-flex align-items-center justify-content-center">
                                                <i className="fas fa-user"></i>
                                            </div>
                                        )}
                                    </div>
                                    <div className="review-modern-quote">"</div>
                                    <div className="review-modern-text">{review.review}</div>
                                    <div className="review-modern-name">{review.name}</div>
                                    <div className="review-modern-stars">
                                        {[...Array(5)].map((_, i) => (
                                            <i key={i} className={`fas fa-star ${i < review.rating ? '' : 'text-muted'}`}></i>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Teachers Section */}
            <section id="teachers">
                <div className="container">
                    <div className="section-header text-center">
                        <h2 className="section-title">Our Teachers</h2>
                        <p className="section-subtitle">Learn from experienced instructors</p>
                    </div>
                    <div className="row">
                        {teacher && teacher.slice(0, 4).map((teacher) => (
                            <div key={teacher.id} className="col-lg-3 col-md-6 mb-4">
                                <div className="teacher-card-modern">
                                    <div className="teacher-image-wrapper">
                                        <img src={`/storage/${teacher.image}`} alt={teacher.name} />
                                    </div>
                                    <div className="teacher-content">
                                        <h4>{teacher.name}</h4>
                                        <p className="teacher-position">{teacher.position?.name}</p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Scroll to Top Button */}
            <button
                className="btn-scroll-top"
                style={{
                    position:'fixed',
                    bottom:'32px',
                    right:'32px',
                    zIndex:9999,
                    background:'#ff6b01',
                    color:'#fff',
                    border:'none',
                    borderRadius:'50%',
                    width:'48px',
                    height:'48px',
                    boxShadow:'0 2px 8px rgba(255,107,1,0.18)',
                    fontSize:'1.7rem',
                    cursor:'pointer'
                }}
                onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
            >
                <i className="fa-solid fa-arrow-up"></i>
            </button>
            <Footer address={address} />
        </div>
    );
}
