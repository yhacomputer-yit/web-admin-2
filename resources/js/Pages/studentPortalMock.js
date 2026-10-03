/**
 * Placeholder payloads for the student portal UI.
 *
 * The portal pages are built against these shapes while the real tables do not
 * exist yet. When the backend lands, delete this file and have the controller
 * return the same keys so the pages need no changes.
 */

// STATUS drives the badge, whether a score is shown, and whether the row offers
// a Submit button. Keys mirror the lowercase snake_case the API will use.
export const ASSIGNMENT_STATUS = {
    PENDING: "pending",
    SUBMITTED: "submitted",
    LATE: "late",
    GRADED: "graded",
};

export const MOCK_ASSIGNMENTS = [
    {
        id: 1,
        title: "Database Normalization Exercise",
        course_name: "Database Design",
        deadline: "2026-10-14",
        status: ASSIGNMENT_STATUS.PENDING,
        score: null,
    },
    {
        id: 2,
        title: "Responsive Layout with CSS Grid",
        course_name: "Web Frontend",
        deadline: "2026-10-08",
        status: ASSIGNMENT_STATUS.SUBMITTED,
        score: null,
    },
    {
        id: 3,
        title: "TCP vs UDP Analysis Report",
        course_name: "Networking Fundamentals",
        deadline: "2026-09-20",
        status: ASSIGNMENT_STATUS.LATE,
        score: null,
    },
    {
        id: 4,
        title: "Binary Search Tree Implementation",
        course_name: "Data Structures",
        deadline: "2026-09-05",
        status: ASSIGNMENT_STATUS.GRADED,
        score: 88,
    },
    {
        id: 5,
        title: "Unit Testing Best Practices",
        course_name: "Software Engineering",
        deadline: "2026-08-28",
        status: ASSIGNMENT_STATUS.GRADED,
        score: 74,
    },
];

// One entry per material group rendered by the course detail page. The videos
// and links intentionally have no file_size, to exercise the optional path.
export const MOCK_MATERIALS = [
    {
        type: "book",
        title: "Fundamentals of Database Systems",
        description: "7th edition companion text. Chapters 1-4 are covered this term.",
        meta: "PDF Â· 12.4 MB",
        file_size: "12.4 MB",
        action_label: "Download",
        action: "#",
    },
    {
        type: "book",
        title: "Learning SQL, 3rd Edition",
        description: "Practical SQL drills for the normalization assignment.",
        meta: "PDF Â· 6.8 MB",
        file_size: "6.8 MB",
        action_label: "Download",
        action: "#",
    },
    {
        type: "video",
        title: "Normalization Step by Step",
        description: "Recorded lecture, 52 minutes, including the worked exercises.",
        meta: "Video Â· 52 min",
        file_size: null,
        action_label: "Watch",
        action: "#",
    },
    {
        type: "video",
        title: "Building a REST API with Laravel",
        description: "Walkthrough of routing, validation and form requests.",
        meta: "Video Â· 38 min",
        file_size: null,
        action_label: "Watch",
        action: "#",
    },
    {
        type: "link",
        title: "Laravel Documentation",
        description: "Official reference for the framework used in the backend module.",
        meta: "YouTube Â· Playlist",
        file_size: null,
        action_label: "Open",
        action: "#",
    },
    {
        type: "link",
        title: "SQLBolt Interactive Lessons",
        description: "Short browser lessons for practising SELECT and JOINs.",
        meta: "YouTube Â· Playlist",
        file_size: null,
        action_label: "Open",
        action: "#",
    },
    {
        type: "resource",
        title: "Semester Project Starter Pack",
        description: "Scaffold, config samples and the submission checklist.",
        meta: "ZIP Â· 3.1 MB",
        file_size: "3.1 MB",
        action_label: "Download",
        action: "#",
    },
    {
        type: "resource",
        title: "ER Diagram Templates",
        description: "Draw.io template for the entity relationship diagram task.",
        meta: "ZIP · 240 KB",
        file_size: "240 KB",
        action_label: "Download",
        action: "#",
    },
];

/* --------------------------------------------------------------------------
   Course -> subject -> material
   --------------------------------------------------------------------------
   The columns drill courses -> subjects, and a subject expands in place to list
   its files. The placeholder tree mirrors the real shape: a course owns subjects
   (subject_detail), and each file of a subject is a material row of its own. See
   App\Models\Material -- the table is keyed by the file, which is why a subject
   can hold as many books, recordings and archives as it needs.

   `file` is null everywhere on purpose. The viewer falls back to a placeholder
   surface while there is nothing to stream, and switches to a real <iframe> /
   <video> the moment a link arrives from the backend.
   -------------------------------------------------------------------------- */

const book = (key, title, description, pages, size) => ({
    key,
    type: "book",
    title,
    description,
    meta: `PDF · ${size}`,
    pages,
    size,
    file: null,
});

const video = (key, title, description, duration) => ({
    key,
    type: "video",
    title,
    description,
    meta: `Video · ${duration}`,
    duration,
    file: null,
});

/* A zip is a bundle of the sources, so it never opens in the viewer: it is
   download only. `file` is null for the same reason as the other two. */
const zip = (key, title, description, size) => ({
    key,
    type: "zip",
    title,
    description,
    meta: `ZIP · ${size}`,
    size,
    file: null,
});

/** Every placeholder subject gets the same shape: two books, two videos, one zip. */
const subject = (key, name, teacher, materials) => ({
    key,
    name,
    teacher,
    materials,
});

