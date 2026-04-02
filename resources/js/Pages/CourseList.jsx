import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/courselist.css';

export default function CourseList({ courses, prog, graph, ict, address }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            {/* Course List Section */}
            <section className="course-list-section">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fa-solid fa-graduation-cap"></i>
                            <span>All Courses</span>
                        </div>
                        <h1 className="hit-title">
                            <span>Explore Our</span>
                            <span>Course Catalog</span>
                        </h1>
                        <p className="hit-subtitle">Discover our comprehensive range of courses designed to help you master new skills and advance your career.</p>
                    </div>

                    {/* Courses Grid */}
                    <div className="courses-grid">
                        {courses && courses.length > 0 ? (
                            courses.map((course) => (
                                <Link key={course.id} href={`/course/${course.id}`} className="modern-course-card">
                                    <div className="course-image-container">
                                        <img 
                                            src={`/storage/${course.image}`} 
                                            alt={course.name} 
                                            className="course-image"
                                            onError={(e) => {
                                                e.target.src = 'https://via.placeholder.com/350x220/ff6b35/ffffff?text=Course';
                                            }}
                                        />
                                        <div className="course-overlay">
                                            <div className="view-course-btn">
                                                <i className="fa-solid fa-arrow-right"></i>
                                                View Course
                                            </div>
                                        </div>
                                    </div>
                                    <div className="course-content">
                                        {/* <div className="course-category">
                                            <i className="fa-solid fa-book"></i>
                                            {course.courseType?.name || 'General'}
                                        </div> */}
                                        <h3 className="course-title">{course.name}</h3>
                                        <p className="course-description">{course.description}</p>
                                        <div className="course-meta">
                                            <div className="course-meta-item">
                                                <i className="fa-solid fa-clock"></i>
                                                <span>{course.duration || 'Flexible'} Hours</span>
                                            </div>
                                            <div className="course-meta-item">
                                                <i className="fa-solid fa-certificate"></i>
                                                <span>Certificate</span>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            ))
                        ) : (
                            <div className="empty-state">
                                <i className="fa-solid fa-book-open"></i>
                                <h3>No Courses Available</h3>
                                <p>Courses will be updated soon. Please check back later.</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
