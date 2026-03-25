import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState, useRef, useCallback } from 'react';

export default function Navigation({ prog, graph, ict }) {
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 10);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);
    const { url } = usePage();

    const isActive = (path) => {
        if (path === '/') {
            return url === '/' || url === '';
        }
        return url.startsWith(path);
    };
    const navRef = useRef(null);
    const hamburgerRef = useRef(null);
    const subLinksRef = useRef([]);

    const handleHamburgerClick = useCallback(() => {
        if (navRef.current) {
            navRef.current.classList.toggle('open');
        }
    }, []);

    const handleSubLinkClick = useCallback((e) => {
        if (e.currentTarget.getAttribute('href') === '#' && window.innerWidth < 992) {
            e.preventDefault();
            const parent = e.currentTarget.parentElement;
            parent.classList.toggle('open');
        }
    }, []);

    useEffect(() => {
        const hamburger = hamburgerRef.current;
        const navMenu = navRef.current;
        const subLinks = subLinksRef.current;

        if (hamburger && navMenu) {
            hamburger.addEventListener('click', handleHamburgerClick);
        }

        subLinks.forEach((link, index) => {
            if (link && link.querySelector('a[href=\"#\"]')) {
                link.addEventListener('click', handleSubLinkClick);
            }
        });

        return () => {
            if (hamburger && navMenu) {
                hamburger.removeEventListener('click', handleHamburgerClick);
            }
            subLinks.forEach((link) => {
                if (link) {
                    link.removeEventListener('click', handleSubLinkClick);
                }
            });
        };
    }, [handleHamburgerClick, handleSubLinkClick]);

    return (
        <nav className={`glass-navbar ${scrolled ? 'scrolled' : ''}`}>
            <Link className="logo" href="/">
                <img style={{width: '100px', height: '100px'}} src="/image/logo/logo.png" alt="YHA Logo" />
            </Link>

            <div ref={hamburgerRef} className="hamburger" id="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul ref={navRef} className="nav-menu" id="main-nav">
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/') ? 'active' : ''}`} href="/"><i className="fa-solid fa-house"></i> Home</Link>
                </li>
                <li className="nav-item has-sub">
                        <Link ref={(el) => subLinksRef.current[0] = el} className={`nav-link ${isActive('/course') ? 'active' : ''}`} href="#"><i className="fa-solid fa-code"></i> Programming <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {prog && prog.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                        <Link ref={(el) => subLinksRef.current[1] = el} className={`nav-link ${isActive('/course') ? 'active' : ''}`} href="#"><i className="fa-solid fa-pen-nib"></i> Graphic Design <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {graph && graph.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                        <Link ref={(el) => subLinksRef.current[2] = el} className={`nav-link ${isActive('/course') ? 'active' : ''}`} href="#"><i className="fa-solid fa-network-wired"></i> ICT <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {ict && ict.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/courses') ? 'active' : ''}`} href="/courses/monthl"><i className="fa-solid fa-calendar-days"></i> Monthly Courses</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/project') ? 'active' : ''}`} href="/project"><i className="fa-solid fa-diagram-project"></i> Projects</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/event') ? 'active' : ''}`} href="/event"><i className="fa-solid fa-bolt"></i> Events</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/reviews') ? 'active' : ''}`} href="/reviews"><i className="fa-solid fa-star"></i> Reviews</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/about') ? 'active' : ''}`} href="/about"><i className="fa-solid fa-circle-info"></i> About Us</Link>
                </li>
            </ul>
        </nav>
    );
}

