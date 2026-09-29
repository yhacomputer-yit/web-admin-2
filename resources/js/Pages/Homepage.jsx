import { useEffect, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import Navigation from "../Components/Navigation";
import Footer from "../Components/Footer";
import "../../css/pages/homepage.css";

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
}) {
    const [typedText, setTypedText] = useState("");
    const [typingKey, setTypingKey] = useState(0);
    const [activeFilter, setActiveFilter] = useState("all");
    const fullText = "Unlock your full potential through curiosity";
    const { url, courseTypes = [] } = usePage().props;

    const typeList = Array.isArray(courseTypes) ? courseTypes : [];

    // Build one flat list of every course grouped by its course type
    const coursesData = typeList.flatMap((type) =>
        (type.courses || []).map((course, index) => ({
            id: course.id,
            category: type.name,
            categoryId: String(type.id),
            name: course.name,
            description:
                course.description ||
                `Learn ${course.name} with expert instructors and hands-on projects`,
            normal_price: course.normal_price ?? course.special_price,
            duration: course.duration || "3 Months",
            image: course.image,
            level:
                index % 3 === 0
                    ? "Beginner"
                    : index % 3 === 1
                      ? "Intermediate"
                      : "Advanced",
        }))
    );

    const filteredCourses = coursesData.filter((course) => {
        if (activeFilter === "all") return true;
        return course.categoryId === activeFilter;
    });

    const getCourseCount = (categoryId) => {
        if (categoryId === "all") return coursesData.length;
        return coursesData.filter((course) => course.categoryId === categoryId)
            .length;
    };

    // FAQ Accordion functionality
    const toggleFaq = (index) => {
        const faqItems = document.querySelectorAll(".faq-item");
        const clickedItem = faqItems[index];

        if (clickedItem.classList.contains("active")) {
            clickedItem.classList.remove("active");
        } else {
            // Close all other items
            faqItems.forEach((item) => item.classList.remove("active"));
            // Open clicked item
            clickedItem.classList.add("active");
        }
    };

    // Category tabs functionality
    const switchCategory = (category) => {
        const categoryBtns = document.querySelectorAll(".faq-category-btn");
        categoryBtns.forEach((btn) => btn.classList.remove("active"));
        event.target.classList.add("active");
        // Here you could filter FAQs by category
    };

    useEffect(() => {
        // Force restart typing effect
        setTypingKey((prev) => prev + 1);
        setTypedText("");
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
        const slides = document.querySelectorAll(".hero-slide");
        const indicators = document.querySelectorAll(".indicator");
        const totalSlides = slides.length;

        if (totalSlides === 0) return;

        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.classList.toggle("active", i === index);
            });
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle("active", i === index);
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
            indicator.addEventListener("click", () => {
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
            <Navigation />
            {/* Modern Tech University Hero Section */}
            <section id="home" className="tech-university-hero">
                {/* Video Background */}
                <div className="hero-video-background">
                    <div className="video-overlay"></div>
                    <div className="animated-particles">
                        {[...Array(20)].map((_, i) => (
                            <div
                                key={i}
                                className={`particle particle-${i + 1}`}
                            ></div>
                        ))}
                    </div>
                </div>

                {/* Slider Content */}
                <div className="hero-slider-container">
                    <div className="hero-slider">
                        {sliders &&
                            sliders.map((slider, index) => (
                                <div
                                    key={slider.id}
                                    className={`hero-slide ${index === 0 ? "active" : ""}`}
                                >
                                    <div className="slide-background">
                                        <img
                                            src={`/storage/${slider.image}`}
                                            alt={`Slide ${index + 1}`}
                                        />
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
                                <span className="title-line">
                                    YHA Academy of Technology
                                </span>
                            </h1>

                            <p className="hero-description">
                                Join YHA Computer Training Center to master
                                programming, design, and ICT skills from
                                industry experts. Transform your career with
                                cutting-edge technology education.
                            </p>
                        </div>
                    </div>
                </div>

                {/* Slider Indicators */}
                <div className="slider-indicators">
                    {sliders &&
                        sliders.map((_, index) => (
                            <button
                                key={index}
                                className={`indicator ${index === 0 ? "active" : ""}`}
                                data-slide={index}
                            ></button>
                        ))}
                </div>
            </section>

            {/* HIT Myanmar Style Course Section */}
            <section className="hit-courses-section py-5" id="course">
                <div className="container">
                    {/* Section Header */}
                    <div className="mb-1 section-header-left">
                        <div className="hit-b">
                            <span>OUR COURSES</span>
                        </div>
                    </div>



                    {/* Modern Courses Grid */}
                    <div className="row g-2">
                        {filteredCourses && filteredCourses.length > 0 ? (
                            filteredCourses.map((course) => (
                                <div
                                    key={course.id}
                                    className="col-xl-4 col-lg-4 col-md-6"
                                >
                                    <div className="modern-course-card">
                                        {/* Course Image */}
                                        <div className="modern-image-container">
                                            <img
                                                src={
                                                    course.image
                                                        ? `/storage/${course.image}`
                                                        : "/image/no-image.jpg"
                                                }
                                                onError={(e) => {
                                                    e.currentTarget.onerror = null;
                                                    e.currentTarget.src =
                                                        "/image/no-image.jpg";
                                                }}
                                                alt={course.name}
                                                className="modern-course-image"
                                            />
                                            <div className="modern-overlay">
                                                <div className="modern-category">
                                                    {course.category}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Course Content */}
                                        <div className="modern-course-content">
                                            <h3 className="modern-course-title">
                                                {course.name}
                                            </h3>
                                            <p className="modern-course-description">
                                                {course.description}
                                            </p>
                                        </div>

                                        {/* Course Meta */}
                                        <div className="modern-course-meta">
                                            <div className="modern-price-info">
                                                <span className="modern-price-label">
                                                    Course Fee
                                                </span>
                                                <div className="modern-price-amount">
                                                    <span className="modern-currency">
                                                        Ks
                                                    </span>
                                                    {course.normal_price
                                                        ? Number(
                                                              course.normal_price,
                                                          ).toLocaleString()
                                                        : "Contact for price"}
                                                </div>
                                            </div>
                                            <Link
                                                href={`/course/${course.id}`}
                                                className="learnmore"
                                            >
                                                <span>Learn More</span>
                                                <i className="fas fa-arrow-right ms-2 learnmore"></i>
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
                                    <p>
                                        We're preparing amazing courses for you.
                                        Check back later!
                                    </p>
                                    <Link
                                        href="/course"
                                        className="modern-browse-btn"
                                    >
                                        <i className="fas fa-th-large"></i>
                                        <span>Browse All Courses</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Statistics Section */}
            <section className="modern-stats-section py-5">
                <div className="container">
                    <div className="row g-4">
                        <div className="col-lg-4 col-md-6">
                            <div className="modern-stat-card">
                                <div className="stat-icon-wrapper">
                                    <i className="fas fa-user-group"></i>
                                </div>
                                <h3 className="stat-label">Total Students</h3>
                                <div className="stat-number">909,999+</div>
                                <div className="stat-description">
                                    Learning and growing with us
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6">
                            <div className="modern-stat-card">
                                <div className="stat-icon-wrapper">
                                    <i className="fas fa-laptop-code"></i>
                                </div>
                                <h3 className="stat-label">CS Students</h3>
                                <div className="stat-number">99,999+</div>
                                <div className="stat-description">
                                    Building the future of tech
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6">
                            <div className="modern-stat-card">
                                <div className="stat-icon-wrapper">
                                    <i className="fas fa-book-open"></i>
                                </div>
                                <h3 className="stat-label">
                                    Number of Courses
                                </h3>
                                <div className="stat-number">
                                    {coursesData.length}+
                                </div>
                                <div className="stat-description">
                                    Comprehensive learning paths
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Projects Section - Modern Design */}
            <section className="modern-projects-section py-5" id="projects">
                <div className="container">
                    {/* Section Header */}
                    <div className="mb-2 section-header-left">
                        <div className="hit-b">
                            <span>STUDENT PROJECTS</span>
                        </div>
                    </div>

                    {/* Modern Projects Grid */}
                    <div className="row g-2">
                        {projects && projects.length > 0 ? (
                            projects.map((project, index) => (
                                <div
                                    key={project.id || index}
                                    className="col-xl-4 col-lg-4 col-md-6"
                                >
                                    <div className="modern-project-card">
                                        {/* Project Image */}
                                        <div className="modern-project-image-container">
                                            <img
                                                src={
                                                    project.image
                                                        ? `/storage/${project.image}`
                                                        : "/placeholder.jpg"
                                                }
                                                alt={project.title || "Project"}
                                                className="modern-project-image"
                                                onError={(e) => {
                                                    e.target.src =
                                                        "/placeholder.jpg";
                                                }}
                                            />
                                            <div className="modern-project-overlay">
                                                <div className="modern-project-category">
                                                    {typeof project.course
                                                        ?.name === "string"
                                                        ? project.course.name
                                                        : project.category ||
                                                          "Student Work"}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Project Content */}
                                        <div className="modern-project-content">
                                            <h3 className="modern-project-title">
                                                {typeof project.title ===
                                                "string"
                                                    ? project.title
                                                    : "Project Title"}
                                            </h3>
                                            <p className="modern-project-description">
                                                {typeof (
                                                    project.desc ||
                                                    project.description
                                                ) === "string"
                                                    ? project.desc ||
                                                      project.description
                                                    : "Project description"}
                                            </p>
                                        </div>

                                        {/* Project Footer */}
                                        <div className="modern-project-meta">
                                            {/* <div className="project-actions">
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
                                            </div> */}
                                            <Link
                                                href={`/course/`}
                                                className=""
                                            >
                                                <span className="learnmore">
                                                    Learn More
                                                </span>

                                                <i className="fas fa-arrow-right learnmore"></i>
                                            </Link>
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
                                    <p>
                                        Our students are working on amazing
                                        projects. Check back later!
                                    </p>
                                    <Link
                                        href="/yha/project"
                                        className="modern-browse-btn"
                                    >
                                        <i className="fas fa-folder-open"></i>
                                        <span>Browse All Projects</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* ========== About Section (Homepage) ========== */}
             <section className="about-hero-section mt-5">
                <div className="container">
                    <div className="about-hero-wrapper">
                        {/* Left - Image */}
                        <div className="about-hero-image">
                            <img
                                src="/image/logo/about.jpg"
                                alt="YHA Computer Training Center"
                            />
                        </div>

                        {/* Right - Content */}
                        <div className="about-hero-content">
                            <h1 className="about-hero-title">
                                Learn about YHA Computer
                            </h1>

                            <h2 className="about-hero-subtitle">
                                YHA Computer Training Center
                            </h2>

                            <p className="about-hero-text">
                              မင်္ဂလာပါ ခင်ဗျား။

ကျွန်တော်တို့ YHA Academy of Technology မှ နွေးထွေးစွာ ကြိုဆိုနှုတ်ဆက်အပ်ပါသည်။

YHA Academy ကို ၂၀၁၇ ခုနှစ်၊ ဇွန်လတွင် စတင်တည်ထောင်ခဲ့ပြီး နည်းပညာနယ်ပယ်မှ Software Developer ဘဝ အတွေ့အကြုံများနှင့် သင်ကြားရေး အတွေ့အကြုံများကို အခြေခံကာ မိမိတို့ လူ့ဘောင်အတွက် စွမ်းဆောင်ရည်မြင့် လူငယ်လူရွယ်များကို မွေးထုတ်ပေးနိုင်ရန် ရည်ရွယ်ဖွင့်လှစ်ခဲ့ပါသည်။

ကျွန်တော်တို့ သင်တန်းကျောင်းတွင်-

ကမ္ဘာ့ထိပ်တန်း တက္ကသိုလ်များ၏ သင်ကြားနည်းစနစ်များနှင့် ပြင်ပပညာရှင်များ၏ အကြံပြုချက်များကို အခြေခံထားသော Programming သင်ရိုးညွှန်းတမ်းများ ဖြစ်ခြင်း၊

ဝါသနာပါရာ နယ်ပယ်အလိုက် လက်တွေ့ Project များကို အခြေပြု သင်ကြားပေးခြင်း၊

ကျောင်းသားတစ်ဦးချင့်စီအတွက် ထိရောက်သော အကြံဉာဏ်နှင့် လမ်းညွှန်မှုများ ပေးအပ်နိုင်ခြင်း စသည့် အားသာချက်များဖြင့် စနစ်တကျ သင်ကြားပေးလျက် ရှိပါသည်။

အနာဂတ် နည်းပညာခရီ်းလမ်းကို ယုံကြည်မှုရှိရှိ အတူတကွ လျှောက်လှမ်းလိုသူ လူငယ်များအားလုံးကို ကြိုဆိုဖိတ်ခေါ်အပ်ပါသည်။
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Contact Section */}
            <section id="contact">
                <div className="container">
                    <div className="row g-4 align-items-stretch">
                        <div className="mb-4 col-lg-6 col-12">
                            <div
                                className="p-4 shadow-sm card h-100 contact-card-light"
                                style={{ borderRadius: "20px" }}
                            >
                                <div
                                    className="mb-3 d-flex align-items-center"
                                    style={{ gap: "0.7rem" }}
                                >
                                    <span
                                        style={{
                                            fontSize: "2rem",
                                            color: "#ff6b01",
                                        }}
                                    >
                                        <i className="fa-solid fa-headset"></i>
                                    </span>
                                    <h3
                                        className="mb-0"
                                        style={{
                                            fontWeight: "800",
                                            color: "#222",
                                            letterSpacing: "1px",
                                        }}
                                    >
                                        Contact Us
                                    </h3>
                                </div>
                                {addr && (
                                    <>
                                        <div className="mb-3 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light">
                                                <i className="fa-solid fa-location-dot"></i>
                                            </span>
                                            <span className="contact-label-light">
                                                Address:
                                            </span>
                                            <span className="contact-value-light">
                                                {addr.address || "-"}
                                            </span>
                                        </div>
                                        <div className="mb-3 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light">
                                                <i className="fa-solid fa-phone"></i>
                                            </span>
                                            <span className="contact-label-light">
                                                Phone:
                                            </span>
                                            <span className="contact-value-light">
                                                {addr.yphNo || "-"}
                                            </span>
                                        </div>
                                        <div className="mb-3 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light">
                                                <i className="fa-solid fa-envelope"></i>
                                            </span>
                                            <span className="contact-label-light">
                                                Email:
                                            </span>
                                            <span className="contact-value-light">
                                                {addr.yEmail || "-"}
                                            </span>
                                        </div>
                                        <div className="mb-2 d-flex align-items-center contact-info-light">
                                            <span className="contact-icon-light">
                                                <i className="fa-solid fa-clock"></i>
                                            </span>
                                            <span className="contact-label-light">
                                                Open Hours:
                                            </span>
                                            <span className="contact-value-light">
                                                {addr.OpenClose || "-"}
                                            </span>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                        <div className="mb-4 col-lg-6 col-12">
                            <div
                                className="p-2 shadow-sm card h-100 map-card-light"
                                style={{
                                    borderRadius: "20px",
                                    overflow: "hidden",
                                }}
                            >
                                <div
                                    className="mb-2 d-flex align-items-center"
                                    style={{ gap: "0.7rem" }}
                                >
                                    <span
                                        style={{
                                            fontSize: "1.5rem",
                                            color: "#ff6b01",
                                        }}
                                    >
                                        <i className="fa-solid fa-map"></i>
                                    </span>
                                    <h4
                                        className="mb-0"
                                        style={{
                                            fontWeight: "700",
                                            color: "#222",
                                            letterSpacing: "0.5px",
                                        }}
                                    >
                                        Our Location
                                    </h4>
                                </div>
                                <div className="map-responsive-light">
                                    <iframe
                                        src={
                                            addr?.map_url ||
                                            "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d238.68470692968666!2d96.12988827305935!3d16.828572950010972!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2smm!4v1689075973621!5m2!1sen!2smm"
                                        }
                                        width="100%"
                                        height="280"
                                        style={{
                                            border: 0,
                                            borderRadius: "16px",
                                        }}
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
            <button
                id="goToTopBtn"
                title="Go to top"
                className="shadow-orange-glow text-white text-xl"
                style={{
                    display: "none",
                    position: "fixed",
                    bottom: "32px",
                    right: "32px",
                    zIndex: 9999,
                    background: "var(--primary-orange)",
                    border: "none",
                    borderRadius: "50%",
                    width: "48px",
                    height: "48px",
                    fontSize: "1.7rem",
                    cursor: "pointer",
                }}
                onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}
            >
                <i className="fa-solid fa-arrow-up"></i>
            </button>
            <Footer address={address} />
        </div>
    );
}
