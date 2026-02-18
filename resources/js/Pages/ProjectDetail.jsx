import { Link } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';

export default function ProjectDetail({ project }) {
    return (
        <div className="frontend-page">
            <Navigation prog={[]} graph={[]} ict={[]} />
            
            {/* Hero Section */}
            <section className="project-detail-hero">
                <div className="container">
                    <h1>{project?.title || 'Project Details'}</h1>
                    <p className="breadcrumb">
                        <Link href="/">Home</Link> / 
                        <Link href="/yha/project">Projects</Link> / 
                        <span>{project?.title || 'Project'}</span>
                    </p>
                </div>
            </section>

            {/* Project Content */}
            <section className="project-content">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-8">
                            <div className="project-main">
                                {project?.image && (
                                    <div className="project-image">
                                        <img src={`/storage/${project.image}`} alt={project.title} />
                                    </div>
                                )}
                                
                                <div className="project-info">
                                    <h2>{project?.title}</h2>
                                    <p>{project?.desc}</p>
                                    
                                    {project?.course && (
                                        <div className="project-course">
                                            <h4>Course: {project.course.name}</h4>
                                            <Link href={`/yha/course/${project.course.id}`} className="btn btn-primary">
                                                View Course
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                        
                        <div className="col-lg-4">
                            <div className="project-sidebar">
                                <div className="sidebar-card">
                                    <h4>Project Details</h4>
                                    <ul>
                                        <li>
                                            <strong>Student:</strong> {project?.student_name || 'N/A'}
                                        </li>
                                        <li>
                                            <strong>Category:</strong> {project?.course?.name || 'N/A'}
                                        </li>
                                        <li>
                                            <strong>Completed:</strong> {project?.created_at ? new Date(project.created_at).toLocaleDateString() : 'N/A'}
                                        </li>
                                    </ul>
                                </div>
                                
                                {(project?.github || project?.demo) && (
                                    <div className="sidebar-card">
                                        <h4>Links</h4>
                                        {project?.github && (
                                            <a href={project.github} className="btn btn-outline-primary mb-2" target="_blank" rel="noopener noreferrer">
                                                <i className="fab fa-github"></i> View on GitHub
                                            </a>
                                        )}
                                        {project?.demo && (
                                            <a href={project.demo} className="btn btn-primary" target="_blank" rel="noopener noreferrer">
                                                <i className="fas fa-external-link-alt"></i> Live Demo
                                            </a>
                                        )}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <Footer address={[]} />
        </div>
    );
}
