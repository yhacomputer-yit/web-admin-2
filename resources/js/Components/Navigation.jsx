import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState, useRef } from 'react';

export default function Navigation({ prog, graph, ict, contactInfo = {} }) {
    // Set default contact info if not provided
    const defaultContactInfo = {
        address: '123 University Street, Tech City',
        phone: '+1 (555) 123-4567',
        email: 'info@yhauniversity.edu'
    };

    const contactData = { ...defaultContactInfo, ...contactInfo };
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);

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
        if (path === '/courses') {
            return url === '/courses' || url.startsWith('/course');
        }
        return url.startsWith(path);
    };

    const isCourseActive = (courseType) => {
        // Check if current URL contains course details for this specific course type
        if (courseType === 'programming' && prog) {
            return prog.some(course => url.includes(`/course/${course.id}`));
        }
        if (courseType === 'graphic' && graph) {
            return graph.some(course => url.includes(`/course/${course.id}`));
        }
        if (courseType === 'ict' && ict) {
            return ict.some(course => url.includes(`/course/${course.id}`));
        }
        return false;
    };

    const handleMobileMenuToggle = () => {
        setMobileMenuOpen(!mobileMenuOpen);
        setOpenDropdown(null); // Close all dropdowns when toggling menu
    };

    const handleDropdownToggle = (dropdownName) => {
        if (window.innerWidth < 992) {
            setOpenDropdown(openDropdown === dropdownName ? null : dropdownName);
        }
    };

    // Close mobile menu when resizing to desktop
    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth >= 992) {
                setMobileMenuOpen(false);
                setOpenDropdown(null);
            }
        };

        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    // Close mobile menu when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (mobileMenuOpen && !event.target.closest('.tech-university-navbar')) {
                setMobileMenuOpen(false);
                setOpenDropdown(null);
            }
        };

        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    }, [mobileMenuOpen]);

    return (
        <>
            {/* Main Navigation */}
            <nav className={`tech-university-navbar ${scrolled ? 'scrolled' : ''}`}>
                <div className={`hamburger ${mobileMenuOpen ? 'active' : ''}`} onClick={handleMobileMenuToggle}>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul className={`nav-menu ${mobileMenuOpen ? 'open' : ''}`}>

                     <li className="nav-item">
                        <Link className="logo" href="/">
                            <img style={{width: '100px', height: '100px'}} src="/image/logo/logo.png" alt="YHA Logo" />
                        </Link>
                    </li>
                    <li className="nav-item">
                        <Link className={`nav-link ${isActive('/') ? 'active' : ''}`} href="/"> Home</Link>
                    </li>
                    <li className={`nav-item has-sub ${openDropdown === 'programming' ? 'open' : ''}`}>
                        <button 
                            className={`nav-link ${isCourseActive('programming') ? 'active' : ''}`}
                            onClick={() => handleDropdownToggle('programming')}
                            style={{background: 'none', border: 'none', cursor: 'pointer'}}
                        >
                            <Link href="/courses" style={{textDecoration: 'none', color: 'inherit', display: 'flex', alignItems: 'center', gap: '0.5rem'}}>
                                Programming <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i>
                            </Link>
                        </button>
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
                    <li className={`nav-item has-sub ${openDropdown === 'graphic' ? 'open' : ''}`}>
                        <button 
                            className={`nav-link ${isCourseActive('graphic') ? 'active' : ''}`}
                            onClick={() => handleDropdownToggle('graphic')}
                            style={{background: 'none', border: 'none', cursor: 'pointer'}}
                        >
                            <Link href="/courses" style={{textDecoration: 'none', color: 'inherit', display: 'flex', alignItems: 'center', gap: '0.5rem'}}>
                                Graphic Design <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i>
                            </Link>
                        </button>
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
                    <li className={`nav-item has-sub ${openDropdown === 'ict' ? 'open' : ''}`}>
                        <button 
                            className={`nav-link ${isCourseActive('ict') ? 'active' : ''}`}
                            onClick={() => handleDropdownToggle('ict')}
                            style={{background: 'none', border: 'none', cursor: 'pointer'}}
                        >
                            <Link href="/courses" style={{textDecoration: 'none', color: 'inherit', display: 'flex', alignItems: 'center', gap: '0.5rem'}}>
                                ICT <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i>
                            </Link>
                        </button>
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
                    <li className="nav-item">
                        <Link className={`nav-link ${isActive('/reviews') ? 'active' : ''}`} href="/reviews">Reviews</Link>
                    </li>
                    <li className="nav-item">
                        <Link className={`nav-link ${isActive('/about') ? 'active' : ''}`} href="/about"> About Us</Link>
                    </li>
                    <li className="nav-item">
                        <Link href="/admin/loginPage" className="login-btn">
                            <i className="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </Link>
                    </li>
                </ul>
            </nav>
        </>
    );
}

