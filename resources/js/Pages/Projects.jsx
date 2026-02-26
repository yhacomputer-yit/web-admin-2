import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function Projects({ projects, prog, graph, ict, address }) {
    return (
        <div className="frontend-page">
            <Navigation prog={prog} graph={graph} ict={ict} />
            
            <style jsx>{`
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
                }

                .project-content h3 {
                    color: #333;
                    margin-bottom: 1rem;
                    font-size: 1.25rem;
                    font-weight: 600;
                }

                .project-content p {
                    color: #666;
                    line-height: 1.6;
                    margin-bottom: 1rem;
                }

                .project-meta {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-top: 1rem;
                }

                .project-course {
                    background: #e9ecef;
                    color: #495057;
                    padding: 0.25rem 0.75rem;
                    border-radius: 20px;
                    font-size: 0.875rem;
                    font-weight: 500;
                }

                .project-date {
                    color: #6c757d;
                    font-size: 0.875rem;
                }

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
                }

                @media (max-width: 576px) {
                    .projects-hero h1 {
                        font-size: 1.5rem;
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

            {/* Course Filter */}
            <section className="course-filter">
                <div className="container">
                    <div className="filter-buttons">
                        <a href="/yha/project/1" className="filter-btn">
                            Programming Projects
                        </a>
                        <a href="/yha/project/2" className="filter-btn">
                            Graphic Design Projects
                        </a>
                    </div>
                </div>
            </section>

            {/* Projects Grid */}
            <section className="projects-grid">
                <div className="container">
                    {projects && projects.data && projects.data.length > 0 ? (
                        <div className="row">
                            {projects.data.map((project) => (
                                <div key={project.id} className="col-lg-4 col-md-6 mb-4">
                                    <div className="project-card">
                                        <div className="project-image">
                                            <img 
                                                src={project.image ? `/storage/${project.image}` : '/image/placeholder.jpg'}
                                                alt={project.name}
                                            />
                                        </div>
                                        <div className="project-content">
                                            <h3>{project.name}</h3>
                                            <p>{project.description}</p>
                                            <div className="project-meta">
                                                <span className="project-course">{project.course?.name}</span>
                                                <span className="project-date">{new Date(project.created_at).toLocaleDateString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-5">
                            <div className="alert alert-info" role="alert">
                                <i className="fa-solid fa-folder-open me-2"></i>
                                No projects available at the moment. Check back soon for student work!
                            </div>
                        </div>
                    )}
                    
                    {/* Pagination */}
                    {projects && projects.links && (
                        <div className="pagination-container">
                            <div dangerouslySetInnerHTML={{ __html: projects.links }} />
                        </div>
                    )}
                </div>
            </section>
            <Footer address={address} />
        </div>
    );
}
