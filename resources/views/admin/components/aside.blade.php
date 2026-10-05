<style>
    .menu-item:hover {
        background: rgb(241, 241, 241);
    }

    .dropdown-menu a:hover {
        background-color: #ff6c0f;
        color: white;
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('Logo-png-.png') }}" width="70" alt="" class="">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2 text-uppercase">YHA</span>
        </a>

        {{-- closes the off-canvas drawer on small screens; admin-sidebar.css hides it
             from 1200px up, where the rail is permanent. Deliberately not the
             template's `layout-menu-toggle`: that would also flip its own
             `layout-menu-collapsed` state and quietly collapse the desktop rail. --}}
        <a href="javascript:void(0);" class="sidebar-drawer-close menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    {{-- collapse / expand the sidebar to an icon-only rail --}}
    <button type="button" id="sidebarToggle" class="sidebar-toggle" aria-controls="layout-menu" aria-expanded="true"
        aria-label="Collapse sidebar" title="Collapse sidebar">
        <i class="bx bx-chevron-left"></i>
    </button>

    {{-- Grouped by what the pages do, not by which controller they live in: an admin
         opening "Attendance" is looking for a student, and "Drop Outs" is a student
         record too. Each heading is a button that folds its own group away, and the
         items stay flat children of .menu-inner so the template's collapsed-rail
         metrics (which key off `.menu-inner > .menu-item`) still apply. --}}
    <ul class="menu-inner py-1">
        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="overview" aria-expanded="true">
                <span>Overview</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        <li class="menu-item" data-group="overview">
            <a href="{{ route('admin.home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div data-i18n="userInterface">Dashboard</div>
            </a>
        </li>

        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="course" aria-expanded="true">
                <span>Course Management</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        <!-- Course -->
        <li class="menu-item" data-group="course">
            <a href="{{ route('admin.course') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-book-reader"></i>
                <div data-i18n="Dashboards">Course</div>
            </a>
        </li>
        <!-- Section -->
        <li class="menu-item" data-group="course">
            <a href="{{ route('admin.section') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-hourglass"></i>
                <div data-i18n="Dashboards">Section</div>
            </a>
        </li>
        {{-- Course -> Section linking --}}
        <li class="menu-item" data-group="course">
            <a href="{{ route('course.section.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-link-alt"></i>
                <div data-i18n="Dashboards">Course Sections</div>
            </a>
        </li>
        <!-- TimeTable -->
        <li class="menu-item" data-group="course">
            <a href="{{ route('admin.timetable') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Dashboards">TimeTable</div>
            </a>
        </li>
        {{-- per subject materials --}}
        <li class="menu-item" data-group="course">
            <a href="{{ route('material.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder-open"></i>
                <div data-i18n="Dashboards">Reference</div>
            </a>
        </li>

        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="student" aria-expanded="true">
                <span>Student Management</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        <!-- Student -->
        <li class="menu-item" data-group="student">
            <a href="{{ route('admin.student') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Dashboards">Student</div>
            </a>
        </li>
        <!-- Course Enrollment -->
        <li class="menu-item" data-group="student">
            <a href="{{ route('admin.enrollment') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-book-add"></i>
                <div data-i18n="Dashboards">Course Enrollment</div>
            </a>
        </li>
        {{-- Attendance --}}
        <li class="menu-item" data-group="student">
            <a href="{{ route('attendance.createPage') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Dashboards">Attendance</div>
            </a>
        </li>
        {{-- <li class="menu-item" data-group="student">
            <a href="{{ route('attendance.report') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Dashboards">Attendance Reports</div>
            </a>
        </li> --}}
        {{-- students who left a class before it finished --}}
        <li class="menu-item" data-group="student">
            <a href="{{ route('dropOut.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-minus"></i>
                <div data-i18n="Dashboards">Drop Outs</div>
            </a>
        </li>

        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="result" aria-expanded="true">
                <span>Exam &amp; Result</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        {{-- exam sittings, with their papers --}}
        <li class="menu-item" data-group="result">
            <a href="{{ route('exam.index') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-clipboard"></i>
                <div data-i18n="Dashboards">Exam</div>
            </a>
        </li>
        <li class="menu-item" data-group="result">
            <a href="{{ route('gradingResult.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-note"></i>
                <div data-i18n="Dashboards">Marks</div>
            </a>
        </li>
        {{-- the grade bands, then the marks filed against them --}}
        <li class="menu-item" data-group="result">
            <a href="{{ route('grading.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-slider-alt"></i>
                <div data-i18n="Dashboards">Grading</div>
            </a>
        </li>
        {{-- certificates issued, and which have been handed over --}}
        <li class="menu-item" data-group="result">
            <a href="{{ route('certificate.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-award"></i>
                <div data-i18n="Dashboards">Certificates</div>
            </a>
        </li>

        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="people" aria-expanded="true">
                <span>People &amp; Staff</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        <!-- Teacher -->
        <li class="menu-item" data-group="people">
            <a href="{{ route('admin.teacher') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="Dashboards">Instructor</div>
            </a>
        </li>
        <!-- Admin List  -->
        <li class="menu-item" data-group="people">
            <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Dashboards">Staff</div>
            </a>
        </li>

        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="content" aria-expanded="true">
                <span>Content &amp; Media</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        <!-- Project -->
        <li class="menu-item" data-group="content">
            <a href="{{ route('admin.project') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder"></i>
                <div data-i18n="Dashboards">Project</div>
            </a>
        </li>
        <!-- Reviews -->
        <li class="menu-item" data-group="content">
            <a href="{{ route('admin.review.index') }}" class="menu-link">
                <i class="menu-icon fa-solid fa-star"></i>
                <div data-i18n="Dashboards">Reviews</div>
            </a>
        </li>
        {{-- <li class="menu-item" data-group="content">
            <a href="{{ route('admin.gallery') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>
                <div data-i18n="Dashboards">Gallery</div>
            </a>
        </li> --}}
        <li class="menu-item" data-group="content">
            <a href="{{ route('event.index') }}" class="menu-link">
                <i class="menu-icon fa-solid fa-champagne-glasses"></i>
                <div data-i18n="Dashboards">Events</div>
            </a>
        </li>

        <li class="nav-group">
            <button type="button" class="nav-group-toggle" data-group="system" aria-expanded="true">
                <span>System &amp; POS</span>
                <i class="bx bx-chevron-down nav-group-chevron"></i>
            </button>
        </li>
        {{-- POS List --}}
        <li class="menu-item dropdown" data-group="system">
            <a href="#" class="menu-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                <i class="menu-icon fa-solid fa-file-invoice-dollar"></i>
                <div data-i18n="Dashboards">POS</div>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('pos') }}">Invoice</a></li>
                <li><a class="dropdown-item" href="{{ route('income_list') }}">Income List</a></li>
                <li><a class="dropdown-item" href="{{ route('final_pay') }}">Final Payment</a></li>
            </ul>
        </li>
        <!-- User Interface -->
        <li class="menu-item" data-group="system">
            <a href="{{ route('admin.home') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-color-fill"></i>
                <div data-i18n="userInterface">User Interface</div>
            </a>
        </li>
    </ul>
