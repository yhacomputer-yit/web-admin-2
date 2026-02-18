import { Link } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Navigation({ prog, graph, ict }) {
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
    }, []);

    return (
        <nav className="glass-navbar">
            <a className="logo" href="/">
                <img style={{width: '100px', height: '100px'}} src="/image/logo/logo.png" alt="YHA Logo" />
                <span><h1 className="fw-bold">YHA</h1><h6 style={{letterSpacing: '2px'}}>Computer</h6></span>
            </a>

            <div className="hamburger" id="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul className="nav-menu" id="main-nav">
                <li className="nav-item">
                    <a className="nav-link active" href="/"><i className="fa-solid fa-house"></i> Home</a>
                </li>
                <li className="nav-item has-sub">
                    <a className="nav-link" href="#"><i className="fa-solid fa-code"></i> Programming <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></a>
                    <ul className="sub-menu">
                        {prog && prog.map((course) => (
                            <li key={course.id}>
                                <a href={`/yha/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </a>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                    <a className="nav-link" href="#"><i className="fa-solid fa-pen-nib"></i> Graphic Design <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></a>
                    <ul className="sub-menu">
                        {graph && graph.map((course) => (
                            <li key={course.id}>
                                <a href={`/yha/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </a>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item has-sub">
                    <a className="nav-link" href="#"><i className="fa-solid fa-network-wired"></i> ICT <i className="fa-solid fa-chevron-down" style={{fontSize: '0.8em'}}></i></a>
                    <ul className="sub-menu">
                        {ict && ict.map((course) => (
                            <li key={course.id}>
                                <a href={`/yha/course/${course.id}`}>
                                    <i className="fa-solid fa-arrow-right"></i> {course.name}
                                </a>
                            </li>
                        ))}
                    </ul>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="/yha/courses/monthl"><i className="fa-solid fa-calendar-days"></i> Monthly Courses</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="/yha/project"><i className="fa-solid fa-diagram-project"></i> Projects</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="/yha/event"><i className="fa-solid fa-bolt"></i> Events</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="/reviews"><i className="fa-solid fa-star"></i> Reviews</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="/gallery"><i className="fa-solid fa-images"></i> Gallery</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link" href="/yha/about"><i className="fa-solid fa-circle-info"></i> About Us</a>
                </li>
            </ul>
        </nav>
    );
}
