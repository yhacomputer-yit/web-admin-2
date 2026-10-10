import { Link } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { Head } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/project.css';

export default function Projects({
    projects,
    courses = [],
    prog,
    graph,
    ict,
    address,
    subjects = [],
    courseCounts = {},
    subjectCourseMap = {},
    courseSubjects = {},
}) {
    const [search, setSearch] = useState('');
    const [courseId, setCourseId] = useState('all');
    const [subjectId, setSubjectId] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const [filterOpen, setFilterOpen] = useState(false);
    const projectsPerPage = 9;

    const projectsData = Array.isArray(projects) ? projects : (projects?.data || []);
    const allCourses = courses.length ? courses : [...(prog || []), ...(graph || []), ...(ict || [])];

    const siteName = 'YHA ACADEMY OF TECHNOLOGY';
    const title = `Student Projects - ${siteName}`;
    const description = 'Explore student projects from YHA ACADEMY OF TECHNOLOGY. Programming, Graphic Design, and ICT projects showcasing hands-on learning and practical skills.';
    const ogImage = '/image/logo/logo.png';

    const keywords = [
        'student projects Myanmar',
        'YHA ACADEMY OF TECHNOLOGY projects',
        'programming projects',
        'graphic design portfolio',
        'ICT projects Yangon',
        'Data Science projects',
        'AI projects',
        'Machine Learning projects',
        'Mobile Development projects',
        'Flutter projects',
        'Dart projects',
        'React projects',
        'Vue projects',
        'Laravel projects',
        'PHP projects',
        'JavaScript projects',
        'MERN Stack projects',
        'Web Development projects',
        'MySQL projects',
        'MongoDB projects'
    ].join(', ');

    const getCourseCount = (id) => {
        const key = String(id);
        if (courseCounts && (courseCounts[key] !== undefined || courseCounts[id] !== undefined)) {
            return courseCounts[key] ?? courseCounts[id] ?? 0;
        }
        return projectsData.filter(
            (p) => String(p.course_id) === key || String(p.course?.id) === key
        ).length;
    };

    const getSubjectCount = (id) => {
        const allowedCourseIds = subjectCourseMap[id] || subjectCourseMap[String(id)] || [];
        return allowedCourseIds.reduce(
            (total, courseId) => total + Number(getCourseCount(courseId) || 0),
            0
        );
    };

    const filteredProjects = useMemo(() => {
        return projectsData.filter((project) => {
            // ===== Search =====
            if (search.trim()) {
                const q = search.toLowerCase();
                const title = (project.title || '').toLowerCase();
                const desc = (project.desc || project.description || '').toLowerCase();
                const courseName = (project.course?.name || '').toLowerCase();
                const student = (
                    project.student?.name ||
                    ''
                ).toLowerCase();
                const tech = (project.tech_stack || project.technologies || '').toLowerCase();

                if (
                    !title.includes(q) &&
                    !desc.includes(q) &&
                    !courseName.includes(q) &&
                    !student.includes(q) &&
                    !tech.includes(q)
                ) {
                    return false;
                }
            }

            // ===== Course filter =====
            if (courseId !== 'all') {
                const pCourseId = String(project.course_id || project.course?.id || '');
                if (pCourseId !== String(courseId)) {
                    return false;
                }
            }

            // ===== Subject filter  =====
            if (subjectId !== 'all') {
                const allowedCourseIds = subjectCourseMap[subjectId] || subjectCourseMap[String(subjectId)] || [];

                const allowed = allowedCourseIds.map(String);
                const pCourseId = String(project.course_id || project.course?.id || '');

                if (!allowed.includes(pCourseId)) {
                    return false;
                }
            }

            return true;
        });
    }, [projectsData, search, courseId, subjectId, subjectCourseMap]);

    const totalPages = Math.ceil(filteredProjects.length / projectsPerPage) || 1;
    const indexOfLast = currentPage * projectsPerPage;
    const indexOfFirst = indexOfLast - projectsPerPage;
    const currentProjects = filteredProjects.slice(indexOfFirst, indexOfLast);

    const handleCourse = (id) => {
        setCourseId(id);
        setCurrentPage(1);
    };

    const handleSubject = (id) => {
        setSubjectId(id);
        setCurrentPage(1);
    };

    const clearAll = () => {
        setSearch('');
        setCourseId('all');
        setSubjectId('all');
        setCurrentPage(1);
    };

    const hasFilters = search || courseId !== 'all' || subjectId !== 'all';

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

            <section className="ps-section">
                <div className="ps-container">
                    {/* Search */}
                    <div className="ps-search-bar">
                        <div className="ps-search-input-wrap">
                            <i className="fas fa-search"></i>
                            <input
                                type="text"
                                placeholder="Search projects by title, description, course..."
                                value={search}
                                onChange={(e) => {
                                    setSearch(e.target.value);
                                    setCurrentPage(1);
                                }}
                            />
                            {search && (
                                <button
                                    type="button"
                                    className="ps-search-clear"
                                    onClick={() => setSearch('')}
                                >
                                    <i className="fas fa-times"></i>
                                </button>
                            )}
                        </div>
                        <button type="button" className="ps-search-btn">
                            Search
                        </button>
                    </div>

                    {/* Mobile filter toggle */}
                    <button
                        type="button"
                        className="ps-filter-toggle"
                        onClick={() => setFilterOpen(!filterOpen)}
                    >
                        <i className="fas fa-sliders-h"></i>
                        Filter & Refine
                        {hasFilters && <span className="ps-filter-dot"></span>}
                        <i className={`fas fa-chevron-${filterOpen ? 'up' : 'down'}`}></i>
                    </button>

                    <div className="ps-layout">
                        {/* Sidebar */}
                        <aside className={`ps-sidebar ${filterOpen ? 'open' : ''}`}>
                            <div className="ps-sidebar-header">
                                <h3>
                                    <i className="fas fa-sliders-h"></i> Filter & Refine
                                </h3>
                                {hasFilters && (
                                    <button
                                        type="button"
                                        className="ps-clear-all"
                                        onClick={clearAll}
                                    >
                                        Clear all
                                    </button>
                                )}
                            </div>

                            {/* Course / Class */}
                            <div className="ps-filter-group">
                                <h4 className="ps-filter-title">Course / Class</h4>
                                <ul className="ps-filter-list ps-course-list">
                                    <li>
                                        <button
                                            type="button"
                                            className={courseId === 'all' ? 'active' : ''}
                                            onClick={() => handleCourse('all')}
                                        >
                                            All Courses
                                            <span>{projectsData.length}</span>
                                        </button>
                                    </li>
                                    {allCourses.map((c) => (
                                        <li key={c.id}>
                                            <button
                                                type="button"
                                                className={
                                                    String(courseId) === String(c.id)
                                                        ? 'active'
                                                        : ''
                                                }
                                                onClick={() => handleCourse(c.id)}
                                            >
                                                {c.name || c.title}
                                                <span>{getCourseCount(c.id)}</span>
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            {/* Subject / Tech */}
                            <div className="ps-filter-group">
                                <h4 className="ps-filter-title">Subject / Tech</h4>
                                <ul className="ps-filter-list">
                                    <li>
                                        <button
                                            type="button"
                                            className={subjectId === 'all' ? 'active' : ''}
                                            onClick={() => handleSubject('all')}
                                        >
                                            All Subjects
                                            <span>{projectsData.length}</span>
                                        </button>
                                    </li>
                                    {subjects.map((sub) => (
                                        <li key={sub.id}>
                                            <button
                                                type="button"
                                                className={
                                                    String(subjectId) === String(sub.id)
                                                        ? 'active'
                                                        : ''
                                                }
                                                onClick={() => handleSubject(sub.id)}
                                            >
                                                {sub.name}
                                                <span>{getSubjectCount(sub.id)}</span>
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </aside>

                        {/* Main content */}
                        <div className="ps-main">
                            <div className="ps-results-header">
                                <div className="ps-results-info">
                                    <strong>{filteredProjects.length}</strong> projects
                                    {hasFilters && (
                                        <span className="ps-active-filters">
                                            {search && (
                                                <span className="ps-tag">
                                                    “{search}”
                                                    <button
                                                        type="button"
                                                        onClick={() => setSearch('')}
                                                    >
                                                        ×
                                                    </button>
                                                </span>
                                            )}
                                            {courseId !== 'all' && (
                                                <span className="ps-tag">
                                                    Course
                                                    <button
                                                        type="button"
                                                        onClick={() => setCourseId('all')}
                                                    >
                                                        ×
                                                    </button>
                                                </span>
                                            )}
                                            {subjectId !== 'all' && (
                                                <span className="ps-tag">
                                                    Subject
                                                    <button
                                                        type="button"
                                                        onClick={() => setSubjectId('all')}
                                                    >
                                                        ×
                                                    </button>
                                                </span>
                                            )}
                                        </span>
                                    )}
                                </div>
                            </div>

                            <div className="ps-list">
                                {currentProjects.length > 0 ? (
                                    currentProjects.map((project) => (
                                        <div key={project.id} className="ps-row-card">
                                            <div className="ps-row-image">
                                                <img
                                                    src={
                                                        project.image
                                                            ? `/storage/${project.image}`
                                                            : '/image/no-image.jpg'
                                                    }
                                                    alt={project.title || 'Project'}
                                                    onError={(e) => {
                                                        e.currentTarget.onerror = null;
                                                        e.currentTarget.src = '/image/no-image.jpg';
                                                    }}
                                                />
                                                <span className="ps-row-badge">
                                                    {project.course?.name || 'Student Project'}
                                                </span>
                                            </div>

                                            <div className="ps-row-center">
                                                <h3 className="ps-row-title">
                                                    <Link
                                                        href={`/yha/project/detail/${project.id}`}
                                                    >
                                                        {project.title || 'Project Title'}
                                                    </Link>
                                                </h3>
                                                 <p className="ps-row-student">
                                                     <i className="fas fa-user"></i>
                                                     {project.student?.name || 'Unassigned'}
                                                 </p>
                                                <p className="ps-row-desc">
                                                    {project.desc ||
                                                        project.description ||
                                                        'No description available.'}
                                                </p>
                                                <div className="ps-row-tech">
                                                    {(project.tech_stack || project.technologies || '')
                                                        .toString()
                                                        .split(',')
                                                        .filter(Boolean)
                                                        .map((tech, i) => (
                                                            <span
                                                                key={`tech-${i}`}
                                                                className="ps-tech-tag"
                                                            >
                                                                {tech.trim()}
                                                            </span>
                                                        ))}
                                                    {!(project.tech_stack || project.technologies) &&
                                                        (courseSubjects[project.course_id] || courseSubjects[String(project.course?.id)]) &&
                                                        [...new Set((courseSubjects[project.course_id] || courseSubjects[String(project.course?.id)] || []))].map((subject, i) => (
                                                            <span
                                                                key={`subj-${i}`}
                                                                className="ps-tech-tag"
                                                            >
                                                                {subject}
                                                            </span>
                                                        ))
                                                    }
                                                </div>
                                            </div>

                                            <div className="ps-row-avatar">
                                                <div className="ps-avatar-img-wrap">
                                                    <img
                                                        src={
                                                            project.student?.image
                                                                ? `/storage/${project.student.image}`
                                                                : '/image/logo/student-placeholder.svg'
                                                        }
                                                        alt={project.student?.name || 'Student avatar'}
                                                        onError={(e) => {
                                                            e.target.src =
                                                                '/image/logo/student-placeholder.svg';
                                                        }}
                                                    />
                                                </div>
                                                <p className="ps-avatar-uni">
                                                    {project.student?.education ||
                                                        'YHA ACADEMY OF TECHNOLOGY'}
                                                </p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="ps-empty">
                                        <i className="fas fa-folder-open"></i>
                                        <h3>No projects found</h3>
                                        <p>Try changing filters or search keywords.</p>
                                        <button
                                            type="button"
                                            className="ps-clear-all"
                                            onClick={clearAll}
                                        >
                                            Clear all filters
                                        </button>
                                    </div>
                                )}
                            </div>

                            {totalPages > 1 && (
                                <div className="ps-pagination">
                                    <button
                                        type="button"
                                        disabled={currentPage === 1}
                                        onClick={() => setCurrentPage(currentPage - 1)}
                                    >
                                        <i className="fas fa-chevron-left"></i>
                                    </button>
                                    {[...Array(totalPages)].map((_, i) => (
                                        <button
                                            type="button"
                                            key={i + 1}
                                            className={
                                                currentPage === i + 1 ? 'active' : ''
                                            }
                                            onClick={() => setCurrentPage(i + 1)}
                                        >
                                            {i + 1}
                                        </button>
                                    ))}
                                    <button
                                        type="button"
                                        disabled={currentPage === totalPages}
                                        onClick={() => setCurrentPage(currentPage + 1)}
                                    >
                                        <i className="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            )}

                            {filteredProjects.length > 0 && (
                                <p className="ps-page-info">
                                    Showing {indexOfFirst + 1}–
                                    {Math.min(indexOfLast, filteredProjects.length)} of{' '}
                                    {filteredProjects.length}
                                </p>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            <Footer address={address} />
        </div>
    </>
    );
}
