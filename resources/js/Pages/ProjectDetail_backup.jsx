import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';

export default function ProjectDetail({ project }) {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            <style jsx>{`
                .project-detail-hero {
                    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
                    padding: 80px 0;
                    color: white;
                    text-align: center;
                }

                .project-detail-content {
                    padding: 80px 0;
                    background: #f8f9fa;
                }

                .project-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                }

                .project-image {
                    width: 100%;
                    max-height: 400px;
                    object-fit: cover;
                    border-radius: 15px;
                    margin-bottom: 2rem;
                }

                .project-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    color: #2c3e50;
                    margin-bottom: 1rem;
                }

                .project-course {
                    color: #ff6b01;
                    font-weight: 600;
                    margin-bottom: 1.5rem;
                }

                .project-description {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #495057;
                    margin-bottom: 2rem;
                }

                .project-links {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                }

                .project-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 1rem 2rem;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }

                .github-link {
                    background: #24292e;
                    color: white;
                }

                .demo-link {
                    background: #ff6b01;
                    color: white;
                }

                .project-link:hover {
                    transform: translateY(-2px);
                }

                .back-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    color: #ff6b01;
                    text-decoration: none;
                    font-weight: 600;
                    margin-bottom: 2rem;
                }

                .back-link:hover {
                    color: #e65b00;
                }
            `}</style>

            <section className="project-detail-hero">
                <div className="container">
                    <Link href="/yha/project" className="back-link">
                        <i className="fas fa-arrow-left"></i>
                        Back to Projects
                    </Link>
                    <h1 className="project-title">{project?.title}</h1>
                    <div className="project-course">
                        <i className="fas fa-graduation-cap"></i> {project?.course?.name}
                    </div>
                </div>
            </section>

            <section className="project-detail-content">
                <div className="container">
                    <div className="project-card">
                        {project?.image && (
                            <img src={`/storage/${project.image}`} className="project-image" alt={project.title} />
                        )}
                        
                        <div className="project-description" dangerouslySetInnerHTML={{ __html: project?.desc }} />
                        
                        <div className="project-links">
                            {project?.github && (
                                <a href={project.github} className="project-link github-link" target="_blank" rel="noopener noreferrer">
                                    <i className="fab fa-github"></i>
                                    View on GitHub
                                </a>
                            )}
                            {project?.demo && (
                                <a href={project.demo} className="project-link demo-link" target="_blank" rel="noopener noreferrer">
                                    <i className="fas fa-play"></i>
                                    Live Demo
                                </a>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}
