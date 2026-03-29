import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState, useRef, useCallback } from 'react';

export default function Navigation({ prog, graph, ict, contactInfo = {} }) {
    // Set default contact info if not provided
    const defaultContactInfo = {
        address: '123 University Street, Tech City',
        phone: '+1 (555) 123-4567',
        email: 'info@yhauniversity.edu'
    };

    const contactData = { ...defaultContactInfo, ...contactInfo };
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
        if (hamburgerRef.current) {
            hamburgerRef.current.classList.toggle('active');
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
        <>
            {/* Main Navigation */}
            <nav className={`tech-university-navbar ${scrolled ? 'scrolled' : ''}`}>
               

            <div ref={hamburgerRef} className="hamburger" id="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul ref={navRef} className="nav-menu" id="main-nav">

                     <li className="nav-item">
                 <Link className="logo" href="/">
                        <img style={{width: '100px', height: '100px'}} src="/image/logo/logo.png" alt="YHA Logo" />
                    </Link>
                    </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/') ? 'active' : ''}`} href="/"> Home</Link>
                </li>
                <li className="nav-item has-sub">
                        <Link ref={(el) => subLinksRef.current[0] = el} className={`nav-link ${isActive('/course') ? 'active' : ''}`} href="#"> Programming <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {prog && prog.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>
                                   {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                        <Link ref={(el) => subLinksRef.current[1] = el} className={`nav-link ${isActive('/course') ? 'active' : ''}`} href="#"> Graphic Design <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {graph && graph.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>
                                   {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                        <Link ref={(el) => subLinksRef.current[2] = el} className={`nav-link ${isActive('/course') ? 'active' : ''}`} href="#"> ICT <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {ict && ict.map((course) => (
                            <li key={course.id}>
                                <Link href={`/course/${course.id}`}>
                                   {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/project') ? 'active' : ''}`} href="/project"> Projects</Link>
                </li>
                {/* <li className="nav-item">
                    <Link className={`nav-link ${isActive('/event') ? 'active' : ''}`} href="/event"> Events</Link>
                </li> */}
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/reviews') ? 'active' : ''}`} href="/reviews">Reviews</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/about') ? 'active' : ''}`} href="/about"> About Us</Link>
                </li>
                    <li className="nav-item">
                         <Link href="/login" className="login-btn">
                                <i className="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </Link>
                    </li>
            </ul>
        </nav>
        </>
    );
}

