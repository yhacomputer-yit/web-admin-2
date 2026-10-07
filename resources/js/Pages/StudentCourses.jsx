import { useEffect, useMemo, useRef, useState } from "react";
import { usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";


/* The three kinds of file a subject owns, in the order they are listed inside a
   subject's dropdown. `type` on a material is what matches a group, and the
   icon, the label and the empty copy all hang off the same entry. */
const GROUPS = [
    {
        key: "book",
        label: "PDF Books",
        icon: "fas fa-file-pdf",
        empty: "No PDF books shared yet.",
    },
    {
        key: "video",
        label: "Videos",
        icon: "fas fa-circle-play",
        empty: "No lecture videos shared yet.",
    },
    {
        key: "zip",
        label: "ZIP Folders",
        icon: "fas fa-file-zipper",
        empty: "No ZIP folders shared yet.",
    },
];

/* Two drill-down steps. The left column is the whole navigation: course, then
   subject, then the files of that subject, each one dropping out under the row
   above it. The right column is a preview surface and nothing else. On mobile
   the two columns become two screens, so this is the value that says which one
   is on screen; on desktop it also says whether a file is open, which is what
   lets the tree give its width to the preview. */
const STEP_BROWSE = 1;
const STEP_PREVIEW = 2;

// "08:00" + "10:00" -> "08:00 - 10:00", tolerating a half-populated pair
const timeRange = (start, end) => {
    if (!start && !end) return null;
    if (start && end) return `${String(start).slice(0, 5)} - ${String(end).slice(0, 5)}`;
    return String(start || end).slice(0, 5);
};

/* Whether a subject has anything to open at all. The counts themselves are not
   shown: a number beside each kind tells a student more about the row they are
   about to click than about the material itself, and the file list right below
   it is the answer. */
const hasAny = (materials = []) => materials.length > 0;

/* `meta` on a placeholder is a display string ("PDF · 12.4 MB"). The rows want
   the size on its own, so it is read back out when the payload does not carry
   one. A real row will pass `size` directly and this never runs. */
const sizeOf = (material) => {
    if (material.size) return material.size;
    const part = String(material.meta || "").split("\u00b7").pop().trim();
    return part || "Unknown";
};

// the one line of detail under a file name: a duration for a video, a size for
// everything else
const detailOf = (material) => (material.duration ? material.duration : sizeOf(material));

/* -------------------------------------------------------------------------- */
/* Shared bits
   -------------------------------------------------------------------------- */

function EmptyState({ icon, title, text, fill = false }) {
    return (
        <div className={`mc-empty ${fill ? "mc-empty-fill" : ""}`}>
            <span className="mc-empty-icon"><i className={icon}></i></span>
            <p className="mc-empty-title">{title}</p>
            {text && <p className="mc-empty-text">{text}</p>}
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Left column: courses -> subjects -> files, each level an accordion
   -------------------------------------------------------------------------- */

/* One file inside an open subject. The row is only a pointer: it never opens a
   viewer of its own, it hands the file to the preview column. */
function FileRow({ material, isActive, onSelect }) {
    return (
        <li>
            <button
                type="button"
                className={`mc-file ${isActive ? "is-active" : ""}`}
                onClick={onSelect}
                aria-current={isActive ? "true" : undefined}
                title={`Preview ${material.title}`}
            >
                <span className={`mc-file-icon mc-file-icon-${material.type}`}>
                    <i className={material.type === "book" ? "fas fa-file-pdf" : material.type === "video" ? "fas fa-circle-play" : "fas fa-file-zipper"}></i>
                </span>
                <span className="mc-file-body">
                    <span className="mc-file-name">{material.title}</span>
                    <span className="mc-file-meta">{detailOf(material)}</span>
                </span>
                <i className={`fas ${material.type === "video" ? "fa-play" : "fa-eye"} mc-file-go`}></i>
            </button>
        </li>
    );
}

/* One file type inside an open subject: a small head, then rows. No count on the
   head either -- the rows below it are the list. */
function FileGroup({ group, items, materialKey, onSelectMaterial }) {
    return (
        <div className="mc-files-group">
            <div className="mc-files-head">
                <span className={`mc-files-icon mc-files-icon-${group.key}`}><i className={group.icon}></i></span>
                <span className="mc-files-label">{group.label}</span>
            </div>

            {items.length === 0 ? (
                <p className="mc-files-none">{group.empty}</p>
            ) : (
                <ul className="mc-files-list">
                    {items.map((m) => (
                        <FileRow
                            key={m.key}
                            material={m}
                            isActive={m.key === materialKey}
                            onSelect={() => onSelectMaterial(m.key)}
                        />
                    ))}
                </ul>
            )}
        </div>
    );
}

function SubjectRow({ subject, index, isOpen, materialKey, onToggle, onSelectMaterial }) {
    const empty = !hasAny(subject.materials);
    const panelId = `mc-subject-panel-${subject.key}`;

    return (
        <li className={`mc-sub ${isOpen ? "is-open" : ""}`}>
            <button
                type="button"
                className={`mc-srow ${isOpen ? "is-active" : ""}`}
                onClick={onToggle}
                aria-expanded={isOpen}
                aria-controls={panelId}
            >
                <span className="mc-srow-num">{String(index + 1).padStart(2, "0")}</span>
                <span className="mc-srow-body">
                    <span className="mc-srow-name">{subject.name}</span>
                </span>
                {empty && <em className="mc-chip-none">Empty</em>}
                <i className="fas fa-chevron-down mc-srow-chevron"></i>
            </button>

            {/* the panel stays in the DOM so it can animate its own height, and
                its contents are hidden for real while collapsed so the file
                buttons leave the tab order (see .mc-sacc-inner in the CSS) */}
            <div className="mc-sacc" id={panelId} aria-hidden={!isOpen}>
                <div className="mc-sacc-inner">
                    {empty ? (
                        <p className="mc-files-none">
                            Nothing has been uploaded for {subject.name} yet.
                        </p>
                    ) : (
                        <div className="mc-files">
                            {GROUPS.map((g) => (
                                <FileGroup
                                    key={g.key}
                                    group={g}
                                    items={subject.materials.filter((m) => m.type === g.key)}
                                    materialKey={materialKey}
                                    onSelectMaterial={onSelectMaterial}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </li>
    );
}

/* The course row is a name and a chevron, nothing else. The category, the
   section, the thumbnail and the per-course counts are all dropped: a list of
   courses has to read as a menu, and every extra line turns it into a table the
   student has to scan instead of a list they can click. A subject row works the
   same way for the same reason. */
function CourseAccordion({ course, isOpen, subjectKey, materialKey, onToggle, onSelectSubject, onSelectMaterial }) {
    return (
        <li className={`mc-acc ${isOpen ? "is-open" : ""}`}>
            <button
                type="button"
                className={`mc-crow ${isOpen ? "is-active" : ""}`}
                onClick={onToggle}
                aria-expanded={isOpen}
            >
                <span className="mc-crow-name">{course.name}</span>
                <i className="fas fa-chevron-down mc-crow-chevron"></i>
            </button>

            <div className="mc-cacc" aria-hidden={!isOpen}>
                <div className="mc-cacc-inner">
                    {course.subjects.length === 0 ? (
                        <p className="mc-cacc-empty">
                            <i className="fas fa-circle-info"></i>
                            No subjects have been assigned to this course yet.
                        </p>
                    ) : (
                        <ul className="mc-sub-list">
                            {course.subjects.map((s, i) => (
                                <SubjectRow
                                    key={s.key}
                                    subject={s}
                                    index={i}
                                    isOpen={isOpen && s.key === subjectKey}
                                    materialKey={materialKey}
                                    onToggle={() => onSelectSubject(s.key)}
                                    onSelectMaterial={onSelectMaterial}
                                />
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </li>
    );
}

function CourseColumn({ courses, total, courseKey, subjectKey, materialKey, onSelectCourse, onSelectSubject, onSelectMaterial }) {
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

                {!courseKey && courses.length > 0 && (
                    <p className="mc-hint">
                        <i className="fas fa-circle-info"></i>
                        Open a course, then a subject, to see its PDFs, videos and ZIP folders.
                    </p>
                )}

                {/* this region, and only this region, scrolls in column one */}
                <div className="mc-body mc-body-inset">
                    <ul className="mc-list">
                        {courses.map((c) => (
                            <CourseAccordion
                                    key={c.key}
                                    course={c}
                                    isOpen={c.key === courseKey}
                                    subjectKey={subjectKey}
                                    materialKey={materialKey}
                                    onToggle={() => onSelectCourse(c.key)}
                                    onSelectSubject={onSelectSubject}
                                    onSelectMaterial={onSelectMaterial}
                                />
                        ))}
                    </ul>
                </div>
            </section>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Right column: the preview surface, and nothing else
   -------------------------------------------------------------------------- */

function PdfPlaceholder({ material }) {
    const lines = [96, 88, 92, 74, 90, 84, 95, 88, 91, 70, 86, 60];
    const lines2 = [93, 87, 78, 90, 85, 96, 82, 68];

    return (
        <div className="mc-pdf">
            <div className="mc-pdf-bar">
                <span className="mc-pdf-chip">PDF</span>
            </div>
            <div className="mc-pdf-page">
                <h3>{material.title}</h3>
                <p className="mc-pdf-lead">{material.description}</p>
                {lines.map((w, i) => <span className="mc-pdf-line" key={i} style={{ width: `${w}%` }}></span>)}
                <h4>Sed do eiusmod tempor</h4>
                {lines2.map((w, i) => <span className="mc-pdf-line" key={i} style={{ width: `${w}%` }}></span>)}
                <p className="mc-pdf-note">
                    <i className="fas fa-circle-info"></i>
                    Preview placeholder - the real PDF renders here once the file is uploaded.
                </p>
            </div>
        </div>
    );
}

function VideoPlaceholder({ material }) {
    return (
        <div className="mc-video">
            <div className="mc-video-stage">
                <span className="mc-video-play"><i className="fas fa-play"></i></span>
                {material.duration && <span className="mc-video-time">{material.duration}</span>}
                <span className="mc-video-label">Video preview</span>
            </div>
            <div className="mc-video-meta">
                <h3>{material.title}</h3>
                <p>{material.description}</p>
                <p className="mc-video-note">
                    <i className="fas fa-circle-info"></i>
                    Preview placeholder - the recording plays here once the file is uploaded.
                </p>
            </div>
        </div>
    );
}

/* Students may read a file on the portal but never take a copy of it, so there
   is no download button anywhere on this page: no header button and no link in
   the zip note. The viewer is the whole feature. A zip has nothing to stream, so
   its panel just says what it is.

   The head is the file name and nothing else. The course and the subject are
   already the two rows the student clicked in the tree on the left, and the type
   and the size are already on the row inside it, so repeating any of it here
   only makes the name harder to read. There is no Clear button either: the way
   out is the back arrow on a phone, Escape anywhere, and clicking the same file
   a second time, which is also how it was chosen. Fullscreen sits on the frame,
   where a reader's eye already is. */
function PreviewPanel({ material, onBack, canGoBack, paneRef, isFullscreen, onFullscreen }) {
    const isBook = material.type === "book";
    const isZip = material.type === "zip";

    return (
        <div className="mc-pane">
            {/* the whole card is the fullscreen element, so a document can take
                the entire screen without leaving the page layout behind */}
            <section className="mc-card mc-card-col mc-pvcard" ref={paneRef}>
                <header className="mc-pvbar">
                    {canGoBack && (
                        <button type="button" className="mc-back" onClick={onBack} aria-label="Back to courses">
                            <i className="fas fa-chevron-left"></i>
                        </button>
                    )}

                    <div className="mc-pvtitle">
                        <span className={`mc-pvicon mc-file-icon-${material.type}`}>
                            <i className={isBook ? "fas fa-file-pdf" : isZip ? "fas fa-file-zipper" : "fas fa-circle-play"}></i>
                        </span>
                        <h1 className="mc-pvname" title={material.title}>{material.title}</h1>
                    </div>
                </header>

                {/* the frame is its own scroll region, so a wheel gesture over a
                    document never reaches the tree on its left */}
                <div className={`mc-body mc-pvbody ${isZip ? "is-zip" : ""}`}>
                    {/* fullscreen sits on the frame rather than in the head above
                        it; a zip has nothing to frame, so it has nothing to go
                        fullscreen with */}
                    {!isZip && (
                        <button
                            type="button"
                            className="mc-expand"
                            onClick={onFullscreen}
                            title={isFullscreen ? "Exit fullscreen" : "Fullscreen"}
                            aria-label={isFullscreen ? "Exit fullscreen" : "Fullscreen"}
                        >
                            <i className={`fa-solid ${isFullscreen ? "fa-compress" : "fa-expand"}`}></i>
                        </button>
                    )}
                    {isZip ? (
                        <div className="mc-pvnote">
                            <span className="mc-pvnote-icon"><i className="fas fa-file-zipper"></i></span>
                            <h3>{material.title}</h3>
                            <p>{material.description}</p>
                            <p className="mc-pvnote-text">
                                <i className="fas fa-circle-info"></i>
                                A ZIP archive cannot be opened on the portal. Ask your teacher for the
                                files inside it if you need them.
                            </p>
                            <span className="mc-pvnote-meta">
                                <em><i className="fas fa-file-zipper"></i>ZIP archive</em>
                                <em><i className="fas fa-hard-drive"></i>{sizeOf(material)}</em>
                            </span>
                        </div>
                    ) : isBook ? (
                        material.file ? (
                            <iframe className="mc-frame" src={`${material.file}#view=FitH`} title={material.title} />
                        ) : (
                            <PdfPlaceholder material={material} />
                        )
                    ) : material.file ? (
                        <video className="mc-frame mc-video-el" src={material.file} controls preload="metadata" />
                    ) : (
                        <VideoPlaceholder material={material} />
                    )}
                </div>
            </section>
        </div>
    );
}

/* -------------------------------------------------------------------------- */
/* Page
   -------------------------------------------------------------------------- */
export default function StudentCourses({ enrollments, references }) {
    const { url } = usePage();

    const [courseKey, setCourseKey] = useState(null);
    const [subjectKey, setSubjectKey] = useState(null);
    const [materialKey, setMaterialKey] = useState(null);
    const [step, setStep] = useState(STEP_BROWSE);
    const [isFullscreen, setIsFullscreen] = useState(false);

    // the preview card is the fullscreen element, so the button has to hold a ref
    const paneRef = useRef(null);

    /* The subject tree is the real `reference` data: one course -> subject ->
       file, scoped by the controller to the courses this student is enrolled
       in. A course with no rows keeps an empty subject list, and the course row
       says so, which is honest -- a placeholder tree here would show a student
       files that do not exist. */
    const byCourse = useMemo(() => {
        const map = new Map();
        (references || []).forEach((course) => map.set(course.course_id, course));
        return map;
    }, [references]);

    const courses = useMemo(() => {
        if (!enrollments?.length) {
            return [];
        }

        return enrollments.map((e) => ({
            key: `e-${e.id}`,
            course_id: e.course_id,
            name: e.course_name || "Unknown course",
            image: e.course_image,
            category: e.course_type,
            section: e.section_name,
            section_time: timeRange(e.section_start, e.section_end),
            enroll_date: e.enroll_date,
            subjects: byCourse.get(e.course_id)?.subjects || [],
        }));
    }, [enrollments, byCourse]);

    /* All three keys start null: nothing is open until it is clicked, which is
       exactly what the preview column's empty state is telling the student. */
    const course = courses.find((c) => c.key === courseKey) || null;
    const subject = course?.subjects.find((s) => s.key === subjectKey) || null;
    const material = subject?.materials.find((m) => m.key === materialKey) || null;

    const selectCourse = (key) => {
        // clicking the open course closes it, so there is always a way back
        const open = key === courseKey;
        setCourseKey(open ? null : key);
        setSubjectKey(null);
        setMaterialKey(null);
        setStep(STEP_BROWSE);
    };

    const selectSubject = (key) => {
        // the subject is a dropdown too, so a second click closes it again
        const open = key === subjectKey;
        setSubjectKey(open ? null : key);
        setMaterialKey(null);
        setStep(STEP_BROWSE);
    };

    const selectMaterial = (key) => {
        if (key === materialKey) {
            // clicking the same file again clears the preview
            setMaterialKey(null);
            setStep(STEP_BROWSE);
            return;
        }
        setMaterialKey(key);
        setStep(STEP_PREVIEW);
    };

    // Escape clears the preview, the same as the Clear button
    useEffect(() => {
        if (!material) return;

        const onKey = (e) => { if (e.key === "Escape") { setMaterialKey(null); setStep(STEP_BROWSE); } };
        document.addEventListener("keydown", onKey);

        return () => document.removeEventListener("keydown", onKey);
    }, [material]);

    // Fullscreen is a real request, not a mock: the browser's own state is
    // tracked, so the label stays right when the student leaves with Esc.
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
            /* fullscreen denied or unsupported: the card stays as it is */
        }
    };

    if (courses.length === 0) {
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
                {/* two columns and no third: the whole course tree on the left,
                    the preview of one file on the right */}
                <div className="mc-grid" data-step={step}>
                    <div className="mc-col mc-col-tree">
                        <CourseColumn
                            courses={courses}
                            total={courses.length}
                            courseKey={courseKey}
                            subjectKey={subjectKey}
                            materialKey={materialKey}
                            onSelectCourse={selectCourse}
                            onSelectSubject={selectSubject}
                            onSelectMaterial={selectMaterial}
                        />
                    </div>

                    <div className="mc-col mc-col-preview">
                        {course && subject && material ? (
                            <PreviewPanel
                                material={material}
                                onBack={() => setStep(STEP_BROWSE)}
                                canGoBack={step === STEP_PREVIEW}
                                paneRef={paneRef}
                                isFullscreen={isFullscreen}
                                onFullscreen={toggleFullscreen}
                            />
                        ) : (
                            <div className="mc-pane">
                                <section className="mc-card mc-card-col">
                                    <EmptyState
                                        icon="fas fa-eye"
                                        title="Select a file to preview"
                                        text="Open a course, then a subject, and pick a PDF, video or ZIP folder to preview it here."
                                        fill
                                    />
                                </section>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </StudentLayout>
    );
}
