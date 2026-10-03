import { Link, usePage } from "@inertiajs/react";
import StudentLayout from "../Layouts/StudentLayout";


/* The reference table has three link columns, so the page has three groups. The
   old fourth group was for hand-entered YouTube links, which no column holds, and
   it only ever rendered the placeholder library. */
const GROUPS = [
    {
        key: "book",
        label: "Reference Books",
        hint: "PDF",
        icon: "fas fa-book",
        empty: "No reference books have been shared yet.",
    },
    {
        key: "video",
        label: "Videos",
        hint: "Recordings",
        icon: "fas fa-circle-play",
        empty: "No lecture videos have been shared yet.",
    },
    {
        key: "resource",
        label: "Zip / Resources",
        hint: "Downloads",
        icon: "fas fa-folder-open",
        empty: "No resource packs have been shared yet.",
    },
];

/* which group each reference column belongs to, and what its button says */
const GROUP_OF = { book: "book", video: "video", zip: "resource" };
const ACTION_OF = { book: "Open", video: "Watch", zip: "Download" };
const HINT_OF = { book: "PDF", video: "Video", zip: "ZIP" };

export default function StudentCourseDetail({ course, section, materials }) {
    const { url } = usePage();

    /* Real rows only. The payload is the reference table, so a course with no
       files has to read as empty rather than fall back to a placeholder list a
       student would mistake for real course material. */
    const list = (materials ?? [])
        .filter((m) => GROUP_OF[m.type])
        .map((m) => ({
            ...m,
            group: GROUP_OF[m.type],
            meta: [HINT_OF[m.type], m.size].filter(Boolean).join(" · "),
            action: m.file,
            action_label: ACTION_OF[m.type],
        }));

    const grouped = GROUPS.map((g) => ({
        ...g,
        items: list.filter((m) => m.group === g.key),
    }));

    const totalItems = grouped.reduce((sum, g) => sum + g.items.length, 0);

    return (
        <StudentLayout active="courses" title={course?.name || "Course"} key={url}>
            <div className="sa-page">
                <nav aria-label="Breadcrumb" className="sa-breadcrumb">
                    <Link href="/student-portal/courses">My Courses</Link>
                    <i className="fas fa-chevron-right"></i>
                    <span>{course?.name || "Course"}</span>
                </nav>

                <div className="stu-hero mb-3">
                    <div className="p-3 p-md-4">
                        <div className="row g-3 align-items-center">
                            {course?.image && (
                                <div className="col-12 col-sm-auto">
                                    <img
                                        src={`/storage/${course.image}`}
                                        onError={(e) => { e.currentTarget.onerror = null; e.currentTarget.style.display = "none"; }}
                                        alt={course.name}
                                        className="sa-course-img"
                                    />
                                </div>
                            )}
                            <div className="col-12 col-sm">
                                {course?.type && (
                                    <span className="badge text-bg-warning mb-2">{course.type}</span>
                                )}
                                <h1 className="stu-hero-name">{course?.name || "Course"}</h1>

                            </div>
                        </div>

                        <div className="row g-2 mt-1">
                            <div className="col-6 col-md-3">
                                <div className="stu-stat">
                                    <div className="stu-stat-label">Section</div>
                                    <div className="stu-stat-value">{section || "—"}</div>
                                </div>
                            </div>
                            <div className="col-6 col-md-3">
                                <div className="stu-stat">
                                    <div className="stu-stat-label">Materials</div>
                                    <div className="stu-stat-value">{totalItems}</div>
                                </div>
                            </div>
                            <div className="col-6 col-md-3">
                                <div className="stu-stat">
                                    <div className="stu-stat-label">Books</div>
                                    <div className="stu-stat-value">{grouped[0].items.length}</div>
                                </div>
                            </div>
                            <div className="col-6 col-md-3">
                                <div className="stu-stat">
                                    <div className="stu-stat-label">Videos</div>
                                    <div className="stu-stat-value">{grouped[1].items.length}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {totalItems === 0 ? (
                    <div className="stu-empty">
                        <div className="stu-empty-icon"><i className="fas fa-folder-open"></i></div>
                        <div className="stu-empty-title">No materials yet</div>
                        <p className="stu-empty-text mt-2 mb-0">
                            Learning materials for this course have not been uploaded yet.
                        </p>
                    </div>
                ) : (
                    <div className="row g-3">
                        {grouped.map((g) => (
                            <div className="col-12 col-lg-6" key={g.key}>
                                <section className="sa-card h-100">
                                    <div className="sa-card-head d-flex align-items-center justify-content-between">
                                        <span className="d-flex align-items-center gap-2">
                                            <i className={`sa-head-icon sa-type-${g.key}`}>
                                                <i className={g.icon}></i>
                                            </i>
                                            {g.label}
                                        </span>
                                        <span className="sa-head-count">{g.items.length}</span>
                                    </div>

                                    {g.items.length === 0 ? (
                                        <div className="sa-mat-empty">
                                            <i className="fas fa-inbox"></i>
                                            <span>{g.empty}</span>
                                        </div>
                                    ) : (
                                        <ul className="sa-mat-list">
                                            {g.items.map((m) => (
                                                <li className="sa-mat" key={m.key}>
                                                    <span className={`sa-type-icon sa-type-${g.key}`}>
                                                        <i className={g.icon}></i>
                                                    </span>
                                                    <div className="sa-mat-body">
                                                        <div className="sa-mat-title">{m.title}</div>
                                                        {m.description && (
                                                            <div className="sa-mat-desc">{m.description}</div>
                                                        )}
                                                        <div className="sa-mat-meta">
                                                            {m.meta && <span>{m.meta}</span>}
                                                        </div>
                                                    </div>
                                                    <a
                                                        href={m.action || "#"}
                                                        className={`stu-btn stu-btn-auto sa-mat-action sa-type-${g.key}`}
                                                        onClick={(e) => !m.action && e.preventDefault()}
                                                    >
                                                        <i className={`fa-solid ${g.key === "video" ? "fa-play" : "fa-download"} me-1`}></i>
                                                        {m.action_label || "Open"}
                                                    </a>
                                                </li>
                                            ))}
                                        </ul>
                                    )}
                                </section>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </StudentLayout>
    );
}
