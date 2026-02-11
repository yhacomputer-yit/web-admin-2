<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YHA - Computer Training Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="{{ asset('image/logo/logo.png') }}" type="" />
    <link rel="stylesheet" href="{{ asset('css/f_home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/f_course_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/f_footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;500;900&display=swap" rel="stylesheet">
</head>

<style>
    :root {
        --maincolor: #ff6b01;
        --side-orange: #ff852d;
        --white: #ffffff;
        --sec-white: #222;
        --ab_bg: #333;
        --footer-link: #bbb;
        --footer-link-hover: #ff6b01;
        --glass-bg: rgba(255,255,255,0.75);
        --glass-blur: blur(16px);
        --navbar-gradient: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
    }

    body {
        background: var(--white);
        color: #333;
        font-family: 'Roboto', sans-serif;
        overflow-x: hidden;
    }

    /* Glassmorphism Navbar */
    .glass-navbar {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        border-radius: 18px;
        margin: 18px auto 0 auto;
        max-width: 100%;
        position: sticky;
        top: 12px;
        z-index: 200;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 2rem 0.5rem 2rem;
        border-bottom: 3px solid transparent;
        border-image: var(--navbar-gradient) 1;
        border-image-slice: 1;
        transition: box-shadow 0.2s;
    }

    .glass-navbar .logo {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        text-decoration: none;
    }

    .glass-navbar .logo img {
        width: 48px;
        height: 48px;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(255,107,1,0.10);
    }

    .glass-navbar .logo span {
        color: var(--maincolor);
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .glass-navbar .search-bar {
        flex: 1 1 320px;
        max-width: 340px;
        margin: 0 2rem;
        position: relative;
        display: flex;
        align-items: center;
    }

    .glass-navbar .search-bar input {
        width: 100%;
        padding: 0.5rem 2.2rem 0.5rem 1rem;
        border-radius: 20px;
        border: 1px solid #eee;
        background: rgba(255,255,255,0.7);
        font-size: 1rem;
        outline: none;
        transition: border 0.2s;
    }

    .glass-navbar .search-bar input:focus {
        border: 1.5px solid var(--maincolor);
    }

    .glass-navbar .search-bar .fa-search {
        position: absolute;
        right: 1rem;
        color: #bbb;
        font-size: 1.1rem;
    }

    .glass-navbar .nav-menu {
        display: flex;
        align-items: center;
        gap: 1.2rem;
    }

    .glass-navbar .nav-item {
        position: relative;
        list-style: none;
    }

    .glass-navbar .nav-link {
        color: #222;
        font-size: 15px;
        font-weight: 500;
        padding: 0.5rem 0.7rem;
        border-radius: 7px;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
    }

    .glass-navbar .nav-link i {
        color: var(--maincolor);
        font-size: 1.1em;
    }

    .glass-navbar .nav-link:hover, .glass-navbar .nav-link.active {
        background: rgba(255,107,1,0.10);
        color: var(--maincolor);
    }

    /* Dropdown (Desktop) */
    .glass-navbar .sub-menu {
        display: block;
        opacity: 0;
        visibility: hidden;
        position: absolute;
        left: 0;
        top: 110%;
        min-width: 210px;
        background: var(--glass-bg);
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        border-radius: 12px;
        padding: 0.5rem 0;
        z-index: 1001;
        transform: translateY(10px);
        transition: opacity 0.25s, transform 0.25s, visibility 0.25s;
        border: 1.5px solid #eee;
        backdrop-filter: var(--glass-blur);
    }

    .glass-navbar .nav-item:hover > .sub-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .glass-navbar .sub-menu li {
        width: 100%;
    }

    .glass-navbar .sub-menu a {
        color: #222;
        padding: 0.5rem 1.2rem;
        display: flex;
        align-items: center;
        font-size: 0.97rem;
        border-radius: 0;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        gap: 0.4rem;
    }

    .glass-navbar .sub-menu a:hover {
        background: var(--maincolor);
        color: #fff;
    }

    /* CTA Button */
    .glass-navbar .login-btn {
        background: var(--maincolor);
        color: #fff !important;
        border-radius: 20px;
        padding: 0.4rem 1.2rem;
        font-weight: 600;
        margin-left: 1rem;
        transition: background 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(255,107,1,0.10);
    }

    .glass-navbar .login-btn:hover {
        background: #e65c00;
        color: #fff !important;
    }

    /* Avatar/Profile */
    .glass-navbar .avatar {
        margin-left: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        overflow: hidden;
        border: 2px solid var(--maincolor);
        transition: box-shadow 0.2s;
    }

    .glass-navbar .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Hamburger */
    .glass-navbar .hamburger {
        display: none;
        flex-direction: column;
        justify-content: center;
        width: 36px;
        height: 36px;
        cursor: pointer;
        margin-left: 1.5rem;
    }

    .glass-navbar .hamburger span {
        height: 4px;
        width: 100%;
        background: #7e7272;
        margin: 4px 0;
        border-radius: 2px;
        transition: all 0.3s;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .glass-navbar {
            max-width: 100%;
            margin: 12px 8px 0 8px;
            padding: 0.5rem 1rem;
        }
        .glass-navbar .search-bar {
            margin: 0 1rem;
        }
    }

    @media (max-width: 991.98px) {
        .glass-navbar {
            flex-wrap: wrap;
            padding: 0.5rem 0.7rem;
        }
        .glass-navbar .search-bar {
            order: 3;
            width: 100%;
            max-width: 100%;
            margin: 0.7rem 0 0.2rem 0;
        }
        .glass-navbar .nav-menu {
            position: absolute;
            left: 0;
            top: 100%;
            width: 100vw;
            background: var(--glass-bg);
            flex-direction: column;
            align-items: flex-start;
            gap: 0;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-radius: 0 0 18px 18px;
            padding: 0.5rem 0 1rem 0;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
            transition: max-height 0.3s, opacity 0.3s;
        }
        .glass-navbar .nav-menu.open {
            max-height: 600px;
            opacity: 1;
            pointer-events: auto;
        }
        .glass-navbar .nav-item {
            width: 100%;
        }
        .glass-navbar .nav-link {
            width: 100%;
            padding: 0.9rem 1.5rem;
            border-radius: 0;
        }
        .glass-navbar .sub-menu {
            position: static;
            box-shadow: none;
            min-width: 100%;
            background: #f8f8f8;
            border-radius: 0;
            padding: 0;
            opacity: 1 !important;
            visibility: visible !important;
            transform: none;
            display: none;
        }
        .glass-navbar .nav-item.open > .sub-menu {
            display: block;
        }
        .glass-navbar .hamburger {
            display: flex;
        }
        .glass-navbar .login-btn, .glass-navbar .avatar {
            margin-left: 0.7rem;
        }
    }

    @media (max-width: 576px) {
        .glass-navbar {
            padding: 0.5rem 0.3rem;
        }
        .glass-navbar .logo img {
            width: 36px;
            height: 36px;
        }
        .glass-navbar .search-bar input {
            font-size: 0.95rem;
        }
    }

    /* Footer Modern Styles */
    #footer {
        background: var(--sec-white);
        color: #fff;
        padding: 40px 0 0 0;
        margin-top: 60px;
    }

    #footer .footer-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 2rem;
        padding-bottom: 30px;
    }

    #footer .footer-col {
        flex: 1 1 220px;
        min-width: 200px;
    }

    #footer h5 {
        color: var(--maincolor);
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: 1px;
    }

    #footer ul {
        list-style: none;
        padding: 0;
    }

    #footer ul li {
        margin-bottom: 0.7rem;
    }

    #footer ul li a {
        color: var(--footer-link);
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.2s;
    }

    #footer ul li a:hover {
        color: var(--footer-link-hover);
    }

    #footer .footer-contact span, #footer .footer-contact a {
        display: block;
        color: #bbb;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        text-decoration: none;
    }

    #footer .footer-social {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    #footer .footer-social a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #333;
        color: #fff;
        font-size: 1.3rem;
        transition: background 0.2s, color 0.2s, transform 0.2s;
    }

    #footer .footer-social a:hover {
        background: var(--maincolor);
        color: #fff;
        transform: scale(1.1);
    }

    #footer .footer-logo {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.7rem;
    }

    #footer .footer-logo img {
        width: 90px;
        margin-bottom: 0.5rem;
    }

    #footer .footer-logo span {
        font-size: 1rem;
        color: #bbb;
        letter-spacing: 1px;
    }

    #footer .copyright-bar {
        background: #181818;
        color: #bbb;
        text-align: center;
        padding: 12px 0 8px 0;
        font-size: 0.97rem;
        margin-top: 0;
        letter-spacing: 1px;
    }

    @media (max-width: 992px) {
        .glass-navbar .nav-link {
            padding: 0.5rem 0.7rem;
        }
        #footer .footer-row {
            flex-direction: column;
            gap: 1.5rem;
        }
        #footer .footer-col {
            min-width: 0;
        }
    }

    @media (max-width: 576px) {
        .glass-navbar {
            padding: 0.5rem 0.7rem;
        }
        #footer {
            padding: 30px 0 0 0;
        }
        #footer .footer-logo img {
            width: 60px;
        }
    }
