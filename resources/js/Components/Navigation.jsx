import { Link, usePage } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Navigation({ prog, graph, ict }) {
    const { url } = usePage();

    const isActive = (path) => {
        if (path === '/') {
            return url === '/' || url === '';
        }
        return url.startsWith(path);
    };
    useEffect(() => {
        // Hamburger menu toggle
        const hamburger = document.getElementById('hamburger-menu');
        const navMenu = document.getElementById('main-nav');
        if (hamburger && navMenu) {
            hamburger.addEventListener('click', function() {
                navMenu.classList.toggle('open');
            });
        }

        // Mobile submenu toggle
        document.querySelectorAll('.glass-navbar .has-sub > .nav-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    const parent = link.parentElement;
                    parent.classList.toggle('open');
                }
            });
        });
    }, [prog, graph, ict]);

    return (
        <nav className="glass-navbar">
            <Link className="logo" href="/">
                <img style={{width: '100px', height: '100px'}} src="/image/logo/logo.png" alt="YHA Logo" />
                {/* <span><h1 className="fw-bold">YHA</h1><h6 style={{letterSpacing: '2px'}}>Computer</h6></span> */}
            </Link>

            <div className="hamburger" id="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul className="nav-menu" id="main-nav">
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/') ? 'active' : ''}`} href="/"><i className="fa-solid fa-house"></i> Home</Link>
                </li>
                <li className="nav-item has-sub">
                    <Link className={`nav-link ${isActive('/yha/course') ? 'active' : ''}`} href="#"><i className="fa-solid fa-code"></i> Programming <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {prog && prog.map((course) => (
                            <li key={course.id}>
                                <Link href={`/yha/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                    <Link className={`nav-link ${isActive('/yha/course') ? 'active' : ''}`} href="#"><i className="fa-solid fa-pen-nib"></i> Graphic Design <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {graph && graph.map((course) => (
                            <li key={course.id}>
                                <Link href={`/yha/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                    <Link className={`nav-link ${isActive('/yha/course') ? 'active' : ''}`} href="#"><i className="fa-solid fa-network-wired"></i> ICT <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></Link>
                    <ul className="sub-menu">
                        {ict && ict.map((course) => (
                            <li key={course.id}>
                                <Link href={`/yha/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/yha/courses') ? 'active' : ''}`} href="/yha/courses/monthl"><i className="fa-solid fa-calendar-days"></i> Monthly Courses</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/yha/project') ? 'active' : ''}`} href="/yha/project"><i className="fa-solid fa-diagram-project"></i> Projects</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/yha/event') ? 'active' : ''}`} href="/yha/event"><i className="fa-solid fa-bolt"></i> Events</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/reviews') ? 'active' : ''}`} href="/reviews"><i className="fa-solid fa-star"></i> Reviews</Link>
                </li>
                <li className="nav-item">
                    <Link className={`nav-link ${isActive('/yha/about') ? 'active' : ''}`} href="/yha/about"><i className="fa-solid fa-circle-info"></i> About Us</Link>
                </li>
            </ul>
        </nav>
    );
}