</aside>

{{-- Below the template's 1200px breakpoint the rail becomes this drawer, and
     these two are the only way in and out of it. Hidden from 1200px up. --}}
<button type="button" id="sidebarDrawerToggle" class="sidebar-drawer-toggle" aria-controls="layout-menu"
    aria-expanded="false" aria-label="Open menu">
    <i class="bx bx-menu bx-sm"></i>
</button>
<div class="sidebar-drawer-backdrop" hidden></div>

<!-- / Menu -->

<script>
    (function () {
        const KEY = 'yha.sidebar.collapsed';
        const GROUP_KEY = 'yha.nav.groups';
        const root = document.documentElement;
        const btn = document.getElementById('sidebarToggle');
        const menu = document.getElementById('layout-menu');
        if (!btn || !menu) return;

        // labels come from the markup so the tooltip can never drift from the menu text
        menu.querySelectorAll('.menu-inner > .menu-item > .menu-link').forEach(function (link) {
            const label = link.querySelector('div:not(.menu-block)');
            if (label && !link.dataset.label) {
                link.dataset.label = label.textContent.trim();
            }
        });

        function isCollapsed() {
            return root.classList.contains('sidebar-collapsed');
        }

        function syncButton() {
            const collapsed = isCollapsed();
            btn.setAttribute('aria-expanded', String(!collapsed));
            btn.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
            btn.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
        }

        function setCollapsed(collapsed, persist) {
            root.classList.toggle('sidebar-collapsed', collapsed);
            if (persist) {
                try {
                    if (collapsed) { localStorage.setItem(KEY, '1'); }
                    else { localStorage.removeItem(KEY); }
                } catch (e) { /* private mode: just don't remember it */ }
            }
            syncButton();
        }

        btn.addEventListener('click', function () {
            setCollapsed(!isCollapsed(), true);
        });

        // reset the rail on small screens where the template uses an overlay menu
        let mq = window.matchMedia('(min-width: 1200px)');
        function applyViewport(e) {
            if (!e.matches) {
                root.classList.remove('sidebar-collapsed');
            } else {
                let saved = null;
                try { saved = localStorage.getItem(KEY); } catch (err) { /* ignore */ }
                root.classList.toggle('sidebar-collapsed', saved === '1');
            }
            syncButton();
        }
        applyViewport(mq);
        if (mq.addEventListener) { mq.addEventListener('change', applyViewport); }
        else if (mq.addListener) { mq.addListener(applyViewport); }

        // ---- collapsible groups (accordion) ----
        // Only one group is open at a time. Which one is remembered, because every
        // menu link is a full page load: without this, opening a group would be
        // undone by the navigation it was opened for.
        const groupToggles = menu.querySelectorAll('.nav-group-toggle');
        const groupNames = Array.from(groupToggles).map(function (toggle) {
            return toggle.dataset.group;
        });

        function applyGroup(name, open) {
            root.classList.toggle('nav-hide-' + name, !open);
            const toggle = menu.querySelector('.nav-group-toggle[data-group="' + name + '"]');
            if (toggle) { toggle.setAttribute('aria-expanded', String(open)); }
        }

        function closeOpenDropdowns() {
            // A submenu left open inside a folded group just hangs there invisible,
            // and BS only closes them for clicks it sees itself.
            menu.querySelectorAll('.dropdown-toggle').forEach(function (toggle) {
                const instance = window.bootstrap && window.bootstrap.Dropdown
                    ? window.bootstrap.Dropdown.getInstance(toggle)
                    : null;

                if (instance) { instance.hide(); }
            });
        }

        if (groupToggles.length) {
            let remembered = null;

            try {
                remembered = JSON.parse(localStorage.getItem(GROUP_KEY));
            } catch (e) { /* unreadable or not an object */ }

            const stored = remembered && typeof remembered === 'object' ? remembered.open : null;

            /* The page you are on wins, so the item you navigated to is never left
               hidden inside a folded group. Failing that, the group last opened;
               failing that, the first one. */
            const activeItem = menu.querySelector('.menu-item.active[data-group]');
            const openOnLoad = (activeItem && activeItem.dataset.group) || stored || groupNames[0];

            groupNames.forEach(function (name) {
                applyGroup(name, name === openOnLoad);
            });

            groupToggles.forEach(function (toggle) {
                const name = toggle.dataset.group;

                toggle.addEventListener('click', function () {
                    // every group, not just this one: that is what makes it an accordion
                    groupNames.forEach(function (other) {
                        applyGroup(other, other === name);
                    });

                    try {
                        localStorage.setItem(GROUP_KEY, JSON.stringify({ open: name }));
                    } catch (e) { /* private mode: it just will not be remembered */ }

                    closeOpenDropdowns();
                });
            });
        }

        // ---- off-canvas drawer below the template's breakpoint ----
        // From 1200px the rail is fixed and always on screen, so the drawer only
        // exists underneath that. The opener and the backdrop live outside the
        // aside so the rail keeps the exact markup the template expects.
        const drawerToggle = document.getElementById('sidebarDrawerToggle');
        const backdrop = document.querySelector('.sidebar-drawer-backdrop');
        const wide = window.matchMedia('(min-width: 1200px)');

        if (drawerToggle && backdrop) {
            const closeChevron = menu.querySelector('.sidebar-drawer-close');

            function applyDrawer(open) {
                // growing back to a permanent rail must also undo the scroll lock
                if (wide.matches) { open = false; }

                root.classList.toggle('sidebar-open', open);
                backdrop.hidden = !open;
                drawerToggle.setAttribute('aria-expanded', String(open));
                drawerToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
            }

            drawerToggle.addEventListener('click', function () {
                applyDrawer(!root.classList.contains('sidebar-open'));
            });

            if (closeChevron) {
                closeChevron.addEventListener('click', function () { applyDrawer(false); });
            }

            backdrop.addEventListener('click', function () { applyDrawer(false); });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') { applyDrawer(false); }
            });

            // a menu link navigates away, so the drawer goes back off the edge with it
            menu.querySelectorAll('.menu-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    // the POS toggle opens a submenu instead of navigating, so
                    // closing the drawer here made it unreachable on a tablet
                    if (link.classList.contains('dropdown-toggle')) { return; }

                    closeOpenDropdowns();
                    applyDrawer(false);
                });
            });

            function closeWhenWide(event) { if (event.matches) { applyDrawer(false); } }
            if (wide.addEventListener) { wide.addEventListener('change', closeWhenWide); }
            else if (wide.addListener) { wide.addListener(closeWhenWide); }
        }
    })();
</script>