// Materials of one course, reused across its subjects so the tree stays short to
// read in the source while each subject still lists its own files.
const MOBILE = {
    android: [
        book("mob-and-b1", "Lorem Ipsum Android Handbook", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.", 312, "9.6 MB"),
        book("mob-and-b2", "Ipsum Dolor Layout Reference", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore et dolore.", 148, "4.1 MB"),
        video("mob-and-v1", "Dolor Sit Amet: Activity Lifecycle", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, recorded on 12 Sep.", "47:12"),
        video("mob-and-v2", "Consectetur Adipiscing: Intents", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore.", "36:40"),
        zip("mob-and-z1", "Android Fundamentals Source Bundle", "Lorem ipsum dolor sit amet, the slides, the starter project and the exercise files.", "18.4 MB"),
    ],
    native: [
        book("mob-nat-b1", "Lorem Ipsum React Native Notes", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.", 226, "7.2 MB"),
        book("mob-nat-b2", "Ipsum Dolor Cross-Platform Guide", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore et dolore.", 194, "5.4 MB"),
        video("mob-nat-v1", "Tempor Incididunt: Component Basics", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, recorded on 10 Sep.", "42:05"),
        video("mob-nat-v2", "Ut Labore: Publishing Builds", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod.", "29:58"),
        zip("mob-nat-z1", "Cross-Platform Basics Source Bundle", "Lorem ipsum dolor sit amet, the slides, the starter project and the exercise files.", "15.1 MB"),
    ],
};

const FRONTEND = {
    html: [
        book("fr-html-b1", "Lorem Ipsum HTML and CSS Reference", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.", 402, "11.8 MB"),
        book("fr-html-b2", "Ipsum Dolor Typography Handbook", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore et dolore.", 168, "3.9 MB"),
        video("fr-html-v1", "Dolor Sit Amet: Semantic Layouts", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, recorded on 28 Aug.", "51:24"),
        video("fr-html-v2", "Consectetur Adipiscing: Flexbox", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore.", "38:47"),
        zip("fr-html-z1", "HTML and CSS Layout Source Bundle", "Lorem ipsum dolor sit amet, the starter files and the exercise assets.", "12.7 MB"),
    ],
    scripts: [
        book("fr-js-b1", "Lorem Ipsum JavaScript Handbook", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.", 356, "10.1 MB"),
        book("fr-js-b2", "Ipsum Dolor DOM Reference", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore et dolore.", 122, "2.8 MB"),
        video("fr-js-v1", "Tempor Incididunt: Events and Delegation", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, recorded on 24 Aug.", "44:16"),
        video("fr-js-v2", "Ut Labore: Asynchronous Patterns", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod.", "33:52"),
        zip("fr-js-z1", "JavaScript Essentials Source Bundle", "Lorem ipsum dolor sit amet, the starter files and the exercise assets.", "10.9 MB"),
    ],
};

const DATABASE = {
    modeling: [
        book("db-mod-b1", "Lorem Ipsum Database Systems", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.", 512, "12.4 MB"),
        book("db-mod-b2", "Ipsum Dolor Entity Design Notes", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore et dolore.", 208, "5.6 MB"),
        video("db-mod-v1", "Dolor Sit Amet: Normalization Steps", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, recorded on 05 Aug.", "52:18"),
        video("db-mod-v2", "Consectetur Adipiscing: Keys and Indexes", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore.", "41:33"),
        zip("db-mod-z1", "Relational Modeling Source Bundle", "Lorem ipsum dolor sit amet, the schema files and the exercise scripts.", "9.3 MB"),
    ],
    queries: [
        book("db-qry-b1", "Lorem Ipsum SQL Practice Book", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.", 288, "6.8 MB"),
        book("db-qry-b2", "Ipsum Dolor Query Tuning Guide", "Lorem ipsum dolor sit amet, sed do eiusmod tempor incididunt ut labore et dolore.", 164, "3.2 MB"),
        video("db-qry-v1", "Tempor Incididunt: Joins Workshop", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, recorded on 30 Jul.", "46:07"),
        video("db-qry-v2", "Ut Labore: Stored Procedures", "Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod.", "31:44"),
        zip("db-qry-z1", "Query Writing Source Bundle", "Lorem ipsum dolor sit amet, the practice database and the exercise scripts.", "7.6 MB"),
    ],
};

export const MOCK_COURSE_LIBRARY = [
    {
        id: "mock-mobile",
        name: "Flutter and Dart",
        category: "Mobile Development",
        section: "Section A",
        section_time: "08:00 - 10:00",
        enroll_date: "2026-09-14",
        subjects: [
            subject("mob-android", "Android Fundamentals", "Dolor Sit Amet", MOBILE.android),
            subject("mob-native", "Cross-Platform Basics", "Ipsum Dolor", MOBILE.native),
        ],
    },
    {
        id: "mock-frontend",
        name: "Web Frontend Fundamentals",
        category: "Web Development",
        section: "Section B",
        section_time: "10:15 - 12:15",
        enroll_date: "2026-09-02",
        subjects: [
            subject("fr-html", "HTML and CSS Layout", "Consectetur Adipiscing", FRONTEND.html),
            subject("fr-js", "JavaScript Essentials", "Tempor Incididunt", FRONTEND.scripts),
        ],
    },
    {
        id: "mock-database",
        name: "Database Design and SQL",
        category: "Backend Development",
        section: "Section A",
        section_time: "13:30 - 15:30",
        enroll_date: "2026-08-21",
        subjects: [
            subject("db-modeling", "Relational Modeling", "Ut Labore", DATABASE.modeling),
            subject("db-queries", "Query Writing", "Eiusmod Tempor", DATABASE.queries),
        ],
    },
];
