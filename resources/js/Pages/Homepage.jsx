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
    const [typingKey, setTypingKey] = useState(0);
    const [activeFilter, setActiveFilter] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const projectsPerPage = 6;
    const fullText = "Unlock your full potential through curiosity";
    const { url } = usePage();

    // Combine all navigation courses for display with real database data
    const monthliesData = [];

    // Add Programming courses with real data
    if (prog && prog.length > 0) {
        prog.forEach((course, index) => {
            monthliesData.push({
                id: course.id,
                course: {
                    name: course.name || course.title || course.course_name,
                    description: course.description || course.desc || course.about || `Learn ${course.name || course.title || course.course_name} with expert instructors and hands-on projects`,
                    normal_price: course.price || course.fee || course.cost || course.normal_price || course.special_price,
                    duration: course.duration || course.period || '3 Months',
                    level: course.level || course.difficulty || (index % 3 === 0 ? 'Beginner' : index % 3 === 1 ? 'Intermediate' : 'Advanced')
                },
                m_img: course.image || course.img || course.photo || `courses/programming-${index + 1}.jpg`,
                instructor: course.instructor || course.teacher || 'Expert Instructor',
                category: 'programming'
            });
        });
    }

    // Add Graphic Design courses with real data
    if (graph && graph.length > 0) {
        graph.forEach((course, index) => {
            monthliesData.push({
                id: course.id + 1000, // Different ID to avoid conflicts
                course: {
                    name: course.name || course.title || course.course_name,
                    description: course.description || course.desc || course.about || `Master ${course.name || course.title || course.course_name} with industry-standard tools and techniques`,
                    normal_price: course.price || course.fee || course.cost || course.normal_price || course.special_price,
                    duration: course.duration || course.period || '3 Months',
                    level: course.level || course.difficulty || (index % 3 === 0 ? 'Beginner' : index % 3 === 1 ? 'Intermediate' : 'Advanced')
                },
                m_img: course.image || course.img || course.photo || `courses/design-${index + 1}.jpg`,
                instructor: course.instructor || course.teacher || 'Expert Instructor',
                category: 'graphic'
            });
        });
    }

    // Add ICT courses with real data
    if (ict && ict.length > 0) {
        ict.forEach((course, index) => {
            monthliesData.push({
                id: course.id + 2000, // Different ID to avoid conflicts
                course: {
                    name: course.name || course.title || course.course_name,
                    description: course.description || course.desc || course.about || `Become proficient in ${course.name || course.title || course.course_name} for career advancement`,
                    normal_price: course.price || course.fee || course.cost || course.normal_price || course.special_price,
                    duration: course.duration || course.period || '3 Months',
                    level: course.level || course.difficulty || (index % 3 === 0 ? 'Beginner' : index % 3 === 1 ? 'Intermediate' : 'Advanced')
                },
                m_img: course.image || course.img || course.photo || `courses/ict-${index + 1}.jpg`,
                instructor: course.instructor || course.teacher || 'Expert Instructor',
                category: 'ict'
            });
        });
    }

    // Filter courses based on active filter
    const filteredCourses = monthliesData.filter(course => {
        if (activeFilter === 'all') return true;
        return course.category === activeFilter;
    });

    // Get course counts for each category
    const getCourseCount = (category) => {
        if (category === 'all') return monthliesData.length;
        return monthliesData.filter(course => course.category === category).length;
    };

    // Pagination logic for projects
    const indexOfLastProject = currentPage * projectsPerPage;
    const indexOfFirstProject = indexOfLastProject - projectsPerPage;
    const currentProjects = projects ? projects.slice(indexOfFirstProject, indexOfLastProject) : [];
    const totalPages = projects ? Math.ceil(projects.length / projectsPerPage) : 0;

    const paginate = (pageNumber) => setCurrentPage(pageNumber);

    // FAQ Accordion functionality
    const toggleFaq = (index) => {
        const faqItems = document.querySelectorAll('.faq-item');
        const clickedItem = faqItems[index];

        if (clickedItem.classList.contains('active')) {
            clickedItem.classList.remove('active');
        } else {
            // Close all other items
            faqItems.forEach(item => item.classList.remove('active'));
            // Open clicked item
            clickedItem.classList.add('active');
        }
    };

    // Category tabs functionality
    const switchCategory = (category) => {
        const categoryBtns = document.querySelectorAll('.faq-category-btn');
        categoryBtns.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        // Here you could filter FAQs by category
    };

    useEffect(() => {
        // Force restart typing effect
        setTypingKey(prev => prev + 1);
        setTypedText('');
        let index = 0;

        const timer = setInterval(() => {
            if (index < fullText.length) {
                setTypedText(fullText.slice(0, index + 1));
                index++;
            } else {
                clearInterval(timer);
            }
        }, 80);

        return () => {
            clearInterval(timer);
        };
    }, [url]);

    // Hero Slider Functionality
    useEffect(() => {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.indicator');
        const totalSlides = slides.length;

        if (totalSlides === 0) return;

        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
        };

        const nextSlide = () => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        };

        const prevSlide = () => {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        };

        // Auto-play slider
        const slideInterval = setInterval(nextSlide, 5000);

        // Indicator clicks
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
                clearInterval(slideInterval);
            });
        });

        return () => {
            clearInterval(slideInterval);
        };
    }, [sliders]);

    // Get address data
    const addr = address && address.length > 0 ? address[0] : null;
    return (

        <div className="frontend-page" key={url}>
                      <Navigation prog={prog} graph={graph} ict={ict} contactInfo={{
                            address: addr?.address || '123 University Street, Tech City',
                            phone: addr?.yphNo || '+1 (555) 123-4567',
                            email: addr?.yEmail || 'info@yhauniversity.edu'
                        }} />
            {/* Modern Tech University Hero Section */}
            <section id="home" className="tech-university-hero">
                {/* Video Background */}
                <div className="hero-video-background">
                    <div className="video-overlay"></div>
                    <div className="animated-particles">
                        {[...Array(20)].map((_, i) => (
                            <div key={i} className={`particle particle-${i + 1}`}></div>
                        ))}
                    </div>
                </div>

                {/* Slider Content */}
                <div className="hero-slider-container">
                    <div className="hero-slider">
                        {sliders && sliders.map((slider, index) => (
                            <div key={slider.id} className={`hero-slide ${index === 0 ? 'active' : ''}`}>
                                <div className="slide-background">
                                    <img src={`/storage/${slider.image}`} alt={`Slide ${index + 1}`} />
                                    <div className="slide-gradient"></div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>



                {/* Hero Content */}
                <div className="hero-content">
                    <div className="container">
                        <div className="hero-text-content">
                            <h1 className="hero-title">
                                <span className="title-line">YHA Academy of Technology</span>
                                {/* <span className="title-line highlight">With Technology</span> */}
                            </h1>

                            <p className="hero-description">
                                Join YHA Computer Training Center to master programming, design, and ICT skills from industry experts.
                                Transform your career with cutting-edge technology education.
                            </p>

                            <div className="hero-actions">
                                <Link href="/yha/courses/monthl" className="hero-btn primary">
                                    <span className="btn-text">Explore Courses</span>
                                    <i className="fas fa-arrow-right"></i>
                                </Link>
                                <Link href="/about" className="hero-btn secondary">
                                    <i className="fas fa-play-circle"></i>
                                    <span className="btn-text">Learn More</span>
                                </Link>
                            </div>

                            {/* <div className="hero-stats">
                                <div className="stat-item">
                                    <span className="stat-number">20+</span>
                                    <span className="stat-label">Courses</span>
                                </div>
                                <div className="stat-item">
                                    <span className="stat-number">1000+</span>
                                    <span className="stat-label">Students</span>
                                </div>
                                <div className="stat-item">
                                    <span className="stat-number">50+</span>
                                    <span className="stat-label">Batches</span>
                                </div>
                            </div> */}
                        </div>
                    </div>
                </div>

                {/* Slider Indicators */}
                <div className="slider-indicators">
                    {sliders && sliders.map((_, index) => (
                        <button
                            key={index}
                            className={`indicator ${index === 0 ? 'active' : ''}`}
                            data-slide={index}
                        ></button>
                    ))}
                </div>
            </section>

            {/* About Section */}
            {/* <section id="about1" className="py-5">
                <div className="container">
                    <div className="mb-4">
                        <h3 className="typewriter-heading">
                            <span id="typewriter-text" key={typingKey}>{typedText}<span className="typing-cursor">|</span></span>
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
            </section> */}



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

            {/* HIT Myanmar Style Course Section */}
            <section className="hit-courses-section py-5" id="course">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fas fa-code"></i>
                            <span>OUR COURSES</span>
                        </div>
                        <div className="title-underline"></div>
                        <p className="hit-subtitle">
                            To master your skills of tomorrow, join our innovative e-learning platform today
                        </p>
                    </div>

                    {/* Course Filter */}
                    <div className="course-filter-container">
                        <div className="course-filter-buttons">
                            <button
                                className={`filter-btn ${activeFilter === 'all' ? 'active' : ''}`}
                                onClick={() => setActiveFilter('all')}
                                data-count={getCourseCount('all')}
                            >
                                <span>All</span>
                            </button>
                            <button
                                className={`filter-btn ${activeFilter === 'programming' ? 'active' : ''}`}
                                onClick={() => setActiveFilter('programming')}
                                data-count={getCourseCount('programming')}
                            >
                                <span>Programming</span>
                            </button>
                            <button
                                className={`filter-btn ${activeFilter === 'graphic' ? 'active' : ''}`}
                                onClick={() => setActiveFilter('graphic')}
                                data-count={getCourseCount('graphic')}
                            >
                                <span>Graphic</span>
                            </button>
                            <button
                                className={`filter-btn ${activeFilter === 'ict' ? 'active' : ''}`}
                                onClick={() => setActiveFilter('ict')}
                                data-count={getCourseCount('ict')}
                            >
                                <span>ICT</span>
                            </button>
                        </div>
                    </div>

                    {/* Modern Courses Grid */}
                    <div className="row g-4">
                        {filteredCourses && filteredCourses.length > 0 ? (
                            filteredCourses.map((monthly, index) => (
                                <div key={monthly.id} className="col-xl-4 col-lg-4 col-md-6">
                                    <div className="modern-course-card">
                                        {/* Course Image */}
                                        <div className="modern-image-container">
                                            <img
                                                src={`/storage/${monthly.m_img}`}
                                                alt={monthly.course?.name}
                                                className="modern-course-image"
                                            />
                                            <div className="modern-overlay">
                                                <div className="modern-category">
                                                    {monthly.category === 'programming' ? 'Programming' :
                                                     monthly.category === 'graphic' ? 'Graphic' : 'ICT'}
                                                </div>

                                            </div>
                                        </div>

                                        {/* Course Content */}
                                        <div className="modern-course-content">
                                            <h3 className="modern-course-title">{monthly.course?.name}</h3>
                                            <p className="modern-course-description">
                                                {monthly.course?.description}
                                            </p>
                                        </div>

                                        {/* Course Meta */}
                                        <div className="modern-course-meta">
                                            <div className="modern-price-info">
                                                <span className="modern-price-label">Course Fee</span>
                                                <div className="modern-price-amount">
                                                    <span className="modern-currency">Ks</span>
                                                    {monthly.course?.normal_price ? monthly.course.normal_price.toLocaleString() : 'Contact for price'}
                                                </div>
                                            </div>
                                            <Link
                                                href={`/course/${monthly.id}`}
                                                className="modern-action-btn"
                                            >
                                                <span>More Detail</span>
                                                <i className="fas fa-arrow-right"></i>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="modern-no-courses">
                                    <div className="modern-no-courses-icon">
                                        <i className="fas fa-graduation-cap"></i>
                                    </div>
                                    <h3>Courses Coming Soon</h3>
                                    <p>We're preparing amazing courses for you. Check back later!</p>
                                    <Link href="/course" className="modern-browse-btn">
                                        <i className="fas fa-th-large"></i>
                                        <span>Browse All Courses</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Events Section */}
            {/* <section className="mt-5 mb-5" id="event">
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
            </section> */}

            {/* Projects Section - Modern Design */}
            <section className="modern-projects-section py-5" id="projects">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fas fa-rocket"></i>
                            <span>STUDENT PROJECTS</span>
                        </div>
                        <div className="title-underline"></div>
                        <p className="hit-subtitle">
                            Showcasing innovative projects created by our talented students
                        </p>
                    </div>

                    {/* Modern Projects Grid */}
                    <div className="row g-4">
                        {currentProjects && currentProjects.length > 0 ? (
                            currentProjects.map((project) => (
                                <div key={project.id} className="col-xl-4 col-lg-4 col-md-6">
                                    <div className="modern-project-card">
                                        {/* Project Image */}
                                        <div className="modern-project-image-container">
                                            <img
                                                src={`/storage/${project.image}`}
                                                alt={project.title}
                                                className="modern-project-image"
                                            />
                                            <div className="modern-project-overlay">
                                                <div className="modern-project-category">
                                                    {project.course?.name || 'Student Work'}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Project Content */}
                                        <div className="modern-project-content">
                                            <h3 className="modern-project-title">{project.title}</h3>
                                            <p className="modern-project-description">
                                                {project.desc}
                                            </p>
                                        </div>

                                        {/* Project Footer */}
                                        <div className="modern-project-meta">
                                            <div className="project-actions">
                                                {project.github && (
                                                    <a href={project.github} className="modern-project-btn github-btn" target="_blank" rel="noopener noreferrer">
                                                        <i className="fab fa-github"></i>
                                                        <span>Code</span>
                                                    </a>
                                                )}
                                                {project.demo && (
                                                    <a href={project.demo} className="modern-project-btn demo-btn" target="_blank" rel="noopener noreferrer">
                                                        <i className="fas fa-play"></i>
                                                        <span>Live</span>
                                                    </a>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="modern-no-courses">
                                    <div className="modern-no-courses-icon">
                                        <i className="fas fa-code"></i>
                                    </div>
                                    <h3>Projects Coming Soon</h3>
                                    <p>Our students are working on amazing projects. Check back later!</p>
                                    <Link href="/yha/project" className="modern-browse-btn">
                                        <i className="fas fa-folder-open"></i>
                                        <span>Browse All Projects</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Pagination */}
                    {totalPages > 1 && (
                        <div className="projects-pagination">
                            <div className="pagination-container">
                                <button
                                    className="pagination-btn"
                                    onClick={() => paginate(currentPage - 1)}
                                    disabled={currentPage === 1}
                                >
                                    <i className="fas fa-chevron-left"></i>
                                </button>

                                <div className="pagination-numbers">
                                    {[...Array(totalPages)].map((_, index) => (
                                        <button
                                            key={index + 1}
                                            className={`pagination-number ${currentPage === index + 1 ? 'active' : ''}`}
                                            onClick={() => paginate(index + 1)}
                                        >
                                            {index + 1}
                                        </button>
                                    ))}
                                </div>

                                <button
                                    className="pagination-btn"
                                    onClick={() => paginate(currentPage + 1)}
                                    disabled={currentPage === totalPages}
                                >
                                    <i className="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <div className="pagination-info">
                                <span>Showing {indexOfFirstProject + 1}-{Math.min(indexOfLastProject, projects?.length || 0)} of {projects?.length || 0} projects</span>
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* FAQ Section - Modern Design */}
            <section className="modern-faq-section py-5" id="faq">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fas fa-question-circle"></i>
                            <span>FAQ</span>
                        </div>
                        <div className="title-underline"></div>
                        <p className="hit-subtitle">
                            Find answers to common questions about our courses and services
                        </p>
                    </div>

                    {/* Modern FAQ Accordion */}
                    <div className="modern-faq-container">
                        <div className="faq-item active">
                            <div className="faq-question" onClick={() => toggleFaq(0)}>
                                <div className="faq-question-content">
                                    <h4>What courses does YHA offer?</h4>
                                    <p>We offer comprehensive courses in programming, web development, graphic design, and ICT skills.</p>
                                </div>
                                <div className="faq-toggle">
                                    <i className="fas fa-plus"></i>
                                </div>
                            </div>
                            <div className="faq-answer">
                                <div className="faq-answer-content">
                                    <p>We offer a wide range of courses designed to meet different skill levels and career goals:</p>
                                    <ul>
                                        <li><strong>Programming:</strong> Python, JavaScript, Java, C++, and more</li>
                                        <li><strong>Web Development:</strong> HTML, CSS, React, Node.js, PHP</li>
                                        <li><strong>Graphic Design:</strong> Photoshop, Illustrator, UI/UX Design</li>
                                        <li><strong>ICT Skills:</strong> Computer basics, networking, digital literacy</li>
                                    </ul>
                                    <p>All courses include hands-on projects and practical experience.</p>
                                </div>
                            </div>
                        </div>

                        <div className="faq-item">
                            <div className="faq-question" onClick={() => toggleFaq(1)}>
                                <div className="faq-question-content">
                                    <h4>How can I enroll in a course?</h4>
                                    <p>Multiple enrollment options available for your convenience.</p>
                                </div>
                                <div className="faq-toggle">
                                    <i className="fas fa-plus"></i>
                                </div>
                            </div>
                            <div className="faq-answer">
                                <div className="faq-answer-content">
                                    <p>Enrolling in our courses is simple and flexible:</p>
                                    <ul>
                                        <li><strong>Online Enrollment:</strong> Visit our website and complete the registration form</li>
                                        <li><strong>In-Person Registration:</strong> Visit our center during business hours</li>
                                        <li><strong>Phone Registration:</strong> Call our enrollment hotline</li>
                                        <li><strong>Email Registration:</strong> Send us an email with your details</li>
                                    </ul>
                                    <p>Our staff will guide you through the process and help you choose the right course.</p>
                                </div>
                            </div>
                        </div>

                        <div className="faq-item">
                            <div className="faq-question" onClick={() => toggleFaq(2)}>
                                <div className="faq-question-content">
                                    <h4>Are there any prerequisites for joining?</h4>
                                    <p>Requirements vary depending on the course level.</p>
                                </div>
                                <div className="faq-toggle">
                                    <i className="fas fa-plus"></i>
                                </div>
                            </div>
                            <div className="faq-answer">
                                <div className="faq-answer-content">
                                    <p>Prerequisites depend on the course level:</p>
                                    <ul>
                                        <li><strong>Beginner Courses:</strong> No prior experience required</li>
                                        <li><strong>Intermediate Courses:</strong> Basic understanding of the subject</li>
                                        <li><strong>Advanced Courses:</strong> Previous experience or completion of prerequisite courses</li>
                                    </ul>
                                    <p>Each course description clearly outlines any requirements. Contact us if you're unsure about your eligibility.</p>
                                </div>
                            </div>
                        </div>

                        <div className="faq-item">
                            <div className="faq-question" onClick={() => toggleFaq(3)}>
                                <div className="faq-question-content">
                                    <h4>Do you provide certificates?</h4>
                                    <p>Yes, certificates are awarded upon course completion.</p>
                                </div>
                                <div className="faq-toggle">
                                    <i className="fas fa-plus"></i>
                                </div>
                            </div>
                            <div className="faq-answer">
                                <div className="faq-answer-content">
                                    <p>We provide comprehensive certification:</p>
                                    <ul>
                                        <li><strong>Completion Certificate:</strong> Awarded after successfully finishing the course</li>
                                        <li><strong>Achievement Certificate:</strong> For outstanding performance</li>
                                        <li><strong>Digital Certificates:</strong> Shareable on LinkedIn and other platforms</li>
                                        <li><strong>Physical Certificates:</strong> Printed copies available upon request</li>
                                    </ul>
                                    <p>Our certificates are recognized by industry partners and can help advance your career.</p>
                                </div>
                            </div>
                        </div>

                        <div className="faq-item">
                            <div className="faq-question" onClick={() => toggleFaq(4)}>
                                <div className="faq-question-content">
                                    <h4>How can I contact support?</h4>
                                    <p>Multiple support channels available for your convenience.</p>
                                </div>
                                <div className="faq-toggle">
                                    <i className="fas fa-plus"></i>
                                </div>
                            </div>
                            <div className="faq-answer">
                                <div className="faq-answer-content">
                                    <p>We're here to help through multiple channels:</p>
                                    <ul>
                                        <li><strong>Phone Support:</strong> Available during business hours</li>
                                        <li><strong>Email Support:</strong> Response within 24 hours</li>
                                        <li><strong>Live Chat:</strong> Available on our website</li>
                                        <li><strong>In-Person Support:</strong> Visit our center</li>
                                        <li><strong>Social Media:</strong> Message us on Facebook or Instagram</li>
                                    </ul>
                                    <p>Our support team is dedicated to helping you succeed in your learning journey.</p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </section>

            {/* Reviews Section - Modern Design */}
            {homeReviews && homeReviews.length > 0 && (
                <section className="modern-reviews-section py-5" id="reviews">
                    <div className="container">
                        {/* Section Header */}
                        <div className="text-center mb-5">
                            <div className="hit-badge">
                                <i className="fas fa-star"></i>
                                <span>STUDENT REVIEWS</span>
                            </div>
                            <div className="title-underline"></div>
                            <p className="hit-subtitle">
                                Real voices. Real experiences. See what our students say!
                            </p>
                        </div>

                        {/* Reviews Grid */}
                        <div className="reviews-grid">
                            {homeReviews.slice(0, 6).map((review) => (
                                <div key={review.id} className="modern-review-card">
                                    <div className="review-header">
                                        <div className="review-avatar">
                                            {review.photo ? (
                                                <img src={`/storage/${review.photo}`} alt={review.name} />
                                            ) : (
                                                <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(review.name)}&background=ff6b35&color=fff`} alt={review.name} />
                                            )}
                                        </div>
                                        <div className="review-info">
                                            <h4 className="review-name">{review.name}</h4>
                                            <div className="review-rating">
                                                {[1,2,3,4,5].map((star) => (
                                                    <i key={star} className={star <= review.rating ? "fas fa-star" : "far fa-star"}></i>
                                                ))}
                                            </div>
                                        </div>
                                        <div className="review-quote-icon">
                                            <i className="fas fa-quote-right"></i>
                                        </div>
                                    </div>
                                    <div className="review-content">
                                        <p className="review-text">{review.review}</p>
                                    </div>
                                    <div className="review-footer">
                                        <div className="review-date">
                                            <i className="fas fa-calendar"></i>
                                            <span>Recent Student</span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* View All Reviews Button */}
                        <div className="text-center mt-5">
                            <Link href="/reviews" className="modern-view-all">
                                <span>View All Reviews</span>
                                <i className="fas fa-star"></i>
                            </Link>
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
