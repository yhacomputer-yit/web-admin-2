import './bootstrap';
import '../css/master.css'
import '../css/f_footer.css'
import '../css/pages/student-dashboard.css'
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import '../css/layouts/student-layout.css'
import '../css/pages/student-attendance.css'
import '../css/pages/student-portal.css'
import '../css/pages/student-courses.css'

// Create the Inertia app
createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
