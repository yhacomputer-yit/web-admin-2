import { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
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
    const { url } = usePage();

    useEffect(() => {
        // Reset typing effect when component mounts or URL changes
        setTypedText('');
        let index = 0;
        
        // Clear any existing timers
        const timer = setInterval(() => {
            if (index < fullText.length) {
                setTypedText(fullText.slice(0, index + 1));
                index++;
            } else {
                clearInterval(timer);
            }
        }, 80); // Slightly slower for better visibility
        
        // Cleanup function
        return () => {
            clearInterval(timer);
        };
    }, [url, fullText]); // Re-run when URL changes or fullText changes

    // Get address data
    const addr = address && address.length > 0 ? address[0] : null;

    return (
        <div className="frontend-page" key={url}>
            <Navigation prog={prog} graph={graph} ict={ict} />

            {/* Hero Slider Section */}
            <section id="home" className="pb-4">
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
                            <h1>Build Your Future With <span style={{whiteSpace:'nowrap'}}>Technology</span></h1>
                            <p>Join YHA Computer Training Center to learn programming, design, and ICT skills from the best instructors. Start your journey today!</p>
                            <Link href="/yha/courses/monthl" className="btn-uiverse">Explore Courses</Link>
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
            <section id="about1" className="py-5">
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

            {/* Student Numbers Section - Hidden */}
            {/* <section id="stu_number">
                <div className="container">
                    <div className="row">
                        <div className="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 justify-content-end">
                            <div className="card hidden1">
                                <span>YHA</span>
                                <h3 id="number" data-goal="20">0</h3>
                                <p>courses</p>
                            </div>
                        </div>
                        <div className="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 justify-content-center">
                            <div className="card hidden2">
                                <span>YHA</span>
                                <div className="d-flex">
                                    <h3>1k+</h3>
                                </div>
                                <p>Students</p>
                            </div>
                        </div>
                        <div className="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 justify-content-start">
                            <div className="card hidden3">
                                <span>YHA</span>
                                <div className="d-flex">
                                    <h3 id="number" data-goal="50">0</h3>
                                    <h3>+</h3>
                                </div>
                                <p>Batches</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section> */}

            {/* Monthly Courses Section */}
            <section className="edu-section mt-5 mb-5" id="course">
                <div className="container">
                    <h2 className="edu-section-title">
                        <i className="fa-solid fa-book-open"></i> Monthly Courses
                    </h2>
                    <div className="row g-4">
                        {monthies && monthies.map((monthly) => (
                            <div key={monthly.id} className="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div className="edu-card">
                                    <img className="edu-card-img" src={`/storage/${monthly.m_img}`} alt={monthly.course?.name} />
                                    <div className="edu-card-body">
                                        <h5 className="edu-card-title">{monthly.course?.name}</h5>
                                        <p className="edu-card-desc">{monthly.course?.description}</p>
                                        <div className="edu-card-footer">
                                            <span className="edu-card-price">
<span className="text-orange-primary">Ks /-</span> {monthly.course?.normal_price}
                                            </span>
                                            <Link href={`/yha/courses/monthly/${monthly.id}`} className="btn-uiverse">View More</Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                        {monthies && monthies.length > 3 && (
                            <div className="mt-4 text-center">
                                <Link href="/yha/courses/monthl" className="btn-uiverse">View More</Link>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Events Section */}
            <section className="mt-5 mb-5" id="event">
                <div className="container">
                    <div className="edu-glass-heading">
                        <span className="edu-icon">
                            <i className="fa-solid fa-calendar-days"></i>
                        </span>
                        <h3>Latest Events</h3>
                        <div className="edu-subtitle">
                            Stay updated with our latest workshops, seminars, and special events
                        </div>
                    </div>
                    <div className="row w-100">
                        {events && events.slice(0, 3).map((event) => (
                            <div key={event.id} className="mb-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div className="edu-card event-card">
                                    <img className="edu-card-img" src={`/storage/${event.image}`} alt={event.title} />
                                    <div className="edu-card-body">
                                        <h6 className="edu-card-title">{event.title}</h6>
                                        <div className="mb-2 event-date">
<i className="fa-solid fa-calendar-alt text-orange-primary"></i>
                                            <span style={{fontSize: '14px', color: '#666'}}>
                                                {new Date(event.edate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                            </span>
                                        </div>
                                        <p className="edu-card-desc clamped-text">{event.aboute?.replace(/<[^>]*>/g, '')}</p>
                                        <div className="edu-card-footer">
                                            <Link href={`/yha/eventDetail/${event.id}`} className="btn-uiverse">View Details</Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                        {events && events.length > 3 && (
                            <div className="mt-4 text-center">
                                <Link href="/yha/event" className="btn-uiverse">View All Events</Link>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Student Projects Section */}
            <section className="mt-5 mb-5" id="projects">
                <div className="container">
                    <div className="edu-glass-heading">
                        <span className="edu-icon">
                            <i className="fa-solid fa-code"></i>
                        </span>
                        <h3>Student Projects</h3>
                        <div className="edu-subtitle">
                            Showcasing innovative projects created by our talented students
                        </div>
                    </div>
                    <div className="m-auto w-100 row">
                        {projects && projects.slice(0, 3).map((project) => (
                            <div key={project.id} className="mb-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div className="edu-card project-card">
                                    <img src={`/storage/${project.image}`} className="edu-card-img" alt={project.title} />
                                    <div className="edu-card-body">
                                        <h5 className="edu-card-title">{project.title}</h5>
                                        <div className="mb-2 project-course">
    <i className="fa-solid fa-graduation-cap text-orange-primary"></i>
<span className="text-orange-primary font-bold">{project.course?.name || 'Unknown Course'}</span>
                                        </div>
                                        <p className="edu-card-desc clamped-text">{project.desc}</p>
                                        <div className="gap-2 mb-3 project-links d-flex justify-content-center">
                                            {project.github ? (
                                                <a href={project.github} className="project-link github-link btn-uiverse" target="_blank" rel="noopener noreferrer">
                                                    <i className="fa-brands fa-github"></i>
                                                    <span>GitHub</span>
                                                </a>
                                            ) : (
                                                <span className="project-link github-link disabled" style={{opacity: 0.5, cursor: 'not-allowed'}}>
                                                    <i className="fa-brands fa-github"></i>
                                                    <span>GitHub</span>
                                                </span>
                                            )}
                                            {project.demo ? (
                                                <a href={project.demo} className="project-link demo-link btn-uiverse" target="_blank" rel="noopener noreferrer">
                                                    <i className="fa-solid fa-play"></i>
                                                    <span>Live Demo</span>
                                                </a>
                                            ) : (
                                                <span className="project-link demo-link disabled" style={{opacity: 0.5, cursor: 'not-allowed'}}>
                                                    <i className="fa-solid fa-play"></i>
                                                    <span>Live Demo</span>
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                        {projects && projects.length > 3 && (
                            <div className="mt-4 text-center">
                                <Link href="/yha/project" className="btn-uiverse">View All Projects</Link>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* FAQ Section */}
            <section id="faq" className="py-5 bg-gray-light" style={{borderTop: '2px solid #f3f3f3'}}>
                <div className="container">
                    <div className="mb-4 edu-glass-heading">
                        <span className="edu-icon"><i className="fa-solid fa-question-circle"></i></span>
                        <h3>Frequently Asked Questions</h3>
                        <div className="edu-subtitle">Find answers to common questions about our courses and center</div>
                    </div>
                    <div className="accordion" id="faqAccordion">
                        <div className="accordion-item">
                            <h2 className="accordion-header" id="faq1">
                                <button className="accordion-button faq-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true">
                                    What courses does YHA offer?
                                </button>
                            </h2>
                            <div id="collapse1" className="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                <div className="accordion-body faq-body">
                                    We offer a wide range of courses including programming, web development, graphic design, and ICT skills for all levels.
                                </div>
                            </div>
                        </div>
                        <div className="accordion-item">
                            <h2 className="accordion-header" id="faq2">
                                <button className="accordion-button collapsed faq-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false">
                                    How can I enroll in a course?
                                </button>
                            </h2>
                            <div id="collapse2" className="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                <div className="accordion-body faq-body">
                                    You can enroll online through our website or visit our center for in-person registration.
                                </div>
                            </div>
                        </div>
                        <div className="accordion-item">
                            <h2 className="accordion-header" id="faq3">
                                <button className="accordion-button collapsed faq-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false">
                                    Are there any prerequisites for joining?
                                </button>
                            </h2>
                            <div id="collapse3" className="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                <div className="accordion-body faq-body">
                                    Most beginner courses require no prior experience. Advanced courses may have prerequisites.
                                </div>
                            </div>
                        </div>
                        <div className="accordion-item">
                            <h2 className="accordion-header" id="faq4">
                                <button className="accordion-button collapsed faq-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false">
                                    Do you provide certificates?
                                </button>
                            </h2>
                            <div id="collapse4" className="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                <div className="accordion-body faq-body">
                                    Yes, we provide certificates upon successful completion of each course.
                                </div>
                            </div>
                        </div>
                        <div className="accordion-item">
                            <h2 className="accordion-header" id="faq5">
                                <button className="accordion-button collapsed faq-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false">
                                    How can I contact support?
                                </button>
                            </h2>
                            <div id="collapse5" className="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                                <div className="accordion-body faq-body">
                                    You can contact us via phone, email, or visit our center.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Reviews Section */}
            {homeReviews && homeReviews.length > 0 && (
                <section id="reviews" className="py-5 review-section-modern position-relative">
                    <div className="container review-section-modern-container">
                        <div className="mb-5 text-center review-modern-heading">
                            <span className="review-modern-icon"><i className="fa-solid fa-star"></i></span>
                            <h2 className="review-modern-title">Student Reviews</h2>
                            <div className="review-modern-accent"></div>
                            <div className="review-modern-subtitle">Real voices. Real experiences. See what our students say!</div>
                        </div>
                        <div id="reviewSlider" className="carousel slide review-modern-carousel" data-bs-ride="carousel" data-bs-interval="5000">
                            <div className="carousel-inner">
                                {homeReviews.map((review, index) => (
                                    <div key={review.id} className={`carousel-item ${index === 0 ? 'active' : ''}`}>
                                        <div className="d-flex justify-content-center">
                                            <div className="text-center review-modern-card">
                                                <div className="review-modern-avatar-wrap">
                                                    {review.photo ? (
                                                        <img src={`/storage/${review.photo}`} className="review-modern-avatar" alt={review.name} />
                                                    ) : (
                                                        <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(review.name)}&background=ffb347&color=fff`} className="review-modern-avatar" alt={review.name} />
                                                    )}
                                                </div>
                                                <div className="review-modern-quote"><i className="fa-solid fa-quote-left"></i></div>
                                                <div className="review-modern-text">{review.review}</div>
                                                <div className="gap-2 mt-3 mb-1 d-flex align-items-center justify-content-center">
                                                    <span className="review-modern-name">{review.name}</span>
                                                    <span className="review-modern-stars">
                                                        {[1,2,3,4,5].map((star) => (
                                                            <i key={star} className={star <= review.rating ? "fa-solid fa-star" : "fa-regular fa-star"}></i>
                                                        ))}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                            <button className="carousel-control-prev review-modern-arrow" type="button" data-bs-target="#reviewSlider" data-bs-slide="prev">
                                <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span className="visually-hidden">Previous</span>
                            </button>
                            <button className="carousel-control-next review-modern-arrow" type="button" data-bs-target="#reviewSlider" data-bs-slide="next">
                                <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                <span className="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div className="mt-4 text-center">
                            <Link href="/reviews" className="btn-uiverse">View All Reviews</Link>
                        </div>
                    </div>
                </section>
            )}

            {/* Contact Section */}
            <section id="contact" className="py-5 bg-white" style={{borderTop: '2px solid #f3f3f3'}}>
                <div className="container">
                    <div className="row g-4 align-items-stretch">
                        <div className="mb-4 col-lg-6 col-12">
                            <div className="p-4 shadow-sm card h-100 contact-card-light" style={{borderRadius: '20px'}}>
                                <div className="mb-3 d-flex align-items-center" style={{gap:'0.7rem'}}>
                                    <span style={{fontSize:'2rem', color:'#ff6b01'}}><i className="fa-solid fa-headset"></i></span>
                                    <h3 className="mb-0" style={{fontWeight:'800', color:'#222', letterSpacing:'1px'}}>Contact Us</h3>
                                </div>
                                {addr && (
                                    <>
                                        <div className="mb-3 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light"><i className="fa-solid fa-location-dot"></i></span>
                                            <span className="contact-label-light">Address:</span>
                                            <span className="contact-value-light">{addr.address || '-'}</span>
                                        </div>
                                        <div className="mb-3 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light"><i className="fa-solid fa-phone"></i></span>
                                            <span className="contact-label-light">Phone:</span>
                                            <span className="contact-value-light">{addr.yphNo || '-'}</span>
                                        </div>
                                        <div className="mb-3 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light"><i className="fa-solid fa-envelope"></i></span>
                                            <span className="contact-label-light">Email:</span>
                                            <span className="contact-value-light">{addr.yEmail || '-'}</span>
                                        </div>
                                        <div className="mb-2 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light"><i className="fa-solid fa-clock"></i></span>
                                            <span className="contact-label-light">Open Hours:</span>
                                            <span className="contact-value-light">{addr.OpenClose || '-'}</span>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                        <div className="mb-4 col-lg-6 col-12">
                            <div className="p-2 shadow-sm card h-100 map-card-light" style={{borderRadius: '20px', overflow:'hidden'}}>
                                <div className="mb-2 d-flex align-items-center" style={{gap:'0.7rem'}}>
                                    <span style={{fontSize:'1.5rem', color:'#ff6b01'}}><i className="fa-solid fa-map"></i></span>
                                    <h4 className="mb-0" style={{fontWeight:'700', color:'#222', letterSpacing:'0.5px'}}>Our Location</h4>
                                </div>
                                <div className="map-responsive-light">
                                    <iframe
                                        src={addr?.map_url || "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d238.68470692968666!2d96.12988827305935!3d16.828572950010972!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2smm!4v1689075973621!5m2!1sen!2smm"}
                                        width="100%"
                                        height="280"
                                        style={{border:0, borderRadius:'16px'}}
                                        allowFullScreen=""
                                        loading="lazy"
                                        referrerPolicy="no-referrer-when-downgrade"
                                    ></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Go to Top Button */}
            <button id="goToTopBtn" title="Go to top"
                className="shadow-orange-glow text-white text-xl" style={{
                    display:'none',
                    position:'fixed',
                    bottom:'32px',
                    right:'32px',
                    zIndex:9999,
                    background: 'var(--primary-orange)',
                    border:'none',
                    borderRadius:'50%',
                    width:'48px',
                    height:'48px',
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
