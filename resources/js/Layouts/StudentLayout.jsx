import { useEffect, useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import '../../css/pages/student-attendance.css';

/**
 * Shared shell for every student portal page: a fixed navy sidebar on the
 * left, page content on the right.
 *
 * Two collapse modes, because the sidebar has to work on both a desktop monitor
 * and a phone:
 *  - desktop (>= lg): fixed in place, optionally reduced to an icon-only rail.
 *    The choice is remembered so it survives navigation and reloads.
 *  - mobile (< lg): off-canvas behind a hamburger and a backdrop.
 *
 * The active link is passed in as `active` rather than derived from the URL,
 * because two pages legitimately share the "courses" key (list and detail).
 *
 * `fill` hands the page's scrolling to the page itself: the shell stops
 * scrolling and the content box becomes exactly one viewport tall. Pages that
 * own their own scroll regions -- the three column course workspace -- pass it
 * so the document never scrolls behind their columns.
 */
const LINKS = [
    { key: "dashboard", label: "Dashboard", href: "/student-portal/dashboard", icon: "fas fa-gauge-high" },
    { key: "attendance", label: "My Attendance", href: "/student-portal/attendance", icon: "fas fa-calendar-check" },
    { key: "courses", label: "My Courses", href: "/student-portal/courses", icon: "fas fa-book-open" },
    { key: "assignments", label: "Assignment", href: "/student-portal/assignments", icon: "fas fa-file-lines" },
    { key: "exams", label: "Exam", href: "/student-portal/exam", icon: "fas fa-clipboard-list" },
];

const STORAGE_KEY = "yha.portal.sidebar.collapsed";

const logout = (e) => {
    e.preventDefault();
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
    router.post("/student-portal/logout", { _token: token }, {
        onFinish: () => { window.location.href = "/login"; },
    });
};

export default function StudentLayout({ active, title, status, fill, children }) {
    // start un-collapsed, then adopt the stored preference after mount so the
    // server-rendered markup and the first client render agree
    const [collapsed, setCollapsed] = useState(false);
    const [open, setOpen] = useState(false);

    useEffect(() => {
        try {
            setCollapsed(window.localStorage.getItem(STORAGE_KEY) === "1");
        } catch (e) {
            /* private mode: keep the default expanded rail */
        }
    }, []);

    const toggleCollapsed = () => {
        setCollapsed((prev) => {
            const next = !prev;
            try {
                window.localStorage.setItem(STORAGE_KEY, next ? "1" : "0");
            } catch (e) { /* ignore */ }
            return next;
        });
    };

    // close the drawer on Escape, and stop the page scrolling behind it
    useEffect(() => {
        if (!open) return;

        const onKey = (e) => { if (e.key === "Escape") setOpen(false); };
        document.addEventListener("keydown", onKey);
        document.body.classList.add("sl-no-scroll");

        return () => {
            document.removeEventListener("keydown", onKey);
            document.body.classList.remove("sl-no-scroll");
        };
    }, [open]);

    return (
        <div className={`sl-root ${collapsed ? "sl-collapsed" : ""} ${open ? "sl-open" : ""} ${fill ? "sl-fill" : ""}`}>
            <Head title={title} />

            <a href="#sl-content" className="sl-skip">Skip to content</a>

            <button
                type="button"
                className="sl-backdrop"
                aria-label="Close menu"
                tabIndex={open ? 0 : -1}
                onClick={() => setOpen(false)}
            />

            <aside className="sl-sidebar" aria-label="Student portal navigation">
                <div className="sl-brand">
                    <img src="/image/logo/logo.png" alt="" width="38" height="38" className="sl-brand-logo" />
                    <div className="sl-brand-text">
                        <div className="sl-brand-title">Student Portal</div>
                        <div className="sl-brand-sub">YHA Academy of Technology</div>
                    </div>
                </div>

                <nav className="sl-nav">
                    {LINKS.map((l) => (
                        <Link
                            key={l.key}
                            href={l.href}
                            className={`sl-link ${active === l.key ? "active" : ""}`}
                            aria-current={active === l.key ? "page" : undefined}
                            title={l.label}
                            onClick={() => setOpen(false)}
                        >
                            <i className={l.icon}></i>
                            <span className="sl-link-label">{l.label}</span>
                        </Link>
                    ))}
                </nav>

                <div className="sl-foot">
                    {/* {status && (
                        <span className={`sl-status ${status === "active" ? "is-active" : ""}`}>
                            <i className="fas fa-circle"></i>
                            <span className="sl-link-label">Account {status}</span>
                        </span>
                    )} */}



                    <button type="button" onClick={logout} className="sl-link sl-logout" title="Log out">
                        <i className="fas fa-right-from-bracket"></i>
                        <span className="sl-link-label">Logout</span>
                    </button>

                    <button
                        type="button"
                        onClick={toggleCollapsed}
                        className="sl-collapse"
                        aria-label={collapsed ? "Expand sidebar" : "Collapse sidebar"}
                        title={collapsed ? "Expand sidebar" : "Collapse sidebar"}
                    >
                        <i className={`fas ${collapsed ? "fa-angles-right" : "fa-angles-left"}`}></i>
                        <span className="sl-link-label">Collapse</span>
                    </button>
                </div>
            </aside>

            <div className="sl-main">
                {/* mobile top bar: hamburger + page title, since the sidebar is off-canvas */}
                <header className="sl-topbar">
                    <button
                        type="button"
                        className="sl-burger"
                        onClick={() => setOpen(true)}
                        aria-label="Open menu"
                        aria-expanded={open}
                    >
                        <i className="fas fa-bars"></i>
                    </button>
                    <div className="sl-topbar-title">{title}</div>
                </header>

                <main className="sl-content" id="sl-content">{children}</main>
            </div>
        </div>
    );
}
