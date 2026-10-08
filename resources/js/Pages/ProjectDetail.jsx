import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/project-detail.css';

export default function ProjectDetail({ project, relatedProjects = [], prog, graph, ict, address }) {
    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const siteName = 'YHA ACADEMY OF TECHNOLOGY';
    const title = `${project?.title || 'Student Project'} - ${siteName}`;
    const description = project?.desc || `View ${project?.title || 'this student project'} from ${project?.course?.name || 'YHA ACADEMY OF TECHNOLOGY'} course. Programming, Graphic Design, and ICT projects by our students.`;
    const ogImage = project?.image ? `/storage/${project.image}` : '/image/logo/logo.png';

    const keywords = [
        project?.title || 'student project',
        'student project',
        project?.course?.name || 'course',
        siteName,
        'projects',
        'student portfolio',
        'Data Science',
        'AI',
        'Machine Learning',
        'Mobile Development',
        'Flutter',
        'Dart',
        'React',
        'Vue',
        'Laravel',
        'PHP',
        'JavaScript',
        'MERN Stack',
        'Web Development',
        'MySQL',
        'MongoDB'
    ].filter(Boolean).join(', ');

    return (
        <>
            <Head>
                <title>{title}</title>
                <meta name="description" content={description} />
                <meta name="keywords" content={keywords} />
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta property="og:image" content={ogImage} />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content={siteName} />
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:title" content={title} />
                <meta name="twitter:description" content={description} />
                <meta name="twitter:image" content={ogImage} />
                <link rel="canonical" href={window.location.href} />
            </Head>
            <div className="frontend-page">
                <Navigation prog={prog} graph={graph} ict={ict} />

            {/* ========== Hero Section ========== */}
            <section className="pd-hero">
                <div className="container pd-container">
                    <div className="pd-hero-inner">
                        <div
                            className="pd-hero-bg"
                            style={{ backgroundImage: `url(/storage/${project?.image})` }}
                        ></div>
                        <div className="pd-hero-overlay"></div>
                        <div className="pd-hero-content">
                            <nav className="pd-breadcrumb" aria-label="Breadcrumb">
                                <Link href="/" className="pd-breadcrumb-link">
                                    <i className="fas fa-home"></i> Home
                                </Link>
                                <span className="pd-breadcrumb-separator">/</span>
                                <Link href="/yha/project" className="pd-breadcrumb-link">Projects</Link>
                                <span className="pd-breadcrumb-separator">/</span>
                                <span className="pd-breadcrumb-current">{project?.title}</span>
                            </nav>
                            <h1 className="pd-title">{project?.title}</h1>
                            <div className="pd-meta">
                                <span className="pd-category">
                                    <i className="fas fa-tag"></i>
                                    {project?.course?.name || project?.category || 'Student Work'}
                                </span>
                                <span className="pd-date">
                                    <i className="fas fa-calendar"></i>
                                    {project?.created_at ? formatDate(project.created_at) : 'N/A'}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========== Project Content ========== */}
            <section className="pd-content">
                <div className="container pd-container">
                    <div className="row gy-5 align-items-start">
                        {/* Left - Main Content */}
                        <div className="col-lg-8">
                            <div className="pd-main-card">
                                {project?.image && (
                                    <div className="pd-image-wrapper">
                                        <img
                                            src={`/storage/${project.image}`}
                                            alt={project.title}
                                            className="pd-main-image"
                                        />
                                    </div>
                                )}

                                <div className="pd-info">
                                    <div className="pd-header">
                                        <h2 className="pd-title-main">{project?.title}</h2>
                                        {project?.course && (
                                            <Link href={`/yha/course/${project.course.id}`} className="pd-course-link">
                                                <i className="fas fa-book"></i>
                                                Course: {project.course.name}
                                            </Link>
                                        )}
                                    </div>

                                    <div className="pd-description" dangerouslySetInnerHTML={{ __html: project?.desc || project?.description || '<p>No description available.</p>' }} />
                                </div>

                                {project?.technologies && (
                                    <div className="pd-tech-stack">
                                        <h3 className="pd-section-title">Technologies Used</h3>
                                        <div className="pd-tech-tags">
                                            {project.technologies.split(',').map((tech, index) => (
                                                <span key={index} className="pd-tech-tag">{tech.trim()}</span>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Right - Sidebar */}
                        <div className="col-lg-4">
                            <div className="pd-sidebar">
                                <div className="pd-sidebar-card">
                                    <h3 className="pd-sidebar-title">Student Information</h3>
                                    <div className="pd-student-info">
                                        <div className="pd-student-avatar">
                                            <img
                                                        src={
                                                            project.student?.image
                                                                ? `/storage/${project.student.image}`
                                                                : '/image/logo/student-placeholder.svg'
                                                        }
                                                alt={project?.student?.name || 'Student'}
                                                onError={(e) => {
                                                    e.target.src = '/image/logo/stud1.jpeg';
                                                }}
                                            />
                                        </div>
                                        <div className="pd-student-details">
                                            <h4>{project?.student?.name || 'Student'}</h4>
                                            <p>{project?.student?.education || 'No education info'}</p>
                                        </div>
                                    </div>
                                </div>

                                <div className="pd-sidebar-card">
                                    <h3 className="pd-sidebar-title">Project Details</h3>
                                    <ul className="pd-detail-list">
                                        <li>
                                            <span className="pd-detail-label">Category</span>
                                            <span className="pd-detail-value">{project?.course?.name || project?.category || 'Student Work'}</span>
                                        </li>
                                        <li>
                                            <span className="pd-detail-label">Completed</span>
                                            <span className="pd-detail-value">{project?.created_at ? formatDate(project.created_at) : 'N/A'}</span>
                                        </li>
                                        {project?.duration && (
                                            <li>
                                                <span className="pd-detail-label">Duration</span>
                                                <span className="pd-detail-value">{project.duration}</span>
                                            </li>
                                        )}
                                    </ul>
                                </div>

                                <div className="pd-sidebar-card">
                                    <h3 className="pd-sidebar-title">Back to Projects</h3>
                                    <Link href="/yha/project" className="pd-back-link">
                                        <i className="fas fa-arrow-left"></i>
                                        Browse All Projects
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========== Related Projects ========== */}
            {relatedProjects && relatedProjects.length > 0 && (
                <section className="pd-related">
                    <div className="container pd-container">
                        <div className="pd-related-header">
                            <div>
                                <h2 className="pd-section-title">Related Projects</h2>
                                <p className="pd-section-subtitle">Explore more student projects</p>
                            </div>
                        </div>

                        <div className="pd-related-grid">
                            {relatedProjects.map((item, index) => (
                                <div key={item.id || index} className="pd-related-card">
                                    <div className="pd-related-image-wrap">
                                        <img
                                            src={item.image ? `/storage/${item.image}` : '/placeholder.jpg'}
                                            alt={item.title || 'Project'}
                                            className="pd-related-image"
                                            onError={(e) => { e.target.src = '/placeholder.jpg'; }}
                                        />
                                        <div className="pd-related-overlay">
                                            <Link href={`/project-detail/${item.id}`} className="pd-related-view">
                                                <i className="fas fa-eye"></i>
                                                View Project
                                            </Link>
                                        </div>
                                    </div>
                                    <div className="pd-related-body">
                                        <div className="pd-related-category">
                                            {item.course?.name || item.category || 'Student Work'}
                                        </div>
                                        <h3 className="pd-related-title">{item.title}</h3>
                                        <p className="pd-related-desc">
                                            {item.desc || item.description || 'Project description'}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <Footer address={address} />
        </div>
    </>
    );
}