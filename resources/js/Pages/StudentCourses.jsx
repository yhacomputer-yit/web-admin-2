import { useEffect, useMemo, useRef, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";
import { MOCK_COURSE_LIBRARY } from "./studentPortalMock";


const formatDate = (value) => {
    if (!value) return null;
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return d.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
};

// "08:00" + "10:00" -> "08:00 - 10:00", tolerating a half-populated pair
const timeRange = (start, end) => {
    if (!start && !end) return null;
    if (start && end) return `${String(start).slice(0, 5)} - ${String(end).slice(0, 5)}`;
    return String(start || end).slice(0, 5);
};

const courseImg = (src) => (src ? `/storage/${src}` : "/image/no-image.jpg");

const countTypes = (materials) => ({
    books: materials.filter((m) => m.type === "book").length,
    videos: materials.filter((m) => m.type === "video").length,
});

/* The two groups column three lists. The keys double as the per-type accent
   class, which is what the existing .sa-type-* rules already colour. */
const GROUPS = [
    {
        key: "book",
        label: "Reference Books",
        hint: "PDF",
        icon: "fas fa-book",
        action: "Download",
        actionIcon: "fa-solid fa-download",
        empty: "No reference books have been shared for this subject yet.",
    },
    {
        key: "video",
        label: "Videos",
        hint: "Recordings",
        icon: "fas fa-circle-play",
        action: "Watch",
        actionIcon: "fa-solid fa-play",
        empty: "No lecture videos have been shared for this subject yet.",
    },
];

/* Every level of the drill-down gets its own step, and the mobile layout walks
   them one at a time. On desktop the step only decides which column carries the
   focus accent -- all three columns are on screen regardless. */
const STEP_COURSES = 1;
const STEP_SUBJECTS = 2;
const STEP_MATERIALS = 3;
const STEP_VIEWER = 4;

/* -------------------------------------------------------------------------- */

function EmptyState({ icon, title, text, fill = false }) {
    return (
        <div className={`mc-empty ${fill ? "mc-empty-fill" : ""}`}>
            <span className="mc-empty-icon"><i className={icon}></i></span>
            <p className="mc-empty-title">{title}</p>
            {text && <p className="mc-empty-text">{text}</p>}
        </div>
    );
}

function StatTile({ icon, label, value }) {
    return (
        <div className="mc-stat">
            <span className="mc-stat-icon"><i className={icon}></i></span>
            <span className="mc-stat-body">
                <span className="mc-stat-label">{label}</span>
                <span className="mc-stat-value">{value}</span>
            </span>
        </div>
    );
}

function PathBar({ course, subject, material, canGoBack, onBack }) {
    const crumbs = [
        { label: "Courses" },
        course && { label: course.name },
        subject && { label: subject.name },
        material && { label: material.title },
    ].filter(Boolean);

    return (
        <nav className="mc-path" aria-label="Current selection">
            <button
                type="button"
                className="mc-path-back"
                onClick={onBack}
                disabled={!canGoBack}
                aria-label="Go back"
            >
                <i className="fas fa-chevron-left"></i>
            </button>
            <ol className="mc-path-list">
                {crumbs.map((c, i) => (
                    <li
                        key={`${c.label}-${i}`}
                        className={`mc-path-item ${i === crumbs.length - 1 ? "is-current" : ""}`}
                        title={c.label}
                    >
                        {i > 0 && <i className="fas fa-chevron-right mc-path-sep"></i>}
                        <span>{c.label}</span>
                    </li>
                ))}
            </ol>
        </nav>
    );
}

/* --------------------------------------------------------------------------
   Column 1 - courses
   -------------------------------------------------------------------------- */
function CourseColumn({ courses, total, query, onQuery, activeKey, onSelect }) {
    return (
        <div className="mc-pane">
            <section className="mc-card mc-card-col">
                <header className="mc-head">
                    <span className="mc-head-icon-wrap"><i className="fas fa-book-open"></i></span>
                    <span className="mc-head-text">
                        <span className="mc-head-title">My Courses</span>
                        <span className="mc-head-sub">{total} enrolled</span>
                    </span>
                </header>

                <div className="mc-search">
                    <i className="fas fa-magnifying-glass"></i>
                    <input
                        type="search"
                        className="mc-search-input"
                        placeholder="Search courses"
                        value={query}
                        onChange={(e) => onQuery(e.target.value)}
                        aria-label="Search courses"
                    />
                    {query && (
                        <button type="button" className="mc-search-clear" onClick={() => onQuery("")} aria-label="Clear search">
                            <i className="fas fa-xmark"></i>
                        </button>
                    )}
                </div>

                {/* this region, and only this region, scrolls in column one */}
                <div className="mc-body mc-body-inset">
                    {courses.length === 0 ? (
                        <EmptyState
                            icon="fas fa-magnifying-glass"
                            title="No match"
                            text={`Nothing matches â€œ${query}â€. Try a different word.`}
                        />
                    ) : (
                        <ul className="mc-list">
                            {courses.map((c) => {
                                const counts = countTypes(c.subjects.flatMap((s) => s.materials));
                                return (
                                    <li key={c.key}>
                                        <button
                                            type="button"
                                            className={`mc-row ${c.key === activeKey ? "is-active" : ""}`}
                                            onClick={() => onSelect(c.key)}
                                            aria-current={c.key === activeKey ? "true" : undefined}
                                        >
                                            <img
                                                src={courseImg(c.image)}
                                                onError={(ev) => { ev.currentTarget.onerror = null; ev.currentTarget.src = "/image/no-image.jpg"; }}
                                                alt=""
                                                className="mc-row-img"
                                            />
                                            <span className="mc-row-body">
                                                <span className="mc-row-title">{c.name}</span>
                                                <span className="mc-row-tag">
                                                    <i className="fas fa-tag"></i>
                                                    {c.category || "Uncategorised"}
                                                </span>
                                                <span className="mc-row-meta">
                                                    <span>
                                                        <i className="fas fa-clock"></i>
                                                        {c.section_time || c.section || "â€”"}
                                                    </span>
                                                    <span>
                                                        <i className="fas fa-calendar"></i>
                                                        {formatDate(c.enroll_date) || "â€”"}
                                                    </span>
                                                </span>
                                                <span className="mc-row-foot">
                                                    <em>{c.subjects.length} subjects</em>
                                                    <em>{counts.books} books</em>
                                                    <em>{counts.videos} videos</em>
                                                </span>
                                            </span>
                                        </button>
                                    </li>
                                );
                            })}
                        </ul>
                    )}
                </div>
            </section>
        </div>
    );
}

/* --------------------------------------------------------------------------
   Column 2 - subjects of the selected course
   -------------------------------------------------------------------------- */
function SubjectColumn({ course, activeKey, onSelect }) {
    if (!course) {
        return (
            <div className="mc-pane">
                <section className="mc-card mc-card-col">
                    <EmptyState
                        icon="fas fa-hand-pointer"
                        title="Pick a course"
                        text="The subjects of the course you pick in the first column appear here."
                        fill
                    />
                </section>
            </div>
        );
    }

    const reference = course.subjects.flatMap((s) => s.materials);
    const counts = countTypes(reference);

    return (
        <div className="mc-pane">


            <section className="mc-card mc-card-col">
                <header className="mc-subhead">
                    <h3 className="mc-subhead-title">Subjects</h3>
                    {course.course_id && (
                        <Link href={`/student-portal/courses/${course.course_id}`} className="mc-subhead-link">
                            <i className="fas fa-arrow-up-right-from-square"></i>
                            Course page
                        </Link>
                    )}
                </header>

                {/* only this region scrolls in column two; the course summary above
                    it stays put */}
                <div className="mc-body mc-body-inset">
                    {course.subjects.length === 0 ? (
                        <EmptyState
                            icon="fas fa-layer-group"
                            title="No subjects yet"
                            text="This course has no subjects assigned, so there is nothing to open."
                        />
                    ) : (
                        <ul className="mc-list mc-list-flush">
                            {course.subjects.map((s, i) => {
                                const c = countTypes(s.materials);
                                return (
                                    <li key={s.key}>
                                        <button
                                            type="button"
                                            className={`mc-subject ${s.key === activeKey ? "is-active" : ""}`}
                                            onClick={() => onSelect(s.key)}
                                            aria-current={s.key === activeKey ? "true" : undefined}
                                        >
                                            <span className="mc-subject-num">{String(i + 1).padStart(2, "0")}</span>
                                            <span className="mc-subject-body">
                                                <span className="mc-subject-name">{s.name}</span>
                                                {s.teacher && (
                                                    <span className="mc-subject-teacher">
                                                        <i className="fas fa-user-tie"></i>
                                                        {s.teacher}
                                                    </span>
                                                )}
                                                <span className="mc-subject-foot">
                                                    <em className="mc-tag-book"><i className="fas fa-book"></i>{c.books}</em>
                                                    <em className="mc-tag-video"><i className="fas fa-circle-play"></i>{c.videos}</em>
                                                </span>
                                            </span>
                                            <i className="fas fa-chevron-right mc-subject-arrow"></i>
                                        </button>
                                    </li>
                                );
                            })}
                        </ul>
                    )}
                </div>
            </section>
        </div>
    );
}

/* --------------------------------------------------------------------------
   Column 3a - the reference of the selected subject
   -------------------------------------------------------------------------- */
function ReferencePane({ subject, activeKey, onOpen }) {
    // No subject picked yet: column three stays deliberately empty rather than
    // guessing which subject the student wanted.
    if (!subject) {
        return (
            <>
                <section className="mc-card mc-card-col">
                    <EmptyState
                        icon="fas fa-hand-pointer"
                        title="Select a subject to view reference"
                        text="Choose a subject in the middle column and its reference books and videos appear here."
                        fill
                    />
                </section>
            </>
        );
    }

    const counts = countTypes(subject.materials);
    const groups = GROUPS.map((g) => ({
        ...g,
        items: subject.materials.filter((m) => m.type === g.key),
    }));
    const total = groups.reduce((n, g) => n + g.items.length, 0);

    if (total === 0) {
        return (
            <>
                <section className="mc-card mc-card-col">
                    <EmptyState
                        icon="fas fa-folder-open"
                        title="No reference yet"
                        text={`Nothing has been uploaded for ${subject.name} yet. Check back after the next class.`}
                        fill
                    />
                </section>
            </>
        );
    }

    return (
        <>
            <section className="mc-card mc-card-fixed">
                <header className="mc-head mc-head-detail mc-head-tight">
                    <span className="mc-chip mc-chip-quiet">Reference</span>
                    <h2 className="mc-head-h1">{subject.name}</h2>
                    <span className="mc-head-meta">
                        {subject.teacher && <span><i className="fas fa-user-tie"></i>{subject.teacher}</span>}
                        <span><i className="fas fa-book"></i>{counts.books} books</span>
                        <span><i className="fas fa-circle-play"></i>{counts.videos} videos</span>
                    </span>
                </header>
            </section>

            {/* the books and videos scroll together, inside column three only */}
            <div className="mc-body mc-body-inset">
                {groups.map((g) => (
                    <section className="mc-card" key={g.key}>
                        <header className="mc-subhead">
                            <h3 className="mc-subhead-title">
                                <span className={`sa-head-icon sa-type-${g.key}`}><i className={g.icon}></i></span>
                                {g.label}
                            </h3>
                            <span className="mc-badge">{g.items.length}</span>
                        </header>

                        {g.items.length === 0 ? (
                            <EmptyState icon="fas fa-inbox" title="Empty" text={g.empty} />
                        ) : (
                            <ul className="mc-ref-list">
                                {g.items.map((m) => (
                                    <li className={`mc-ref ${m.key === activeKey ? "is-active" : ""}`} key={m.key}>
                                        <button
                                            type="button"
                                            className="mc-ref-main"
                                            onClick={() => onOpen(m)}
                                            title={`Open ${m.title}`}
                                        >
                                            <span className={`sa-type-icon sa-type-${g.key}`}>
                                                <i className={g.icon}></i>
                                            </span>
                                            <span className="mc-ref-body">
                                                <span className="mc-ref-title">{m.title}</span>
                                                <span className="mc-ref-desc">{m.description}</span>
                                                <span className="mc-ref-meta">
                                                    <span><i className={`fas ${g.key === "book" ? "fa-file-pdf" : "fa-clock"}`}></i>{m.meta}</span>
                                                    {g.key === "book" && m.pages && <span>{m.pages} pages</span>}
                                                </span>
                                            </span>
                                        </button>

                                        <a
                                            href={m.file || "#"}
                                            className="mc-btn mc-btn-primary"
                                            title={m.file ? `${g.action} ${m.title}` : `${g.action} once the file is uploaded`}
                                            download={g.key === "book" ? "" : undefined}
                                            onClick={(e) => { if (!m.file) { e.preventDefault(); onOpen(m); } }}
                                        >
                                            <i className={g.actionIcon}></i>
                                            {g.action}
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </section>
                ))}
            </div>
        </>
    );
}

/* --------------------------------------------------------------------------
   Column 3b - the viewer
   -------------------------------------------------------------------------- */

// Stands in for a real PDF while `file` is null, so the pane still shows the
// shape of a document instead of collapsing into an empty box.
function PdfPlaceholder({ material }) {
    const lines = [96, 88, 92, 74, 90, 84, 95, 88, 91, 70, 86, 60];
    const lines2 = [93, 87, 78, 90, 85, 96, 82, 68];

    return (
        <div className="mc-pdf">
            <div className="mc-pdf-bar">
                <span className="mc-pdf-chip">PDF</span>
                <span className="mc-pdf-chip mc-pdf-chip-ghost">Fit width</span>
                <span className="mc-pdf-chip mc-pdf-chip-ghost">1 / {material.pages || 1}</span>
            </div>
            <div className="mc-pdf-page">
                <h3>{material.title}</h3>
                <p className="mc-pdf-lead">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
                {lines.map((w, i) => <span className="mc-pdf-line" key={i} style={{ width: `${w}%` }}></span>)}
                <h4>Sed do eiusmod tempor</h4>
                {lines2.map((w, i) => <span className="mc-pdf-line" key={i} style={{ width: `${w}%` }}></span>)}
                <p className="mc-pdf-note">
                    <i className="fas fa-circle-info"></i>
                    Preview placeholder â€” the real PDF renders here once the file is uploaded.
                </p>
            </div>
        </div>
    );
}

function VideoPlaceholder({ material }) {
    return (
        <div className="mc-video">
            <div className="mc-video-stage">
                <button type="button" className="mc-video-play" title="Preview placeholder">
                    <i className="fas fa-play"></i>
                </button>
                {material.duration && <span className="mc-video-time">{material.duration}</span>}
                <span className="mc-video-label">Video preview</span>
            </div>
            <div className="mc-video-meta">
                <h3>{material.title}</h3>
                <p>{material.description}</p>
            </div>
        </div>
    );
}

function Viewer({ material, onBack, isFullscreen, onFullscreen }) {
    const isBook = material.type === "book";

    return (
        <>
            <section className="mc-card mc-card-col">
                <header className="mc-viewer-bar">
                    <button type="button" className="mc-ghost-btn" onClick={onBack}>
                        <i className="fas fa-arrow-left"></i>
                        Reference
                    </button>
                    <span className={`sa-type-icon sa-type-${material.type}`}>
                        <i className={isBook ? "fas fa-file-pdf" : "fas fa-circle-play"}></i>
                    </span>
                    <span className="mc-viewer-text">
                        <span className="mc-viewer-name">{material.title}</span>
                        <span className="mc-viewer-meta">{material.meta}</span>
                    </span>
                    <span className="mc-viewer-actions">
                        <a
                            href={material.file || "#"}
                            className="mc-btn mc-btn-primary"
                            title={material.file ? "Download this file" : "Download once the file is uploaded"}
                            download={isBook ? "" : undefined}
                            onClick={(e) => { if (!material.file) e.preventDefault(); }}
                        >
                            <i className="fa-solid fa-download"></i>
                            Download
                        </a>
                        <button type="button" className="mc-btn mc-btn-solid" onClick={onFullscreen}>
                            <i className={`fa-solid ${isFullscreen ? "fa-compress" : "fa-expand"}`}></i>
                            {isFullscreen ? "Exit" : "Fullscreen"}
                        </button>
                    </span>
                </header>

                {/* the only scroll region in this column while a document is
                    open, so a wheel gesture over the PDF stays here */}
                <div className="mc-body mc-viewer-body">
                    {isBook ? (
                        material.file ? (
                            <iframe className="mc-frame" src={`${material.file}#view=FitH`} title={material.title} />
                        ) : (
                            <PdfPlaceholder material={material} />
                        )
                    ) : material.file ? (
                        <video className="mc-frame" src={material.file} controls preload="metadata" />
                    ) : (
                        <VideoPlaceholder material={material} />
                    )}
                </div>
            </section>
        </>
    );
}

/* --------------------------------------------------------------------------
   Page
   -------------------------------------------------------------------------- */
export default function StudentCourses({ enrollments }) {
    const { url } = usePage();

    const [query, setQuery] = useState("");
    const [courseKey, setCourseKey] = useState(null);
    const [subjectKey, setSubjectKey] = useState(null);
    const [materialKey, setMaterialKey] = useState(null);
    const [step, setStep] = useState(STEP_COURSES);
    const [back, setBack] = useState(false);
    const [isFullscreen, setIsFullscreen] = useState(false);

    const paneRef = useRef(null);

    // Real enrollments when they exist; the placeholder library otherwise, so the
    // workspace is never just an empty shell. The subject tree cycles through the
    // library because the subject/material placeholders have no rows yet.
    const courses = useMemo(() => {
        if (!enrollments?.length) {
            return MOCK_COURSE_LIBRARY.map((c) => ({ ...c, key: c.id, course_id: null }));
        }

        return enrollments.map((e, i) => ({
            key: `e-${e.id}`,
            course_id: e.course_id,
            name: e.course_name || "Unknown course",
            image: e.course_image,
            category: e.course_type,
            section: e.section_name,
            section_time: timeRange(e.section_start, e.section_end),
            enroll_date: e.enroll_date,
            subjects: MOCK_COURSE_LIBRARY[i % MOCK_COURSE_LIBRARY.length].subjects,
        }));
    }, [enrollments]);

    const visible = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return courses;
        return courses.filter((c) =>
            [c.name, c.category, c.section].some((v) => String(v || "").toLowerCase().includes(q)),
        );
    }, [courses, query]);

    // The first course of the full list, not of the filtered one, so searching
    // never empties the other two columns.
    const course = courses.find((c) => c.key === courseKey) || courses[0] || null;
    const subjects = course?.subjects || [];
    // Deliberately no fallback to subjects[0]: column three stays empty until the
    // student actually picks a subject.
    const subject = subjects.find((s) => s.key === subjectKey) || null;
    const materials = subject?.materials || [];
    const material = materials.find((m) => m.key === materialKey) || null;

    // One gesture always moves one level: forward selects the next column, back
    // returns to the previous one and drops whatever was open inside it.
    const selectCourse = (key) => {
        setCourseKey(key);
        setSubjectKey(null);
        setMaterialKey(null);
        setBack(false);
        setStep(STEP_SUBJECTS);
    };

    const selectSubject = (key) => {
        setSubjectKey(key);
        setMaterialKey(null);
        setBack(false);
        setStep(STEP_MATERIALS);
    };

    const openMaterial = (m) => {
        setMaterialKey(m.key);
        setBack(false);
        setStep(STEP_VIEWER);
    };

    const closeMaterial = () => {
        setMaterialKey(null);
        setBack(true);
        setStep(STEP_MATERIALS);
    };

    const goBack = () => {
        if (step === STEP_VIEWER) return closeMaterial();
        if (step === STEP_MATERIALS) {
            setBack(true);
            setStep(STEP_SUBJECTS);
            return;
        }
        if (step === STEP_SUBJECTS) {
            setBack(true);
            setStep(STEP_COURSES);
        }
    };

    // Fullscreen is a real request, not a mock: track the browser's own state so
    // the button label stays right when the user leaves with Esc.
    useEffect(() => {
        const onChange = () => setIsFullscreen(document.fullscreenElement === paneRef.current);
        document.addEventListener("fullscreenchange", onChange);
        return () => document.removeEventListener("fullscreenchange", onChange);
    }, []);

    const toggleFullscreen = async () => {
        const el = paneRef.current;
        if (!el) return;
        try {
            if (document.fullscreenElement) {
                await document.exitFullscreen();
            } else if (el.requestFullscreen) {
                await el.requestFullscreen();
            }
        } catch (e) {
            /* fullscreen denied or unsupported: the pane stays as it is */
        }
    };

    const focusColumn = step === STEP_COURSES ? 1 : step === STEP_SUBJECTS ? 2 : 3;

    if (!course) {
        return (
            <StudentLayout active="courses" title="My Courses" fill key={url}>
                <div className="mc-root">
                    <section className="mc-card mc-card-col">
                        <EmptyState
                            icon="fas fa-book-open"
                            title="No courses yet"
                            text="You are not enrolled in any course. Please contact the admin to get started."
                            fill
                        />
                    </section>
                </div>
            </StudentLayout>
        );
    }

    return (
        // fill: the shell hands the page its own scrolling, so the document never
        // scrolls and each column scrolls on its own
        <StudentLayout active="courses" title="My Courses" fill key={url}>
            <div className="mc-root">
                <PathBar
                    course={course}
                    subject={subject}
                    material={material}
                    canGoBack={step > STEP_COURSES}
                    onBack={goBack}
                />

                <div className="mc-grid" data-step={step}>
                    <div className={`mc-col mc-col-courses ${focusColumn === 1 ? "is-focused" : ""}`} key={`courses-${step}`}>
                        <CourseColumn
                            courses={visible}
                            total={courses.length}
                            query={query}
                            onQuery={setQuery}
                            activeKey={course.key}
                            onSelect={selectCourse}
                        />
                    </div>

                    <div className={`mc-col mc-col-subjects ${focusColumn === 2 ? "is-focused" : ""}`} key={`subjects-${step}`}>
                        <SubjectColumn course={course} activeKey={subject?.key} onSelect={selectSubject} />
                    </div>

                    <div className={`mc-col mc-col-reference ${focusColumn === 3 ? "is-focused" : ""}`} key={`reference-${step}`}>
                        <div className="mc-pane" ref={paneRef}>
                            <div
                                className={`mc-swap ${back ? "is-back" : ""}`}
                                key={material ? `viewer-${material.key}` : "reference-list"}
                            >
                                {material ? (
                                    <Viewer
                                        material={material}
                                        onBack={closeMaterial}
                                        isFullscreen={isFullscreen}
                                        onFullscreen={toggleFullscreen}
                                    />
                                ) : (
                                    <ReferencePane subject={subject} activeKey={materialKey} onOpen={openMaterial} />
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </StudentLayout>
    );
}
