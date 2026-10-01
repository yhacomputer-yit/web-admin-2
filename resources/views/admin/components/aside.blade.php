
<style>
    .menu-item:hover{
        background: rgb(241, 241, 241);
   }
    .dropdown-menu a:hover{
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

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    {{-- collapse / expand the sidebar to an icon-only rail  --}}
    <button type="button" id="sidebarToggle" class="sidebar-toggle"
        aria-controls="layout-menu" aria-expanded="true" aria-label="Collapse sidebar"
        title="Collapse sidebar">
        <i class="bx bx-chevron-left"></i>
    </button>

    <ul class="menu-inner py-1">
         <li class="menu-item">
            <a href="{{ route('admin.home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div data-i18n="userInterface">Dashboard</div>
            </a>
        </li>
        <!-- User Interface -->
        <li class="menu-item {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <a href="{{ route('admin.home') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-color-fill"></i>
                <div data-i18n="userInterface">User Interface</div>
            </a>
        </li>
        <!-- TimeTable -->
        <li class="menu-item {{ request()->routeIs('admin.timetable') ? 'active' : '' }}">
            <a href="{{ route('admin.timetable') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Dashboards">TimeTable</div>
            </a>
        </li>
        {{-- Attendance --}}
        <li class="menu-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <a href="{{ route('attendance.createPage') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Dashboards">Attendance</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('attendance.report*') ? 'active' : '' }}">
            <a href="{{ route('attendance.report') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Dashboards">Attendance Reports</div>
            </a>
        </li>
        <!-- Course -->
        <li class="menu-item {{ request()->routeIs('admin.course') ? 'active' : '' }}">
            <a href="{{ route('admin.course') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-book-reader"></i>
                <div data-i18n="Dashboards">Course</div>
            </a>
        </li>
        <!-- Section -->
        <li class="menu-item {{ request()->routeIs('admin.section') ? 'active' : '' }}">
            <a href="{{ route('admin.section') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-hourglass"></i>
                <div data-i18n="Dashboards">Section</div>
            </a>
        </li>
                {{-- Course -> Section linking --}}
        <li class="menu-item {{ request()->routeIs('course.section.*') ? 'active' : '' }}">
            <a href="{{ route('course.section.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-link-alt"></i>
                <div data-i18n="Dashboards">Course Sections</div>
            </a>
        </li>
        <!-- Teacher -->
        <li class="menu-item {{ request()->routeIs('admin.teacher') ? 'active' : '' }}">
            <a href="{{ route('admin.teacher') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Dashboards">Instructor</div>
            </a>
        </li>
        <!-- Student -->
        <li class="menu-item {{ request()->routeIs('admin.student') ? 'active' : '' }}">
            <a href="{{ route('admin.student') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Dashboards">Student</div>
            </a>
        </li>
        <!-- Course Enrollment -->
        <li class="menu-item {{ request()->routeIs('admin.enrollment') ? 'active' : '' }}">
            <a href="{{ route('admin.enrollment') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book-add"></i>
                <div data-i18n="Dashboards">Course Enrollment</div>
            </a>
        </li>
        <!-- Project -->
        <li class="menu-item {{ request()->routeIs('admin.project') ? 'active' : '' }}">
            <a href="{{ route('admin.project') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder"></i>
                <div data-i18n="Dashboards">Project</div>
            </a>
        </li>
        <!-- Reviews -->
        <li class="menu-item {{ request()->routeIs('admin.review.*') ? 'active' : '' }}">
            <a href="{{ route('admin.review.index') }}" class="menu-link">
                <i class="menu-icon fa-solid fa-star"></i>
                <div data-i18n="Dashboards">Reviews</div>
            </a>
        </li>
        <!-- Project -->
        {{-- <li class="menu-item {{ request()->routeIs('admin.gallery') ? 'active' : '' }}">
            <a href="{{ route('admin.gallery') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>
                <div data-i18n="Dashboards">Gallery</div>
            </a>
        </li> --}}

        <li class="menu-item {{ request()->routeIs('event.index') ? 'active' : '' }}">
            <a href="{{ route('event.index') }}" class="menu-link">
                <i class="menu-icon fa-solid fa-champagne-glasses"></i>
                <div data-i18n="Dashboards">Events</div>
            </a>
        </li>
        <!-- Admin List  -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Dashboards">Staff</div>
            </a>
        </li>
        {{-- POS List --}}
        <li class="menu-item dropdown">
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



    </ul>
</aside>

<!-- / Menu -->

<script>
(function () {
    const KEY = 'yha.sidebar.collapsed';
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
})();
</script>
