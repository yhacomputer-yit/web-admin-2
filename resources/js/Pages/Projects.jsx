import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Projects({ projects, prog, graph, ict, address }) {
    const [currentPage, setCurrentPage] = useState(1);
    const projectsPerPage = 4;
    
    // Handle projects data structure
    const projectsData = Array.isArray(projects) ? projects : (projects?.data || []);
    const totalPages = Math.ceil(projectsData.length / projectsPerPage);
    
    // Pagination logic
    const indexOfLastProject = currentPage * projectsPerPage;
    const indexOfFirstProject = indexOfLastProject - projectsPerPage;
    const currentProjects = projectsData.slice(indexOfFirstProject, indexOfLastProject);
    
    const paginate = (pageNumber) => setCurrentPage(pageNumber);
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            <style jsx>{`
                /* Modern Projects Page Styles */
                .projects-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 3rem 0;
                    margin-bottom: 3rem;
                    border-radius: 0 0 30px 30px;
                    color: white;
                    text-align: center;
                }

                .projects-hero h1 {
                    font-size: 2.5rem;
                    font-weight: 800;
                    margin-bottom: 1rem;
                    letter-spacing: 1px;
                }

                .projects-hero p {
                    font-size: 1.1rem;
                    opacity: 0.9;
                    max-width: 600px;
                    margin: 0 auto;
                }

                .course-filter {
                    background: #fff;
                    border-radius: 20px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    padding: 2rem;
                    margin-bottom: 3rem;
                    border: 1px solid rgba(255,107,1,0.1);
                }

                .filter-buttons {
                    display: flex;
                    justify-content: center;
                    gap: 1rem;
                    flex-wrap: wrap;
                }

                .filter-btn {
                    background: #f8f9fa;
                    color: #666;
                    border: 2px solid #e9ecef;
                    padding: 0.8rem 2rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    display: inline-block;
                }

                .filter-btn:hover,
                .filter-btn.active {
                    background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
                    color: white;
                    border-color: #ff6b01;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(255,107,1,0.3);
                }

                .project-card {
                    background: #fff;
                    border-radius: 20px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    overflow: hidden;
                    transition: all 0.3s ease;
                    margin-bottom: 2rem;
                    border: 1px solid rgba(255,107,1,0.1);
                    height: 100%;
                }

                .project-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 12px 40px rgba(255,107,1,0.15);
                }

                .project-image {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }

                .project-card:hover .project-image {
                    transform: scale(1.05);
                }

                .project-content {
                    padding: 1.5rem;
                    display: flex;
                    flex-direction: column;
                }

                .project-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #333;
                    margin-bottom: 0.5rem;
                }

                .project-title-link {
                    color: #333;
                    text-decoration: none;
                    transition: color 0.3s ease;
                }

                .project-title-link:hover {
                    color: #ff6b01;
                }

                .project-course {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                    color: #ff6b01;
                    font-weight: 600;
                }

                .project-description {
                    color: #666;
                    line-height: 1.6;
                    margin-bottom: 1.5rem;
                }

                .project-links {
                    display: flex;
                    gap: 0.8rem;
                    justify-content: center;
                }

                .project-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.6rem 1.2rem;
                    border-radius: 20px;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    transition: all 0.3s ease;
                    border: 1px solid transparent;
                    flex: 1;
                    justify-content: center;
                }

                .github-link {
                    background: #24292e;
                    color: #fff;
                }

                .github-link:hover {
                    background: #2f363d;
                    color: #fff;
                    transform: translateY(-2px);
                }

                .demo-link {
                    background: #ff6b01;
                    color: #fff;
                }

                .demo-link:hover {
                    background: #e65b00;
                    color: #fff;
                    transform: translateY(-2px);
                }

                .project-link.disabled {
                    pointer-events: none;
                    opacity: 0.5;
                    cursor: not-allowed;
                }

                .project-link.disabled:hover {
                    transform: none;
                }

                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    background: #f8f9fa;
                    border-radius: 20px;
                    border: 2px dashed #dee2e6;
                }

                .empty-state i {
                    font-size: 3rem;
                    color: #ff6b01;
                    margin-bottom: 1rem;
                }

                .empty-state h3 {
                    color: #333;
                    margin-bottom: 1rem;
                }

                .empty-state p {
                    color: #666;
                    max-width: 400px;
                    margin: 0 auto;
                }

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
                    .projects-hero {
                        padding: 2rem 0;
                        border-radius: 0 0 20px 20px;
                    }

                    .projects-hero h1 {
                        font-size: 2rem;
                    }

                    .projects-hero p {
                        font-size: 1rem;
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

                    .project-content {
                        padding: 1rem;
                    }

                    .project-links {
                        flex-direction: column;
                    }
                }

                @media (max-width: 576px) {
                    .projects-hero h1 {
                        font-size: 1.5rem;
                    }

                    .project-title {
                        font-size: 1.1rem;
                    }

                    .project-image {
                        height: 180px;
                    }
                }
            `}</style>

            {/* Hero Section */}
            <section className="projects-hero">
                <div className="container">
                    <h1>Student Projects</h1>
                    <p>Explore innovative projects created by our talented students across different courses and technologies.</p>
                </div>
            </section>

            {/* Projects Section */}
            <section id="proj">
                <div className="container">
                    <div className="course-filter">
                        <div className="filter-buttons">
                            <a href="/yha/project/1" className="filter-btn">
                                <i className="fa-solid fa-code me-2"></i>
                                Programming
                            </a>
                            <a href="/yha/project/2" className="filter-btn">
                                <i className="fa-solid fa-palette me-2"></i>
                                Graphic Design
                            </a>
                        </div>
                    </div>

                    <div className="row" id="projects-container">
                        {currentProjects && currentProjects.length > 0 ? (
                            currentProjects.map((project) => (
                                <div key={project.id} className="mb-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div className="project-card">
                                        <img src={`/storage/${project.image}`} className="project-image" alt={project.title} />
                                        <div className="project-content">
                                            <h5 className="project-title">
                                                <Link href={`/yha/project/detail/${project.id}`} className="project-title-link">{project.title}</Link>
                                            </h5>
                                            <div className="project-course">
                                                <i className="fa-solid fa-graduation-cap"></i>
                                                <span>{project.course?.name || 'Student Project'}</span>
                                            </div>
                                            <p className="project-description">{project.desc || project.description || 'Project description'}</p>
                                            <div className="project-links">
                                                {project.github ? (
                                                    <a href={project.github} className="project-link github-link" target="_blank" rel="noopener noreferrer">
                                                        <i className="fa-brands fa-github"></i>
                                                        <span>GitHub</span>
                                                    </a>
                                                ) : (
                                                    <span className="project-link github-link disabled">
                                                        <i className="fa-brands fa-github"></i>
                                                        <span>GitHub</span>
                                                    </span>
                                                )}
                                                {project.demo ? (
                                                    <a href={project.demo} className="project-link demo-link" target="_blank" rel="noopener noreferrer">
                                                        <i className="fa-solid fa-play"></i>
                                                        <span>Live Demo</span>
                                                    </a>
                                                ) : (
                                                    <span className="project-link demo-link disabled">
                                                        <i className="fa-solid fa-play"></i>
                                                        <span>Live Demo</span>
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="col-12">
                                <div className="empty-state">
                                    <i className="fa-solid fa-code-branch"></i>
                                    <h3>No Projects Found</h3>
                                    <p>There are no projects to display on this page.</p>
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
                    {(totalPages > 1 || (projectsData && projectsData.length > 0)) && (
                        <div className="text-center mt-3">
                            <small className="text-muted">
                                Showing {indexOfFirstProject + 1}-{Math.min(indexOfLastProject, projectsData.length)} of {projectsData.length} projects
                            </small>
                        </div>
                    )}
                </div>
            </section>

            <Footer address={address} />
        </div>
    );
}
