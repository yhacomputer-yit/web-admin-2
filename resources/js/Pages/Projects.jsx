import { Link } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/project.css';

export default function Projects({
    projects,
    prog,
    graph,
    ict,
    address,
    subjects = [],
    courseCounts = {},
    subjectCourseMap = {},
}) {
    const [search, setSearch] = useState('');
    const [courseId, setCourseId] = useState('all');
    const [subjectId, setSubjectId] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const [filterOpen, setFilterOpen] = useState(false);
    const projectsPerPage = 9;

    const projectsData = Array.isArray(projects) ? projects : (projects?.data || []);
    const allCourses = [...(prog || []), ...(graph || []), ...(ict || [])];

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
                    project.student_name ||
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
                                                            : '/placeholder.jpg'
                                                    }
                                                    alt={project.title || 'Project'}
                                                    onError={(e) => {
                                                        e.target.src = '/placeholder.jpg';
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
                                                    {project.student_name ||
                                                        project.student?.name ||
                                                        project.author ||
                                                        'Student'}
                                                </p>
                                                <p className="ps-row-desc">
                                                    {project.desc ||
                                                        project.description ||
                                                        'No description available.'}
                                                </p>
                                                <div className="ps-row-tech">
                                                    {(
                                                        project.tech_stack ||
                                                        project.technologies ||
                                                        'HTML, CSS'
                                                    )
                                                        .toString()
                                                        .split(',')
                                                        .filter(Boolean)
                                                        .map((tech, i) => (
                                                            <span
                                                                key={i}
                                                                className="ps-tech-tag"
                                                            >
                                                                {tech.trim()}
                                                            </span>
                                                        ))}
                                                </div>
                                            </div>

                                            <div className="ps-row-avatar">
                                                <div className="ps-avatar-img-wrap">
                                                    <img
                                                        src={
                                                            project.student_photo
                                                                ? `/storage/${project.student_photo}`
                                                                : project.student?.photo
                                                                ? `/storage/${project.student.photo}`
                                                                : '/image/logo/stud1.jpeg'
                                                        }
                                                        alt="Student"
                                                        onError={(e) => {
                                                            e.target.src =
                                                                '/image/logo/stud1.jpeg';
                                                        }}
                                                    />
                                                </div>
                                                <p className="ps-avatar-uni">
                                                    {project.university ||
                                                        project.student?.university ||
                                                        project.school ||
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
    );
}
