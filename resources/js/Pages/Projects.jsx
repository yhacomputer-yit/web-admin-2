import { Link } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/project.css';

export default function Projects({ projects, prog, graph, ict, address }) {
    const [search, setSearch] = useState('');
    const [category, setCategory] = useState('all');
    const [courseId, setCourseId] = useState('all');
    const [language, setLanguage] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const [filterOpen, setFilterOpen] = useState(false);
    const projectsPerPage = 9;

    const projectsData = Array.isArray(projects) ? projects : (projects?.data || []);
    const allCourses = [...(prog || []), ...(graph || []), ...(ict || [])];

    const getProjectCategory = (project) => {
        const name = (project.course?.name || '').toLowerCase();
        if (name.includes('program') || name.includes('web') || name.includes('code') || name.includes('laravel') || name.includes('mern')) return 'programming';
        if (name.includes('graphic') || name.includes('design') || name.includes('ui') || name.includes('photoshop') || name.includes('ps ')) return 'graphic';
        if (name.includes('ict') || name.includes('network') || name.includes('office')) return 'ict';
        if (project.course_id === 1) return 'programming';
        if (project.course_id === 2) return 'graphic';
        if (project.course_id === 3) return 'ict';
        return 'other';
    };

    const getProjectLanguage = (project) => {
        const text = `${project.title || ''} ${project.desc || project.description || ''} ${project.tech_stack || ''}`.toLowerCase();
        if (text.includes('react') || text.includes('javascript') || text.includes('js') || text.includes('mern')) return 'javascript';
        if (text.includes('php') || text.includes('laravel')) return 'php';
        if (text.includes('python')) return 'python';
        if (text.includes('java')) return 'java';
        if (text.includes('html') || text.includes('css')) return 'html-css';
        return 'other';
    };

    const filteredProjects = useMemo(() => {
        return projectsData.filter((project) => {
            if (search.trim()) {
                const q = search.toLowerCase();
                const title = (project.title || '').toLowerCase();
                const desc = (project.desc || project.description || '').toLowerCase();
                const courseName = (project.course?.name || '').toLowerCase();
                const student = (project.student_name || project.student?.name || '').toLowerCase();
                if (!title.includes(q) && !desc.includes(q) && !courseName.includes(q) && !student.includes(q)) {
                    return false;
                }
            }
            if (category !== 'all' && getProjectCategory(project) !== category) return false;
            if (courseId !== 'all') {
                if (String(project.course_id) !== String(courseId) && String(project.course?.id) !== String(courseId)) {
                    return false;
                }
            }
            if (language !== 'all' && getProjectLanguage(project) !== language) return false;
            return true;
        });
    }, [projectsData, search, category, courseId, language]);

    const totalPages = Math.ceil(filteredProjects.length / projectsPerPage) || 1;
    const indexOfLast = currentPage * projectsPerPage;
    const indexOfFirst = indexOfLast - projectsPerPage;
    const currentProjects = filteredProjects.slice(indexOfFirst, indexOfLast);

    const handleCategory = (cat) => { setCategory(cat); setCourseId('all'); setCurrentPage(1); };
    const handleCourse = (id) => { setCourseId(id); setCurrentPage(1); };
    const handleLanguage = (lang) => { setLanguage(lang); setCurrentPage(1); };
    const clearAll = () => {
        setSearch(''); setCategory('all'); setCourseId('all'); setLanguage('all'); setCurrentPage(1);
    };
    const hasFilters = search || category !== 'all' || courseId !== 'all' || language !== 'all';

    const countByCat = (cat) =>
        projectsData.filter((p) => (cat === 'all' ? true : getProjectCategory(p) === cat)).length;

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
                                onChange={(e) => { setSearch(e.target.value); setCurrentPage(1); }}
                            />
                            {search && (
                                <button type="button" className="ps-search-clear" onClick={() => setSearch('')}>
                                    <i className="fas fa-times"></i>
                                </button>
                            )}
                        </div>
                        <button type="button" className="ps-search-btn">Search</button>
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
                                <h3><i className="fas fa-sliders-h"></i> Filter & Refine</h3>
                                {hasFilters && (
                                    <button type="button" className="ps-clear-all" onClick={clearAll}>Clear all</button>
                                )}
                            </div>

                            <div className="ps-filter-group">
                                <h4 className="ps-filter-title">Category</h4>
                                <ul className="ps-filter-list">
                                    {[
                                        { id: 'all', label: 'All Categories' },
                                        { id: 'programming', label: 'Programming' },
                                        { id: 'graphic', label: 'Graphic Design' },
                                        { id: 'ict', label: 'ICT' },
                                    ].map((c) => (
                                        <li key={c.id}>
                                            <button
                                                type="button"
                                                className={category === c.id ? 'active' : ''}
                                                onClick={() => handleCategory(c.id)}
                                            >
                                                {c.label}
                                                <span>{countByCat(c.id)}</span>
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </div>

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
                                        </button>
                                    </li>
                                    {allCourses.map((c) => (
                                        <li key={c.id}>
                                            <button
                                                type="button"
                                                className={String(courseId) === String(c.id) ? 'active' : ''}
                                                onClick={() => handleCourse(c.id)}
                                            >
                                                {c.name || c.title}
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            <div className="ps-filter-group">
                                <h4 className="ps-filter-title">Language / Tech</h4>
                                <ul className="ps-filter-list">
                                    {[
                                        { id: 'all', label: 'All' },
                                        { id: 'javascript', label: 'JavaScript / React' },
                                        { id: 'php', label: 'PHP / Laravel' },
                                        { id: 'python', label: 'Python' },
                                        { id: 'java', label: 'Java' },
                                        { id: 'html-css', label: 'HTML / CSS' },
                                    ].map((lang) => (
                                        <li key={lang.id}>
                                            <button
                                                type="button"
                                                className={language === lang.id ? 'active' : ''}
                                                onClick={() => handleLanguage(lang.id)}
                                            >
                                                {lang.label}
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </aside>

                        {/* Main */}
                        <div className="ps-main">
                            <div className="ps-results-header">
                                <div className="ps-results-info">
                                    <strong>{filteredProjects.length}</strong> projects
                                    {hasFilters && (
                                        <span className="ps-active-filters">
                                            {search && (
                                                <span className="ps-tag">
                                                    “{search}”
                                                    <button type="button" onClick={() => setSearch('')}>×</button>
                                                </span>
                                            )}
                                            {category !== 'all' && (
                                                <span className="ps-tag">
                                                    {category}
                                                    <button type="button" onClick={() => setCategory('all')}>×</button>
                                                </span>
                                            )}
                                            {courseId !== 'all' && (
                                                <span className="ps-tag">
                                                    Course
                                                    <button type="button" onClick={() => setCourseId('all')}>×</button>
                                                </span>
                                            )}
                                            {language !== 'all' && (
                                                <span className="ps-tag">
                                                    {language}
                                                    <button type="button" onClick={() => setLanguage('all')}>×</button>
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
                                                    src={project.image ? `/storage/${project.image}` : '/placeholder.jpg'}
                                                    alt={project.title || 'Project'}
                                                    onError={(e) => { e.target.src = '/placeholder.jpg'; }}
                                                />
                                                <span className="ps-row-badge">
                                                    {project.course?.name || 'Student Project'}
                                                </span>
                                            </div>

                                            <div className="ps-row-center">
                                                <h3 className="ps-row-title">
                                                    <Link href={`/yha/project/detail/${project.id}`}>
                                                        {project.title || 'Project Title'}
                                                    </Link>
                                                </h3>
                                                <p className="ps-row-student">
                                                    <i className="fas fa-user"></i>
                                                    {project.student_name || project.student?.name || project.author || 'Student'}
                                                </p>
                                                <p className="ps-row-desc">
                                                    {project.desc || project.description || 'No description available.'}
                                                </p>
                                                <div className="ps-row-tech">
                                                    {(project.tech_stack || project.technologies || 'HTML, CSS')
                                                        .toString()
                                                        .split(',')
                                                        .filter(Boolean)
                                                        .map((tech, i) => (
                                                            <span key={i} className="ps-tech-tag">{tech.trim()}</span>
                                                        ))}
                                                </div>
                                                <Link href={`/yha/project/detail/${project.id}`} className="ps-row-link">
                                                    Learn More <i className="fas fa-arrow-right"></i>
                                                </Link>
                                            </div>

                                            <div className="ps-row-avatar">
                                                <img
                                                    src={
                                                        project.student_photo
                                                            ? `/storage/${project.student_photo}`
                                                            : project.student?.photo
                                                                ? `/storage/${project.student.photo}`
                                                                : '/image/logo/logo.png'
                                                    }
                                                    alt="Student"
                                                    onError={(e) => { e.target.src = '/image/logo/logo.png'; }}
                                                />
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="ps-empty">
                                        <i className="fas fa-folder-open"></i>
                                        <h3>No projects found</h3>
                                        <p>Try changing filters or search keywords.</p>
                                        <button type="button" className="ps-clear-all" onClick={clearAll}>
                                            Clear all filters
                                        </button>
                                    </div>
                                )}
                            </div>

                            {totalPages > 1 && (
                                <div className="ps-pagination">
                                    <button type="button" disabled={currentPage === 1} onClick={() => setCurrentPage(currentPage - 1)}>
                                        <i className="fas fa-chevron-left"></i>
                                    </button>
                                    {[...Array(totalPages)].map((_, i) => (
                                        <button
                                            type="button"
                                            key={i + 1}
                                            className={currentPage === i + 1 ? 'active' : ''}
                                            onClick={() => setCurrentPage(i + 1)}
                                        >
                                            {i + 1}
                                        </button>
                                    ))}
                                    <button type="button" disabled={currentPage === totalPages} onClick={() => setCurrentPage(currentPage + 1)}>
                                        <i className="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            )}

                            {filteredProjects.length > 0 && (
                                <p className="ps-page-info">
                                    Showing {indexOfFirst + 1}–{Math.min(indexOfLast, filteredProjects.length)} of {filteredProjects.length}
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
