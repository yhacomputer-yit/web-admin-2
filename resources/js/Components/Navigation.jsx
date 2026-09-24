import { Link, usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";

export default function Navigation({ prog, graph, ict }) {
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);

    const { url } = usePage();

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 10);
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth >= 992) {
                setMobileMenuOpen(false);
                setOpenDropdown(null);
            }
        };
        window.addEventListener("resize", handleResize);
        return () => window.removeEventListener("resize", handleResize);
    }, []);

    useEffect(() => {
        const handleClickOutside = (e) => {
            if (mobileMenuOpen && !e.target.closest(".tech-university-navbar")) {
                setMobileMenuOpen(false);
                setOpenDropdown(null);
            }
        };
        document.addEventListener("click", handleClickOutside);
        return () => document.removeEventListener("click", handleClickOutside);
    }, [mobileMenuOpen]);

    const isActive = (path) => {
        if (path === "/") return url === "/" || url === "";
        return url.startsWith(path);
    };

    const handleDropdownToggle = (name) => {
        if (window.innerWidth < 992) {
            setOpenDropdown(openDropdown === name ? null : name);
        }
    };

    return (
        <nav className={`tech-university-navbar ${scrolled ? "scrolled" : ""}`}>
            {/* Logo */}
            <Link className="logo-container" href="/">
                <img
                    className="logo-img"
                    src="/image/logo/logo.png"
                    alt="YHA Logo"
                />
                <div className="logo-text">
                    <span>YHA ACADEMY</span>
                    <span>OF TECHNOLOGY</span>
                </div>
            </Link>

            {/* Hamburger */}
            <div
                className={`hamburger ${mobileMenuOpen ? "active" : ""}`}
                onClick={() => {
                    setMobileMenuOpen(!mobileMenuOpen);
                    setOpenDropdown(null);
                }}
            >
                <span></span>
                <span></span>
                <span></span>
            </div>

            {/* Menu */}
            <ul className={`nav-menu ${mobileMenuOpen ? "open" : ""}`}>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive("/") ? "active" : ""}`} href="/">
                        Home
                    </Link>
                </li>

                {/* Web Development */}
                <li className={`nav-item has-sub ${openDropdown === "web" ? "open" : ""}`}>
                    <button
                        className="nav-link"
                        onClick={() => handleDropdownToggle("web")}
                    >
                        Web Development
                        <i className="fa-solid fa-chevron-down"></i>
                    </button>
                    <ul className="sub-menu">
                        {prog?.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>{course.name}</Link>
                            </li>
                        ))}
                    </ul>
                </li>

                {/* Data Science & AI */}
                <li className={`nav-item has-sub ${openDropdown === "data" ? "open" : ""}`}>
                    <button
                        className="nav-link"
                        onClick={() => handleDropdownToggle("data")}
                    >
                        Data Science & AI
                        <i className="fa-solid fa-chevron-down"></i>
                    </button>
                    <ul className="sub-menu">
                        {graph?.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>{course.name}</Link>
                            </li>
                        ))}
                    </ul>
                </li>

                <li className="nav-item">
                    <Link className={`nav-link ${isActive("/reviews") ? "active" : ""}`} href="/reviews">
                        Mobile Development
                    </Link>
                </li>

                {/* ICT */}
                <li className={`nav-item has-sub ${openDropdown === "ict" ? "open" : ""}`}>
                    <button
                        className="nav-link"
                        onClick={() => handleDropdownToggle("ict")}
                    >
                        ICT
                        <i className="fa-solid fa-chevron-down"></i>
                    </button>
                    <ul className="sub-menu">
                        {ict?.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>{course.name}</Link>
                            </li>
                        ))}
                    </ul>
                </li>

                <li className="nav-item">
                    <Link className={`nav-link ${isActive("/project") ? "active" : ""}`} href="/project">
                        Projects
                    </Link>
                </li>

                <li className="nav-item">
                    <Link className={`nav-link ${isActive("/about") ? "active" : ""}`} href="/about">
                        About Us
                    </Link>
                </li>
            </ul>

            {/* Login */}
            <div className="right-login-section">
                <Link href="/login" className="login-btn">
                    <i className="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </Link>
            </div>
        </nav>
    );
}