</style>

<body>
    <!-- Premium Glassmorphism Navbar -->
    <nav class="glass-navbar">
        <a class="logo" href="{{route('user.home')}}">
            <img style="width: 100px; height: 100px;" src="{{ asset('image/logo/logo.png') }}" alt="YHA Logo">
            <span><h1 class="fw-bold">YHA</h1><h6 style="letter-spacing: 2px">Computer</h6></span>
        </a>

        <div class="hamburger" id="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-menu" id="main-nav">
            <li class="nav-item">
                <a class="nav-link active" href="{{route('user.home')}}"><i class="fa-solid fa-house"></i> Home</a>
            </li>
            <li class="nav-item has-sub">
                <a class="nav-link" href="#"><i class="fa-solid fa-code"></i> Programming <i class="fa-solid fa-chevron-down" style="font-size:0.8em;"></i></a>
                <ul class="sub-menu">
                    @foreach ($prog as $course)
                        <li><a href="{{ route('user.course', ['id' => $course->id]) }}"><i class="fa-solid fa-arrow-right"></i> {{ $course->name }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="nav-link" href="#"><i class="fa-solid fa-pen-nib"></i> Graphic Design <i class="fa-solid fa-chevron-down" style="font-size:0.8em;"></i></a>
                <ul class="sub-menu">
                    @foreach ($graph as $course)
                        <li><a href="{{ route('user.course', ['id' => $course->id]) }}"><i class="fa-solid fa-arrow-right"></i> {{ $course->name }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="nav-link" href="#"><i class="fa-solid fa-network-wired"></i> ICT <i class="fa-solid fa-chevron-down" style="font-size:0.8em;"></i></a>
                <ul class="sub-menu">
                    @foreach ($ict as $course)
                        <li><a href="{{ route('user.course', ['id' => $course->id]) }}"><i class="fa-solid fa-arrow-right"></i> {{ $course->name }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('user.courses')}}"><i class="fa-solid fa-calendar-days"></i> Monthly Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('user.project')}}"><i class="fa-solid fa-diagram-project"></i> Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('user.event')}}"><i class="fa-solid fa-bolt"></i> Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reviews.index') }}"><i class="fa-solid fa-star"></i> Reviews</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('user.about')}}"><i class="fa-solid fa-circle-info"></i> About Us</a>
            </li>
            {{-- <li class="nav-item d-lg-none">
                <a class="login-btn" href="/login">
                    <i class="fa-solid fa-user"></i> Login
                </a>
            </li> --}}

        </ul>
        {{-- <a class="login-btn d-none d-lg-flex" href="/login">
            <i class="fa-solid fa-user"></i> Login
        </a> --}}
    </nav>
    <script>
        // Hamburger menu toggle
        const hamburger = document.getElementById('hamburger-menu');
        const navMenu = document.getElementById('main-nav');
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('open');
        });
        // Mobile submenu toggle
        document.querySelectorAll('.glass-navbar .has-sub > .nav-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if(window.innerWidth < 992) {
                    e.preventDefault();
                    const parent = link.parentElement;
                    parent.classList.toggle('open');
                }
            });
        });
    </script>

    @yield('content')
    <!-- Modern Footer -->
    @php $footerAddr = isset($address) && count($address) ? $address->first() : null; @endphp
    <footer class="edu-footer">
      <div class="container py-5">
        <div class="text-center row gy-4 align-items-stretch text-lg-start">
          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
            <div class="mb-3 footer-brand">
              <img src="{{ asset('image/logo/logo.png') }}" alt="Logo" style="height:200px;">
            </div>
            {{-- <p class="mb-0 footer-desc">
              Build your future with Technology
            </p> --}}
          </div>
          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
            <h5 class="footer-title">Quick Links</h5>
            <ul class="p-0 m-0 footer-links">
              <li><a href="{{ route('user.home') }}">Home</a></li>
              <li><a href="{{ route('user.courses') }}">Courses</a></li>
              <li><a href="{{ route('user.event') }}">Events</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
            <h5 class="footer-title">Contact</h5>
            <ul class="p-0 m-0 footer-contact">
              <li><i class="fa-solid fa-location-dot"></i> {{ $footerAddr->address ?? '-' }}</li>
              <li><i class="fa-solid fa-phone"></i> {{ $footerAddr->yphNo ?? '-' }}</li>
              <li><i class="fa-solid fa-envelope"></i> {{ $footerAddr->yEmail ?? '-' }}</li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
            <h5 class="footer-title">Follow Us</h5>
            <div class="mb-3 footer-social">
              <a href="https://t.me/yha202" aria-label="Telegram" target="_blank"><i class="fa-brands fa-telegram"></i></a>
              <a href="https://www.facebook.com/yhacomputerhledan" aria-label="Facebook" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="https://www.youtube.com/channel/UCTwXsN1TMJuEiCuFXacQbkA" aria-label="YouTube" target="_blank"><i class="fa-brands fa-youtube"></i></a>
            </div>
            <a href="#contact" class="footer-cta">Join Our Classes</a>
          </div>
        </div>
        <hr class="my-4 footer-divider">
        <div class="row">
          <div class="text-center col small" style="color: #ff6b01;">
            &copy; {{ date('Y') }} YHA Computer Training Center. All rights reserved.
          </div>
        </div>
      </div>
      <style>
        .edu-footer {
          background: linear-gradient(120deg, #181c24 60%, #23272f 100%);
          color: #eee;
          font-size: 1rem;
          margin-top: 0;
        }
        .footer-brand img { height: 48px; }
        .footer-desc { color: #bbb; font-size: 1rem; margin-bottom: 0.5rem; }
        .footer-title { color: #ff6b01; font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; }
        .footer-links, .footer-contact { list-style: none; padding: 0; margin: 0; }
        .footer-links li, .footer-contact li { margin-bottom: 0.5rem; }
        .footer-links a { color: #eee; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #ff6b01; }
        .footer-contact i { color: #ff6b01; margin-right: 0.5em; }
        .footer-social {
          display: flex;
          gap: 0.7em;
          justify-content: center;
          margin-bottom: 0.5em;
        }
        .footer-social a {
          display: flex; align-items: center; justify-content: center;
          color: #fff; background: #23272f; border-radius: 50%;
          width: 36px; height: 36px; font-size: 1.2rem;
          transition: background 0.2s, color 0.2s;
        }
        .footer-social a:hover { background: #ff6b01; color: #23272f; }
        .footer-cta {
          display: inline-block; background: #ff6b01; color: #fff; padding: 0.5em 1.2em;
          border-radius: 20px; font-weight: 600; text-decoration: none; transition: background 0.2s, color 0.2s;
          margin-top: 0.5em;
        }
        .footer-cta:hover { background: #fff; color: #ff6b01; }
        .footer-divider { border-color: #333; }
        .footer-links, .footer-contact, .footer-title, .footer-cta { width: 100%; }
        @media (max-width: 991.98px) {
          .edu-footer .row > div { margin-bottom: 1.5rem; }
          .footer-brand img { height: 40px; }
          .footer-social { justify-content: center; }
          .footer-title, .footer-links, .footer-contact, .footer-cta { text-align: center !important; }
        }
        @media (max-width: 576px) {
          .footer-title { font-size: 1rem; }
          .footer-links a, .footer-contact li { font-size: 0.98rem; }
          .footer-cta { font-size: 0.98rem; }
        }
      </style>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/setinterval.js') }}"></script>
    <script src="{{ asset('js/f_course_menu.js') }}"></script>
</body>

</html>
