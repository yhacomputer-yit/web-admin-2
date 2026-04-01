import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

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
            
            <style jsx>{`
                /* Premium Projects Page Styles - Matching Homepage Design */
                .projects-section {
                    background: #ffffff;
                    position: relative;
                    overflow: hidden;
                    padding: 6rem 0;
                    margin-bottom: 0;
                    border-bottom: 1px solid #f8f9fa;
                }

                @media (max-width: 768px) {
                    .projects-section {
                        padding: 4rem 0;
                    }
                }

                @media (max-width: 576px) {
                    .projects-section {
                        padding: 3rem 0 !important;
                    }
                }

                /* Section Header - Premium Design */
                .hit-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.6rem;
                    background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
                    color: white;
                    padding: 0.6rem 1.8rem;
                    border-radius: 50px;
                    font-size: 0.85rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 1.2px;
                    margin-bottom: 2rem;
                    box-shadow: 0 4px 20px rgba(255,107,53,0.25);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                }

                .hit-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    line-height: 1.2;
                    letter-spacing: -0.02em;
                    color: #1e293b;
                }

                .hit-title span {
                    display: block;
                }

                .hit-title span:first-child {
                    color: #1e293b;
                }

                .hit-title span:last-child {
                    color: #ff6b35;
                }

                .hit-subtitle {
                    font-size: 1.1rem;
                    color: #64748b;
                    max-width: 700px;
                    margin: 0 auto;
                    line-height: 1.7;
                    font-weight: 400;
                    letter-spacing: 0.01em;
                }

                /* Modern Project Cards - Premium Design */
                .modern-project-card {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
                    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
                    border: 1px solid rgba(0,0,0,0.06);
                    position: relative;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 2rem;
                }

                .modern-project-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 8px 20px rgba(0,0,0,0.08);
                    border-color: rgba(0,0,0,0.1);
                }

                .modern-project-image-container {
                    position: relative;
                    width: 100%;
                    height: 220px;
                    overflow: hidden;
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                }

                .modern-project-image {
                    width: 100%;
                    height: 220px;
                    object-fit: cover;
                    transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
                    opacity: 0.95;
                }

                .modern-project-card:hover .modern-project-image {
                    transform: scale(1.06);
                    opacity: 1;
                    filter: brightness(1.05);
                }

                .modern-project-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.3) 100%);
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    padding: 1rem;
                    opacity: 0;
                    transition: opacity 0.4s ease;
                }

                .modern-project-card:hover .modern-project-overlay {
                    opacity: 1;
                }

                .modern-project-category {
                    background: rgba(255,255,255,0.95);
                    color: #ff6b35;
                    padding: 0.4rem 0.8rem;
                    border-radius: 12px;
                    font-size: 0.7rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    backdrop-filter: blur(10px);
                    box-shadow: 0 2px 8px rgba(255,107,53,0.15);
                    border: 1px solid rgba(255,255,255,0.8);
                }

                .modern-project-content {
                    padding: 1.5rem;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .modern-project-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #1e293b;
                    line-height: 1.4;
                    margin: 0;
                    letter-spacing: -0.01em;
                }

                .modern-project-title a {
                    color: inherit;
                    text-decoration: none;
                    transition: color 0.3s ease;
                }

                .modern-project-title a:hover {
                    color: #ff6b35;
                }

                .modern-project-description {
                    font-size: 0.875rem;
                    color: #64748b;
                    line-height: 1.6;
                    flex: 1;
                    margin: 0;
                    font-weight: 400;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .modern-project-meta {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 1rem 1.5rem;
                    background: linear-gradient(135deg, #fafbfc, #f8fafc);
                    border-top: 1px solid rgba(0,0,0,0.06);
                    margin-top: auto;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }

                .modern-project-links {
                    display: flex;
                    gap: 0.5rem;
                }

                .modern-project-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.5rem 0.8rem;
                    background: white;
                    color: #64748b;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.7rem;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                    transition: all 0.3s ease;
                    white-space: nowrap;
                }

                .modern-project-btn:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }

                .modern-project-btn.github-btn:hover {
                    background: #24292e;
                    color: white;
                    border-color: #24292e;
                }

                .modern-project-btn.demo-btn:hover {
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    color: white;
                    border-color: #ff6b35;
                }

                .modern-project-btn.disabled {
                    pointer-events: none;
                    opacity: 0.5;
                    cursor: not-allowed;
                }

                .modern-project-btn.disabled:hover {
                    transform: none;
                }

                .modern-no-projects {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    border: 1px solid rgba(255,107,53,0.08);
                }

                .modern-no-projects-icon {
                    width: 80px;
                    height: 80px;
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 2rem;
                    color: white;
                    font-size: 2rem;
                    box-shadow: 0 8px 20px rgba(255,107,53,0.3);
                }

                .modern-no-projects h3 {
                    font-size: 1.8rem;
                    font-weight: 800;
                    color: #1e293b;
                    margin-bottom: 1rem;
                }

                .modern-no-projects p {
                    font-size: 1rem;
                    color: #64748b;
                    margin-bottom: 2rem;
                    max-width: 400px;
                    margin-left: auto;
                    margin-right: auto;
                }

                .modern-browse-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.8rem;
                    padding: 0.8rem 1.5rem;
                    background: white;
                    color: #ff6b35;
                    border: 2px solid #ff6b35;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                }

                .modern-browse-btn:hover {
                    background: #ff6b35;
                    color: white;
                    border-color: transparent;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(255,107,53,0.3);
                }

                .modern-view-all {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.8rem;
                    padding: 1rem 2rem;
                    background: white;
                    color: #ff6b35;
                    border: 2px solid #ff6b35;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 700;
                    font-size: 1rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                }

                .modern-view-all:hover {
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    color: white;
                    border-color: transparent;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(255,107,53,0.3);
                }

                .modern-view-all i {
                    font-size: 0.9rem;
                    transition: transform 0.3s ease;
                }

                .modern-view-all:hover i {
                    transform: translateX(4px);
                }

                /* Course Filter - Premium Design */
                .course-filter {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
                    padding: 2rem;
                    margin-bottom: 3rem;
                    border: 1px solid rgba(0,0,0,0.06);
                }

                .filter-buttons {
                    display: flex;
                    justify-content: center;
                    gap: 1rem;
                    flex-wrap: wrap;
                }

                .filter-btn {
                    background: linear-gradient(135deg, #f8fafc, #ffffff);
                    color: #475569;
                    border: 1px solid #e2e8f0;
                    padding: 0.8rem 2rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    text-transform: capitalize;
                    letter-spacing: 0.3px;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .filter-btn:hover,
                .filter-btn.active {
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    color: white;
                    border-color: #ff6b35;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(255,107,53,0.3);
                }

                /* Pagination Styles - Matching Homepage */
                .pagination-container {
                    display: flex;
                    justify-content: center;
                    margin-top: 3rem;
                }

                .pagination-container .btn {
                    min-width: 40px;
                    height: 40px;
                    padding: 0.5rem 0.75rem;
                    border-radius: 8px;
                    font-size: 0.875rem;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }

                .pagination-container .btn:hover:not(:disabled) {
                    transform: translateY(-1px);
                }

                .pagination-container .btn:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }

                .pagination-container .btn-primary {
                    background: linear-gradient(135deg, #ff6b01, #ffb347);
                    border-color: #ff6b01;
                    color: white;
                }

                .pagination-container .btn-outline-primary {
                    background: transparent;
                    border-color: #ff6b01;
                    color: #ff6b01;
                }

                .pagination-container .btn-outline-primary:hover {
                    background: #ff6b01;
                    color: white;
                }

                .pagination-container .btn-outline-secondary {
                    background: transparent;
                    border-color: #6c757d;
                    color: #6c757d;
                }

                .pagination-container .btn-outline-secondary:hover:not(:disabled) {
                    background: #6c757d;
                    color: white;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .hit-title {
                        font-size: 1.8rem;
                    }
                    
                    .hit-subtitle {
                        font-size: 0.95rem;
                    }
                    
                    .modern-project-content {
                        padding: 1rem;
                    }
                    
                    .modern-project-title {
                        font-size: 1rem;
                    }
                    
                    .modern-project-description {
                        font-size: 0.8rem;
                    }
                    
                    .course-filter {
                        padding: 1.5rem;
                    }
                    
                    .filter-buttons {
                        flex-direction: column;
                        align-items: center;
                    }
                    
                    .filter-btn {
                        width: 100%;
                        max-width: 200px;
                        text-align: center;
                    }
                }

                @media (max-width: 576px) {
                    .hit-title {
                        font-size: 1.6rem;
                    }
                    
                    .hit-subtitle {
                        font-size: 0.9rem;
                    }
                    
                    .modern-project-image-container {
                        height: 180px;
                    }
                    
                    .modern-project-image {
                        height: 180px;
                    }
                    
                    .modern-project-title {
                        font-size: 0.95rem;
                    }
                    
                    .modern-project-description {
                        font-size: 0.75rem;
                    }
                    
                    .modern-project-meta {
                        padding: 0.8rem 1rem;
                    }
                    
                    .modern-project-links {
                        flex-direction: column;
                    }
                }
            `}</style>

            {/* Projects Section - Premium Design */}
            <section className="projects-section">
                <div className="container">
                    {/* Section Header */}
                    <div className="text-center mb-5">
                        <div className="hit-badge">
                            <i className="fa-solid fa-code"></i>
                            <span>Student Projects</span>
                        </div>
                        <h1 className="hit-title">
                            <span>Explore Innovative</span>
                            <span>Student Projects</span>
                        </h1>
                        <p className="hit-subtitle">Discover amazing projects created by our talented students across different courses and technologies.</p>
                    </div>

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
                    <div className="row g-4">
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
                                                {project.github ? (
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
                                                )}
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
