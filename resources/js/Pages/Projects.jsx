import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/project.css';

export default function Projects({ projects, prog, graph, ict, address }) {
    const [currentPage, setCurrentPage] = useState(1);
    const [activeFilter, setActiveFilter] = useState('all');
    const projectsPerPage = 4;

    // Handle projects data structure
    const projectsData = Array.isArray(projects) ? projects : (projects?.data || []);

    // Filter projects based on active filter
    const filteredProjects = projectsData.filter(project => {
        if (activeFilter === 'all') return true;
        if (activeFilter === '1') return project.course_id === 1; // Programming
        if (activeFilter === '2') return project.course_id === 2; // Graphic Design
        return true;
    });

    // Pagination logic
    const totalPages = Math.ceil(filteredProjects.length / projectsPerPage);
    const indexOfLastProject = currentPage * projectsPerPage;
    const indexOfFirstProject = indexOfLastProject - projectsPerPage;
    const currentProjects = filteredProjects.slice(indexOfFirstProject, indexOfLastProject);

    const paginate = (pageNumber) => setCurrentPage(pageNumber);
    const handleFilter = (filter) => {
        setActiveFilter(filter);
        setCurrentPage(1); // Reset to first page when filter changes
    };
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />

            {/* Projects Section - Premium Design */}
            <section className="projects-section">
                <div className="container">
                    {/* Section Header */}
                    {/* <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fa-solid fa-code"></i>
                            <span>Student Projects</span>
                        </div>
                        <h1 className="hit-title">
                            <span>Explore Innovative</span>
                            <span>Student Projects</span>
                        </h1>
                        <p className="hit-subtitle">Discover amazing projects created by our talented students across different courses and technologies.</p>
                    </div> */}

                    {/* Course Filter */}
                    <div className="course-filter">
                        <div className="filter-buttons">
                            <button
                                className={`filter-btn ${activeFilter === 'all' ? 'active' : ''}`}
                                onClick={() => handleFilter('all')}
                            >
                                <i className="fa-solid fa-border-all me-2"></i>
                                All Projects
                            </button>
                            <button
                                className={`filter-btn ${activeFilter === '1' ? 'active' : ''}`}
                                onClick={() => handleFilter('1')}
                            >
                                <i className="fa-solid fa-code me-2"></i>
                                Programming
                            </button>
                            <button
                                className={`filter-btn ${activeFilter === '2' ? 'active' : ''}`}
                                onClick={() => handleFilter('2')}
                            >
                                <i className="fa-solid fa-palette me-2"></i>
                                Graphic Design
                            </button>
                        </div>
                    </div>

                    {/* Modern Projects Grid */}
                    <div className="row g-1">
                        {currentProjects && currentProjects.length > 0 ? (
                            currentProjects.map((project) => (
                                <div key={project.id} className="col-xl-4 col-lg-4 col-md-6">
                                    <div className="modern-project-card">
                                        {/* Project Image */}
                                        <div className="modern-project-image-container">
                                            <img
                                                src={project.image ? `/storage/${project.image}` : '/placeholder.jpg'}
                                                alt={project.title || 'Project'}
                                                className="modern-project-image"
                                                onError={(e) => { e.target.src = '/placeholder.jpg'; }}
                                            />
                                            <div className="modern-project-overlay">
                                                <div className="modern-project-category">
                                                    {project.course?.name || 'Student Project'}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Project Content */}
                                        <div className="modern-project-content">
                                            <h3 className="modern-project-title">
                                                <Link href={`/yha/project/detail/${project.id}`} className="project-title-link">
                                                    {project.title || 'Project Title'}
                                                </Link>
                                            </h3>
                                            <p className="modern-project-description">
                                                {project.desc || project.description || 'Project description'}
                                            </p>
                                        </div>

                                        {/* Project Footer */}
                                        <div className="modern-project-meta">
                                            <div className="modern-project-links">
                                                {/* {project.github ? (
                                                    <a href={project.github} className="modern-project-btn github-btn" target="_blank" rel="noopener noreferrer">
                                                        <i className="fab fa-github"></i>
                                                        <span>Code</span>
                                                    </a>
                                                ) : (
                                                    <span className="modern-project-btn github-btn disabled">
                                                        <i className="fab fa-github"></i>
                                                        <span>Code</span>
                                                    </span>
                                                )}
                                                {project.demo ? (
                                                    <a href={project.demo} className="modern-project-btn demo-btn" target="_blank" rel="noopener noreferrer">
                                                        <i className="fa-solid fa-play"></i>
                                                        <span>Demo</span>
                                                    </a>
                                                ) : (
                                                    <span className="modern-project-btn demo-btn disabled">
                                                        <i className="fa-solid fa-play"></i>
                                                        <span>Demo</span>
                                                    </span>
                                                )} */}

                                                <Link
                                                // href={`/course/${monthly.id}`}
                                                className="learnmore"
                                            >
                                                <span>Learn More</span>
                                                <i className="fas fa-arrow-right ms-2 learnmore"></i>
                                            </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="modern-no-projects">
                                    <div className="modern-no-projects-icon">
                                        <i className="fa-solid fa-code-branch"></i>
                                    </div>
                                    <h3>No Projects Found</h3>
                                    <p>There are no projects to display on this page.</p>
                                    <Link href="/" className="modern-browse-btn">
                                        <i className="fa-solid fa-home"></i>
                                        <span>Back to Home</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Pagination */}
                    {(totalPages > 1 || (projectsData && projectsData.length > 0)) && (
                        <div className="pagination-container">
                            <div className="d-flex justify-content-center align-items-center gap-2">
                                <button
                                    className="btn btn-outline-secondary"
                                    onClick={() => paginate(currentPage - 1)}
                                    disabled={currentPage === 1}
                                >
                                    <i className="fa-solid fa-chevron-left"></i>
                                </button>

                                <div className="d-flex gap-1">
                                    {[...Array(totalPages)].map((_, index) => (
                                        <button
                                            key={index + 1}
                                            className={`btn ${currentPage === index + 1 ? 'btn-primary' : 'btn-outline-primary'}`}
                                            onClick={() => paginate(index + 1)}
                                        >
                                            {index + 1}
                                        </button>
                                    ))}
                                </div>

                                <button
                                    className="btn btn-outline-secondary"
                                    onClick={() => paginate(currentPage + 1)}
                                    disabled={currentPage === totalPages}
                                >
                                    <i className="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    )}

                    {/* Pagination Info */}
                    {(totalPages > 1 || (filteredProjects && filteredProjects.length > 0)) && (
                        <div className="text-center mt-3">
                            <small className="text-muted">
                                Showing {indexOfFirstProject + 1}-{Math.min(indexOfLastProject, filteredProjects.length)} of {filteredProjects.length} projects
                            </small>
                        </div>
                    )}
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
