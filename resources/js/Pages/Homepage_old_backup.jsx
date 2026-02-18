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
            
            <style jsx>{`
                /* Hero Slider Styles */
                .hero-slider {
                    position: relative;
                    min-height: 320px;
                    max-height: 600px;
                    overflow: hidden;
                    border-radius: 24px;
                    box-shadow: 0 8px 32px 0 rgba(0,0,0,0.13), 0 1.5px 8px 0 rgba(255,107,1,0.08);
                    margin-bottom: 2.5rem;
                    margin-top: 1.2rem;
                }
                .hero-slider .carousel-inner {
                    border-radius: 24px;
                    overflow: hidden;
                }
                .hero-slider .carousel-item img {
                    object-fit: cover;
                    width: 100%;
                    height: 420px;
                    filter: brightness(0.85) saturate(1.1);
                    border-radius: 24px;
                    transition: height 0.3s;
                }
                .hero-glass-overlay {
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(255,255,255,0.32);
                    backdrop-filter: blur(1px);
                    border-radius: 24px;
                    z-index: 2;
                    pointer-events: none;
                }
                .hero-caption {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    max-width: 80%;
                    left: 10%;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(255,255,255,0.72);
                    border-radius: 18px;
                    box-shadow: 0 4px 24px rgba(255,107,1,0.10);
                    padding: 2.2rem 2.2rem 2rem 2.2rem;
                    border-left: 6px solid #ff6b01;
                    border-bottom: 2px solid #ffb347;
                    position: absolute;
                    z-index: 3;
                }
                .hero-caption .hero-logo {
                    height: 48px;
                    margin-bottom: 10px;
                }
                .hero-caption h1 {
                    font-size: 2.2rem;
                    font-weight: 800;
                    margin-bottom: 1.1rem;
                    letter-spacing: 1.5px;
                }
                .hero-caption p {
                    font-size: 1.18rem;
                    margin-bottom: 1.5rem;
                    font-weight: 400;
                    line-height: 1.6;
                }
                .hero-caption .btn-uiverse {
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    color: #fff;
                    font-weight: 600;
                    font-size: 1.1rem;
                    border: none;
                    border-radius: 22px;
                    padding: 0.7rem 2.2rem;
                    box-shadow: 0 2px 8px rgba(255,107,1,0.13);
                    transition: background 0.2s, box-shadow 0.2s;
                    text-decoration: none;
                    display: inline-block;
                }
                .hero-caption .btn-uiverse:hover {
                    background: #e65b00;
                    color: #fff;
                    box-shadow: 0 4px 16px rgba(255,107,1,0.18);
                }

                /* About Section Styles */
                .about-imgs-flex {
                    display: flex;
                    flex-direction: row;
                    gap: 1.5rem;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                }
                .about-img-card-modern {
                    background: #fff;
                    border-radius: 18px;
                    box-shadow: 0 4px 24px rgba(255,107,1,0.10);
                    overflow: hidden;
                    width: 170px;
                    height: 220px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: transform 0.2s, box-shadow 0.2s;
                    position: relative;
                }
                .about-img-card-modern img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 18px;
                    transition: transform 0.3s;
                }
                .about-img-card-modern:hover {
                    transform: translateY(-8px) scale(1.04);
                    box-shadow: 0 8px 32px rgba(255,107,1,0.18);
                    z-index: 2;
                }
                .about-img-card-modern:hover img {
                    transform: scale(1.05);
                }
                .about-text-modern {
                    background: rgba(255,255,255,0.85);
                    border-radius: 22px;
                    box-shadow: 0 8px 32px rgba(255,107,1,0.10);
                    border: 2px solid #ffe5d0;
                    backdrop-filter: blur(12px);
                    padding: 2rem 1.5rem 1.5rem 1.5rem;
                    text-align: center;
                    max-width: 600px;
                    margin: 0 auto;
                }
                .about-text-modern h2 {
                    font-size: 1.8rem;
                    font-weight: 800;
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    text-fill-color: transparent;
                    margin-bottom: 1.5rem;
                }

                /* Student Number Section */
                .hidden1, .hidden2, .hidden3 {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    border-radius: 20px;
                    padding: 2rem;
                    text-align: center;
                    color: white;
                    box-shadow: 0 8px 32px rgba(255,107,1,0.15);
                    transition: transform 0.3s;
                }
                .hidden1:hover, .hidden2:hover, .hidden3:hover {
                    transform: translateY(-5px);
                }
                .hidden1 span, .hidden2 span, .hidden3 span {
                    display: block;
                    font-size: 1.2rem;
                    margin-bottom: 0.5rem;
                }
                .hidden1 h3, .hidden2 h3, .hidden3 h3 {
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin: 0;
                }
                .hidden1 p, .hidden2 p, .hidden3 p {
                    margin: 0.5rem 0 0 0;
                    font-size: 1.1rem;
                }

                /* Course Section Styles */
                .edu-section {
                    padding: 4rem 0;
                    background: #f8f9fa;
                }
                .edu-section-title {
                    text-align: center;
                    font-size: 2.5rem;
                    font-weight: 800;
                    color: #2c3e50;
                    margin-bottom: 3rem;
                    position: relative;
                }
                .edu-section-title i {
                    color: #ff6b01;
                    margin-right: 1rem;
                }
                .edu-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                    transition: transform 0.3s, box-shadow 0.3s;
                    height: 100%;
                }
                .edu-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(255,107,1,0.2);
                }
                .edu-card-img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                }
                .edu-card-body {
                    padding: 1.5rem;
                }
                .edu-card-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #2c3e50;
                    margin-bottom: 1rem;
                }
                .edu-card-desc {
                    color: #6c757d;
                    margin-bottom: 1.5rem;
                    line-height: 1.6;
                }
                .edu-card-price {
                    font-size: 1.5rem;
                    font-weight: 800;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }
                .edu-card-btn {
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    color: white;
                    border: none;
                    border-radius: 25px;
                    padding: 0.8rem 2rem;
                    font-weight: 600;
                    text-decoration: none;
                    display: inline-block;
                    transition: all 0.3s;
                }
                .edu-card-btn:hover {
                    background: #e65b00;
                    transform: translateY(-2px);
                }

                /* Responsive Design */
                @media (max-width: 991.98px) {
                    .about-imgs-flex {
                        gap: 1rem;
                    }
                    .about-img-card-modern {
                        width: 120px;
                        height: 150px;
                        border-radius: 12px;
                    }
                    .about-img-card-modern img {
                        border-radius: 12px;
                    }
                    .about-text-modern {
                        padding: 1.2rem 0.7rem 1rem 0.7rem;
                        border-radius: 14px;
                        font-size: 1rem;
                    }
                }
                @media (max-width: 767.98px) {
                    .about-imgs-flex {
                        flex-direction: row;
                        gap: 0.5rem;
                    }
                    .about-img-card-modern {
                        width: 80px;
                        height: 80px;
                        border-radius: 8px;
                    }
                    .about-img-card-modern img {
                        border-radius: 8px;
                    }
                    .about-text-modern {
                        padding: 0.7rem 0.3rem 0.7rem 0.3rem;
                        border-radius: 8px;
                        font-size: 0.97rem;
                    }
                }
                @media (max-width: 575.98px) {
                    .about-imgs-flex {
                        gap: 0.3rem;
                    }
                    .about-img-card-modern {
                        width: 60px;
                        height: 60px;
                    }
                    .about-text-modern {
                        padding: 0.5rem 0.1rem 0.5rem 0.1rem;
                    }
                }
            `}</style>

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
                            <h1>Build Your Future With <span style={{whiteSpace: 'nowrap'}}>Technology</span></h1>
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

            {/* Student Number Section */}
            <section id="stu_number">
                <div className="container">
                    <h5 className="hidden">Lorem ipsum dolor, sit amet consectetur adipisicing.</h5>
                    <div className="row">
                        <div className="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 justify-content-end">
                            <div className="card hidden1">
                                <span>YHA</span>
                                <h3>20+</h3>
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
                                    <h3>50+</h3>
                                </div>
                                <p>Batches</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Course Section */}
            <section className="edu-section" id="course">
                <div className="container">
                    <h2 className="edu-section-title"><i className="fa-solid fa-book-open"></i> Monthly Courses</h2>
                    <div className="row g-4">
                        {monthies && monthies.data && monthies.data.slice(0, 3).map((monthly) => (
                            <div key={monthly.id} className="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div className="edu-card">
                                    <img className="edu-card-img" src={`/storage/${monthly.m_img}`} alt="" />
                                    <div className="edu-card-body">
                                        <h5 className="edu-card-title">{monthly.course?.name}</h5>
                                        <p className="edu-card-desc">{monthly.course?.description}</p>
                                        <div className="edu-card-price">Ks {Number(monthly.course?.normal_price || 0).toLocaleString()}</div>
                                        <Link href={`/yha/courses/monthly/${monthly.id}`} className="edu-card-btn">View Details</Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Scroll to Top Button */}
            <button
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
