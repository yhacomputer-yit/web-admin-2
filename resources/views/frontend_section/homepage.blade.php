@extends('layout.front_layout')

@section('content')

<style>
  /* Event and Course Item Styles */
.course-item,
.event-item {
    transition: opacity 0.5s ease, transform 0.5s ease;
    opacity: 1;
    transform: translateY(0);
}

.hidden-course,
.hidden-event {
    display: none !important;
    opacity: 0;
    transform: translateY(20px);
}

/* Event Card Hover Effect */
#event .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#event .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Clamped Text for Descriptions */
.clamped-text {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Ensure consistent button styling */
#event .btn-primary,
#course .btn-primary
#project .btn1{
    background-color: #ff6c0f;
    border: none;
    transition: background-color 0.3s ease;
}

#event .btn-primary:hover,
#course .btn-primary:hover,
#project .btn1:hover {
    background-color: #e65b00;
}

/* --- Modern Glass Hero/Slider --- */
.hero-slider {
  position: relative;
  min-height: 320px;
  max-height: 600px;
  overflow: hidden;
  border-radius: 24px;
  box-shadow: 0 8px 32px 0 rgba(0,0,0,0.13), 0 1.5px 8px 0 rgba(255,107,1,0.08);
  margin-bottom: 2.5rem;
  margin-top: 1.2rem;
}
.hero-slider .carousel-inner {
  border-radius: 24px;
  overflow: hidden;
}
.hero-slider .carousel-item img {
  object-fit: cover;
  width: 100%;
  height: 420px;
  filter: brightness(0.85) saturate(1.1);
  border-radius: 24px;
  transition: height 0.3s;
}
.hero-glass-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(255,255,255,0.32);
  backdrop-filter: blur(1px);
  border-radius: 24px;
  z-index: 2;
  pointer-events: none;
}
/* Desktop (≥1200px) */
.hero-caption {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  max-width: 80%;
  left: 10%;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.72);
  border-radius: 18px;
  box-shadow: 0 4px 24px rgba(255,107,1,0.10);
  padding: 2.2rem 2.2rem 2rem 2.2rem;
  border-left: 6px solid #ff6b01;
  border-bottom: 2px solid #ffb347;
  position: absolute;
  z-index: 3;
}
.hero-caption .hero-logo {
  height: 48px;
  margin-bottom: 10px;
}
.hero-caption h1 {
  font-size: 2.2rem;
  font-weight: 800;
  margin-bottom: 1.1rem;
  letter-spacing: 1.5px;
}
.hero-caption p {
  font-size: 1.18rem;
  margin-bottom: 1.5rem;
  font-weight: 400;
  line-height: 1.6;
}
.hero-caption .hero-btn {
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  color: #fff;
  font-weight: 600;
  font-size: 1.1rem;
  border: none;
  border-radius: 22px;
  padding: 0.7rem 2.2rem;
  box-shadow: 0 2px 8px rgba(255,107,1,0.13);
  transition: background 0.2s, box-shadow 0.2s;
  text-decoration: none;
  display: inline-block;
}
.hero-caption .hero-btn:hover {
  background: #e65b00;
  color: #fff;
  box-shadow: 0 4px 16px rgba(255,107,1,0.18);
}

/* Tablet (768px ~ 1200px) */
@media (max-width: 1200px) and (min-width: 768px) {
  .hero-slider {
    min-height: 220px;
  }
  .hero-slider .carousel-item img {
    height: 220px;
    border-radius: 14px;
  }
  .hero-caption {
    max-width: 95vw;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    padding: 1.2rem 1rem 1rem 1rem;
    border-radius: 14px;
    align-items: center;
    text-align: center;
  }
  .hero-caption .hero-logo { height: 36px; }
  .hero-caption h1 { font-size: 1.3rem; }
  .hero-caption p { font-size: 1rem; }
  .hero-caption .hero-btn { font-size: 1rem; padding: 0.5rem 1.2rem; }
}

/* Phone (<768px) */
@media (max-width: 767.98px) {
  .hero-slider, .hero-slider .carousel-item img {
    min-height: 140px;
    height: 160px;
    border-radius: 8px;
  }
  .hero-glass-overlay, .hero-slider .carousel-inner {
    border-radius: 8px;
  }
  .hero-caption {
    max-width: 98vw;
    left: 50%;
    top: 10%;
    transform: translate(-50%, 0);
    padding: 0.5rem 0.2rem 0.7rem 0.2rem;
    border-radius: 7px;
    background: rgba(255,255,255,0.97);
    box-shadow: 0 2px 8px rgba(255,107,1,0.10);
    align-items: center;
    text-align: center;
  }
  .hero-caption .hero-logo { height: 22px; margin-bottom: 6px; }
  .hero-caption h1 { font-size: 1rem; margin-bottom: 0.5rem; word-break: break-word; }
  .hero-caption p { font-size: 0.8rem; margin-bottom: 0.7rem; word-break: break-word; }
  .hero-caption .hero-btn { font-size: 0.85rem; padding: 0.3rem 0.7rem; }
}
@media (max-width: 576px) {
  .hero-slider, .hero-slider .carousel-item img {
    min-height: 100px;
    height: 120px;
    border-radius: 6px;
  }
  .hero-glass-overlay, .hero-slider .carousel-inner {
    border-radius: 6px;
  }
  .hero-caption {
    padding: 0.3rem 0.1rem 0.5rem 0.1rem;
    border-radius: 5px;
    max-width: 99vw;
    left: 50%;
    top: 12%;
    transform: translate(-50%, 0);
  }
  .hero-caption h1 {
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
    word-break: break-word;
  }
  .hero-caption p {
    font-size: 0.70rem;
    margin-bottom: 0.5rem;
    word-break: break-word;
  }
  .hero-caption .hero-btn {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
  }
  .hero-caption .hero-logo {
    height: 16px;
    margin-bottom: 4px;
  }
}
.carousel-control-prev, .carousel-control-next {
  z-index: 4;
  filter: drop-shadow(0 2px 8px rgba(0,0,0,0.13));
}
.carousel-indicators [data-bs-target] {
  background: #222;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  opacity: 0.7;
  margin: 0 6px;
  transition: opacity 0.2s, background 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(255,107,1,0.08);
  display: inline-block;
  vertical-align: middle;
}
@media (max-width: 991.98px) {
  .hero-slider, .hero-slider .carousel-item img {
    min-height: 260px;
    height: 260px;
    border-radius: 14px;
  }
  .hero-glass-overlay, .hero-slider .carousel-inner {
    border-radius: 14px;
  }
  .hero-caption {
    padding: 1.2rem 1.1rem 1.1rem 1.1rem;
    max-width: 95vw;
    left: 3%;
    border-radius: 12px;
  }
  .hero-caption h1 {
    font-size: 1.5rem;
  }
  .hero-caption p {
    font-size: 1rem;
  }
  .hero-caption .hero-btn {
    font-size: 1rem;
    padding: 0.5rem 1.2rem;
  }
  .hero-caption {
    align-items: flex-end;
    text-align: right;
  }
  .hero-caption .hero-logo {
    height: 36px;
  }
}
@media (max-width: 576px) {
  .hero-slider, .hero-slider .carousel-item img {
    min-height: 200px;
    height: 300px;
    border-radius: 8px;
  }
  .hero-glass-overlay, .hero-slider .carousel-inner {
    border-radius: 8px;
  }
  .hero-caption {
    align-items: center;
    text-align: center;
    padding: 0.3rem 0.2rem 0.5rem 0.2rem;
    max-width: 98vw;
    left: 50%;
    top: 18%;
    transform: translate(-50%, 0);
    border-radius: 7px;
    background: rgba(255,255,255,0.97);
    box-shadow: 0 2px 8px rgba(255,107,1,0.10);
    overflow-wrap: break-word;
  }
  .hero-caption h1 {
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    word-break: break-word;
  }
  .hero-caption p {
    font-size: 0.80rem;
    margin-bottom: 0.7rem;
    word-break: break-word;
  }
  .hero-caption .hero-btn {
    font-size: 0.85rem;
    padding: 0.3rem 0.7rem;
  }
  .hero-caption .hero-logo {
    height: 22px;
    margin-bottom: 6px;
  }
}
@media (max-width: 1200px) and (min-width: 768px) {
  .hero-caption {
    max-width: 90vw;
    padding: 1rem 1.2rem 1.2rem 1.2rem;
    left: 50%;
    top: 18%;
    transform: translate(-50%, 0);
    font-size: 0.5rem;
    background: rgba(255,255,255,0.97);
    box-shadow: 0 2px 8px rgba(255,107,1,0.10);
    overflow-wrap: break-word;
  }
  .hero-caption h1 {
    font-size: 1rem;
    margin-bottom: 0.7rem;
    word-break: break-word;
  }
  .hero-caption p {
    font-size: 0.5rem;
    margin-bottom: 1rem;
    word-break: break-word;
  }
  .hero-caption .hero-btn {
    font-size: 0.5rem;
    padding: 0.5rem 1.2rem;
  }
  .hero-caption .hero-logo {
    height: 32px;
    margin-bottom: 8px;
  }
}
.glass-heading {
  background: rgba(255, 255, 255, 0.18);
  border-radius: 16px;
  box-shadow: 0 2px 16px rgba(255,107,1,0.10);
  border: 1px solid rgba(255,255,255,0.22);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 1.1rem 1.5rem 1.1rem 1.5rem;
  margin-bottom: 1.2rem;
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
}

.glass-heading h3, .glass-heading h1 {
  margin: 0;
  font-size: 2rem;
  font-weight: 800;
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  letter-spacing: 1.5px;
  font-family: 'DM Serif Text', serif;
  line-height: 1.2;
  text-shadow: 0 2px 8px rgba(255,107,1,0.08);
}

@media (max-width: 1200px) {
  .glass-heading {
    padding: 0.7rem 1rem 0.7rem 1rem;
    border-radius: 12px;
    max-width: 95vw;
  }
  .glass-heading h3, .glass-heading h1 {
    font-size: 1.3rem;
  }
}
@media (max-width: 767.98px) {
  .glass-heading {
    padding: 0.5rem 0.5rem 0.5rem 0.5rem;
    border-radius: 8px;
    max-width: 99vw;
  }
  .glass-heading h3, .glass-heading h1 {
    font-size: 1rem;
  }
}

.edu-glass-heading {
  background: linear-gradient(120deg, rgba(255,255,255,0.22) 60%, rgba(255, 236, 210, 0.18) 100%);
  border-radius: 20px;
  box-shadow: none !important;
  border: 2px solid rgba(255, 180, 71, 0.18);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: 1.5rem 2.2rem 1.3rem 2.2rem;
  margin-bottom: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 750px;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.edu-glass-heading .edu-icon {
  font-size: 2.5rem;
  color: #ff6b01;
  margin-bottom: 0.5rem;
  filter: drop-shadow(0 2px 8px rgba(255,107,1,0.10));
}

.edu-glass-heading h3, .edu-glass-heading h1 {
  margin: 0;
  font-size: 2.3rem;
  font-weight: 900;
  font-family: 'DM Serif Text', serif;
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  letter-spacing: 1.5px;
  line-height: 1.15;
  text-shadow: 0 2px 8px rgba(255,107,1,0.08);
}

.edu-glass-heading .edu-subtitle {
  margin-top: 0.7rem;
  font-size: 1.1rem;
  color: #444;
  font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
  font-weight: 400;
  letter-spacing: 0.2px;
  opacity: 0.92;
}

@media (max-width: 1200px) {
  .edu-glass-heading {
    padding: 1rem 1rem 1rem 1rem;
    border-radius: 14px;
    max-width: 98vw;
    box-shadow: none !important;
  }
  .edu-glass-heading h3, .edu-glass-heading h1 {
    font-size: 1.4rem;
  }
}
@media (max-width: 767.98px) {
  .edu-glass-heading {
    padding: 0.7rem 0.5rem 0.7rem 0.5rem;
    border-radius: 8px;
    max-width: 99vw;
    box-shadow: none !important;
  }
  .edu-glass-heading h3, .edu-glass-heading h1 {
    font-size: 1.1rem;
  }
  .edu-glass-heading .edu-icon {
    font-size: 1.5rem;
  }
  .edu-glass-heading .edu-subtitle {
    font-size: 0.95rem;
  }
}
#about {
  margin-top: 0.5rem !important;
  padding-top: 0 !important;
}
.edu-glass-heading {
  margin-bottom: 1.2rem;
  margin-top: 0.5rem;
}
.edu-section {
  margin-top: 2rem;
  margin-bottom: 2.5rem;
}
.edu-section-title {
  font-size: 2rem;
  font-weight: 800;
  color: #ff6b01;
  margin-top: 6rem;
  margin-bottom: 1.5rem;
  letter-spacing: 1px;
  display: flex;
  align-items: center;
  gap: 0.7rem;
}
.edu-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.07);
  overflow: hidden;
  transition: box-shadow 0.2s, transform 0.2s;
  display: flex;
  flex-direction: column;
  height: 100%;
}
.edu-card:hover {
  box-shadow: 0 6px 32px rgba(255,107,1,0.13);
  transform: translateY(-4px) scale(1.02);
}
.edu-card-img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}
.edu-card-body {
  padding: 1.1rem 1.2rem 0.7rem 1.2rem;
  flex: 1 1 auto;
}
.edu-card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}
.edu-card-desc {
  font-size: 1rem;
  color: #555;
  margin-bottom: 1rem;
  min-height: 48px;
}
.edu-card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
}
.edu-card-price {
  font-weight: 600;
  color: #ff6b01;
}
.edu-btn {
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  color: #fff;
  font-weight: 600;
  border: none;
  border-radius: 22px;
  padding: 0.5rem 1.5rem;
  box-shadow: 0 2px 8px rgba(255,107,1,0.13);
  transition: background 0.2s, box-shadow 0.2s;
  text-decoration: none;
  display: inline-block;
}
.edu-btn:hover {
  background: #e65b00;
  color: #fff;
  box-shadow: 0 4px 16px rgba(255,107,1,0.18);
}

/* Event and Project Specific Styles */
.event-card, .project-card {
  position: relative;
  overflow: hidden;
}

.event-card::before, .project-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  z-index: 1;
}

.event-date, .project-course {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.project-links {
  margin-top: auto;
}

.project-link {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 500;
  transition: all 0.3s ease;
  border: 1px solid transparent;
}

.github-link {
  background: #24292e;
  color: #fff;
}

.github-link:hover {
  background: #2f363d;
  color: #fff;
  transform: translateY(-2px);
}

.demo-link {
  background: #ff6b01;
  color: #fff;
}

.demo-link:hover {
  background: #e65b00;
  color: #fff;
  transform: translateY(-2px);
}

/* Enhanced card hover effects */
.event-card:hover .edu-card-img,
.project-card:hover .edu-card-img {
  transform: scale(1.05);
}

.edu-card-img {
  transition: transform 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
  .project-links {
    flex-direction: column;
    gap: 0.5rem;
  }

  .project-link {
    width: 100%;
    justify-content: center;
  }
}

/* Ensure project links are always visible */
.project-links {
  margin-top: auto;
  min-height: 40px;
}

.project-link.disabled {
  pointer-events: none;
}

.project-link.disabled:hover {
  transform: none;
}
  @media (max-width: 767.98px) {
    #course {
      margin-top: 2rem !important;
    }
  }
/* Force Tawk.to widget/button to always float at bottom right */
.tawk-min-container, .tawk-button {
  position: fixed !important;
  right: 24px !important;
  bottom: 24px !important;
  z-index: 2147483647 !important;
  display: block !important;
}

/* From Uiverse.io by SujitAdroja */
.btn-uiverse {
    color: var(--maincolor);
    text-transform: uppercase;
    text-decoration: none;
    border: 2px solid var(--maincolor);
    padding: 10px 20px;
    font-size: 17px;
    cursor: pointer;
    font-weight: bold;
    background: transparent;
    position: relative;
    transition: all 1s;
    overflow: hidden;
    display: inline-block;
    border-radius: 30px;
  }
  .btn-uiverse:hover {
    color: white;
  }
  .btn-uiverse::before {
    content: "";
    position: absolute;
    height: 100%;
    width: 0%;
    top: 0;
    left: -40px;
    transform: skewX(45deg);
    background-color: var(--maincolor);
    z-index: -1;
    transition: all 1s;
  }
  .btn-uiverse:hover::before {
    width: 160%;
  }
.typewriter-heading {
  width: 100%;
  max-width: 100%;
  margin: 0 auto 1.5rem auto;
  padding: 0;
  border-radius: 0;
  box-shadow: none;
  position: relative;
  text-align: center;
  font-weight: 900;
  font-family: 'DM Serif Text', serif;
  letter-spacing: 1.2px;
  overflow: visible;
}
.typewriter-heading .typewriter-inner {
  max-width: 900px;
  margin: 0 auto;
  padding: 2.2rem 1.5rem 2rem 1.5rem;
  border-radius: 0;
  position: relative;
  z-index: 1;
  background: none;
}
.typewriter-heading:before {
  display: none;
}
#typewriter-text {
  background-image: linear-gradient(90deg, var(--maincolor) 0%, #ffb347 100%);
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
  -webkit-text-fill-color: transparent;
  text-fill-color: transparent;
  animation: textclip 2s linear infinite;
  background-size: 200% auto;
  font-size: 2.2rem;
  font-weight: 900;
  letter-spacing: 1.2px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80px;   /* or 150px if you want it tall */
  line-height: 1.2;
}
@keyframes textclip {
  to {
    background-position: 200% center;
  }
}
@media (max-width: 991.98px) {
  .typewriter-heading { max-width: 98vw; font-size: 1.1rem; padding: 1.1rem 0.7rem; border-radius: 16px; }
  #typewriter-text { font-size: 1.3rem; }
}
@media (max-width: 767.98px) {
  .typewriter-heading { max-width: 99vw; font-size: 1rem; padding: 0.7rem 0.2rem; border-radius: 8px; }
  #typewriter-text { font-size: 1rem; }
}

<!-- Typewriter Animated Gradient Heading -->
<div id="app"></div>
<style>
#app {
  text-align: center;
  font-size: 50px;
  font-weight: 900;
  /* Remove background color or image below, keep only gradient for text */
  background: none;
  background-image: none;
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
  -webkit-text-fill-color: transparent;
  text-fill-color: transparent;
  animation: textclip 2s linear infinite;
  background-size: 200% auto;
  letter-spacing: 2px;
  margin-bottom: 2rem;
}
@keyframes textclip {
  to {
    background-position: 200% center;
  }
}
@media (max-width: 767.98px) {
  #app { font-size: 28px; }
}
</style>
<script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
<script>
  var app = document.getElementById('app');
  var typewriter = new Typewriter(app, {
      loop: true,
      delay: 70,
      deleteSpeed: 40,
  });
  typewriter.typeString('Web Developer')
      .pauseFor(2000)
      .deleteAll()
      .typeString('Web Designer')
      .pauseFor(2000)
      .deleteAll()
      .typeString('<strong>FrontEnd Developer</strong>')
      .pauseFor(2000)
      .deleteAll()
      .typeString('<span style="color:#ff1361;">Creative Coder</span>')
      .pauseFor(2000)
      .deleteAll()
      .start();
</script>

</style>


<section id="home">
  <div class="container">
    <div id="carouselExample" class="carousel slide hero-slider" data-bs-ride="carousel">
      <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
          <?php $slide = asset('storage/' . $slider->image); ?>
          <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <img src="{{  $slide }}" class="d-block w-100" alt="...">
          </div>
        @endforeach
      </div>
      <div class="hero-glass-overlay"></div>
      <div class="hero-caption">
        <img class="hero-logo" src="{{ asset('image/logo/logo.png') }}" alt="YHA Logo">
        <h1>Build Your Future With <span style="white-space:nowrap;">Technology</span></h1>
        <p>Join YHA Computer Training Center to learn programming, design, and ICT skills from the best instructors. Start your journey today!</p>
        <a href="{{ route('user.courses') }}" class="btn-uiverse">Explore Courses</a>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

  </div>
</section>


<section id="about1">
  <div class="container">
    <div class="mb-4">
      <h3 class="typewriter-heading">
        <span id="typewriter-text"></span>
      </h3>
    </div>
    <div class="flex-wrap row justify-content-center align-items-center g-5 flex-lg-nowrap">
      @if($abouts->isNotEmpty())
      <div class="col-lg-6 d-flex justify-content-center">
        <div class="about-imgs-flex">
          @php $firstThreeAbouts = $abouts->take(3); @endphp
          @foreach($firstThreeAbouts as $index => $about)
            @php $imagePath = asset('storage/' . $about->image); @endphp
            <div class="about-img-card-modern">
              <img src="{{ $imagePath }}" alt="About image {{ $index+1 }}">
            </div>
          @endforeach
        </div>
      </div>
      @endif

      @if($aboutDesc->isNotEmpty())
      @php $aboutdesc = $aboutDesc->first(); @endphp
      <div class="col-lg-6 d-flex justify-content-center">
        <div class="about-text-modern glass-effect">
          <h2 class="mb-3">Welcome to YHA Programming Training Center!</h2>
          <div class="section">{!! $aboutdesc->desc !!}</div>
        </div>
      </div>
      @endif
    </div>
  </div>
  <style>
    .about-imgs-flex {
      display: flex;
      flex-direction: row;
      gap: 1.5rem;
      justify-content: center;
      align-items: center;
      width: 100%;
    }
    .about-img-card-modern {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(255,107,1,0.10);
      overflow: hidden;
      width: 170px;
      height: 220px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.2s, box-shadow 0.2s;
      position: relative;
    }
    .about-img-card-modern img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 18px;
      transition: transform 0.3s;
    }
    .about-img-card-modern:hover {
      transform: translateY(-8px) scale(1.04);
      box-shadow: 0 8px 32px rgba(255,107,1,0.18);
      z-index: 2;
    }
    .about-img-card-modern:hover img {
      transform: scale(1.07);
    }
    .about-text-modern {
      background: rgba(255,255,255,0.92);
      border-radius: 22px;
      box-shadow: 0 8px 32px rgba(255,107,1,0.10);
      padding: 2.5rem 2.2rem 2.2rem 2.2rem;
      max-width: 520px;
      width: 100%;
      margin: 0 auto;
      font-size: 1.13rem;
      color: #333;
      line-height: 1.7;
      font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    }
    .glass-effect {
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      border: 1.5px solid #ffe5d0;
    }
    @media (max-width: 991.98px) {
      .about-imgs-flex {
        gap: 1rem;
      }
      .about-img-card-modern {
        width: 120px;
        height: 150px;
        border-radius: 12px;
      }
      .about-img-card-modern img {
        border-radius: 12px;
      }
      .about-text-modern {
        padding: 1.2rem 0.7rem 1rem 0.7rem;
        border-radius: 14px;
        font-size: 1rem;
      }
    }
    @media (max-width: 767.98px) {
      .about-imgs-flex {
        flex-direction: row;
        gap: 0.5rem;
      }
      .about-img-card-modern {
        width: 80px;
        height: 80px;
        border-radius: 8px;
      }
      .about-img-card-modern img {
        border-radius: 8px;
      }
      .about-text-modern {
        padding: 0.7rem 0.3rem 0.7rem 0.3rem;
        border-radius: 8px;
        font-size: 0.97rem;
      }
    }
    @media (max-width: 575.98px) {
      .about-imgs-flex {
        gap: 0.3rem;
      }
      .about-img-card-modern {
        width: 60px;
        height: 60px;
      }
      .about-text-modern {
        padding: 0.5rem 0.1rem 0.5rem 0.1rem;
      }
    }
  </style>
</section>

    <section id="stu_number">
      <div class="container">
        <h5 class="hidden">Lorem ipsum dolor, sit amet consectetur adipisicing.</h5>
        <div class="row">
          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 justify-content-end">
            <div class="card hidden1">
              <span>YHA</span>
              <h3 id="number" data-goal="20">0</h3>
              <p>courses</p>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 justify-content-center">
            <div class="card hidden2">
              <span>YHA</span>
              <div class="d-flex">
                <h3 > 1k+</h3>
              </div>
              <p>Students</p>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 justify-content-start">
            <div class="card hidden3">
              <span>YHA</span>
              <div class="d-flex">
                <h3 id="number" data-goal="50">0</h3> <h3> +</h3>
              </div>
              <p>Batches</p>
            </div>
          </div>

        </div>
      </div>

    </section>

    <!-- course section start -->

<section class="edu-section" id="course">
  <div class="container">
    <h2 class="edu-section-title"><i class="fa-solid fa-book-open"></i> Monthly Courses</h2>
    <div class="row g-4">
      @foreach ($monthies as $index => $monthly)
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
          <div class="edu-card">
            <img class="edu-card-img" src="{{ asset('storage/' . $monthly->m_img) }}" alt="">
            <div class="edu-card-body">
              <h5 class="edu-card-title">{{ $monthly->course->name }}</h5>
              <p class="edu-card-desc">{{ $monthly->course->description }}</p>
              <div class="edu-card-footer">
                <span class="edu-card-price"><span style="color: orangered;">Ks /-</span> {{ $monthly->course->normal_price }}</span>
                <a href="{{ route('course.monthly', $monthly->id) }}" class="btn-uiverse">View More</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
      @if($monthies->count() > 3)
        <div class="mt-4 text-center">
          <a href="{{route('user.courses')}}" class="btn-uiverse">View More</a>
        </div>
      @endif
    </div>
  </div>
</section>

    <!-- course section end -->


    <!-- event section start -->

  <section class="mt-5 mb-5" id="event">
    <div class="container">
        <div class="edu-glass-heading">
          <span class="edu-icon">
            <i class="fa-solid fa-calendar-days"></i>
          </span>
          <h3>Latest Events</h3>
          <div class="edu-subtitle">
            Stay updated with our latest workshops, seminars, and special events
          </div>
        </div>
        <div class="row w-100">
            @foreach ($events->take(3) as $index => $event)
                <div class="mb-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="edu-card event-card">
                        <img class="edu-card-img" src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                        <div class="edu-card-body">
                            <h6 class="edu-card-title">{{ $event->title }}</h6>
                            <div class="mb-2 event-date">
                                <i class="fa-solid fa-calendar-alt" style="color: #ff6b01;"></i>
                                <span style="font-size: 14px; color: #666;">{{ \Carbon\Carbon::parse($event->edate)->format('M d, Y') }}</span>
                            </div>
                            <p class="edu-card-desc clamped-text">{!! strip_tags($event->aboute) !!}</p>
                            <div class="edu-card-footer">
                                <a href="{{ route('user.eventDetail', $event->id) }}" class="btn-uiverse">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($events->count() > 3)
                <div class="mt-4 text-center">
                    <a href="{{route('user.event')}}" class="btn-uiverse">View All Events</a>
                </div>
            @endif
        </div>
    </div>
</section>

    <!-- event section end -->


    <!-- student project start -->
<section class="mt-5 mb-5" id="projects" role="region" aria-labelledby="projects-heading">
  <div class="container">
    <div class="edu-glass-heading">
      <span class="edu-icon">
        <i class="fa-solid fa-code"></i>
      </span>
      <h3>Student Projects</h3>
      <div class="edu-subtitle">
        Showcasing innovative projects created by our talented students
      </div>
    </div>
    <div class="m-auto w-100 row" id="projects-container">
      @foreach($projects->take(3) as $index => $project)
        <div class="mb-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="edu-card project-card">
            <img src="{{ asset('storage/' . $project->image) }}" class="edu-card-img" alt="{{ $project->title ?? 'Student project image' }}">
            <div class="edu-card-body">
              <h5 class="edu-card-title">{{ $project->title }}</h5>
              <div class="mb-2 project-course">
                <i class="fa-solid fa-graduation-cap" style="color: #ff6b01;"></i>
                <span style="color: #ff6b01; font-weight: 600;">{{ $project->course->name ?? 'Unknown Course' }}</span>
              </div>
              <p class="edu-card-desc clamped-text">{{ $project->desc }}</p>
              <div class="gap-2 mb-3 project-links d-flex justify-content-center">
                @if($project->github)
                  <a href="{{ $project->github }}" class="project-link github-link btn-uiverse" target="_blank" aria-label="View GitHub source for {{ $project->title }}">
                    <i class="fa-brands fa-github"></i>
                    <span>GitHub</span>
                  </a>
                @else
                  <span class="project-link github-link disabled" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fa-brands fa-github"></i>
                    <span>GitHub</span>
                  </span>
                @endif
                @if($project->demo)
                  <a href="{{ $project->demo }}" class="project-link demo-link btn-uiverse" target="_blank" aria-label="View live demo for {{ $project->title }}">
                    <i class="fa-solid fa-play"></i>
                    <span>Live Demo</span>
                  </a>
                @else
                  <span class="project-link demo-link disabled" style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fa-solid fa-play"></i>
                    <span>Live Demo</span>
                  </span>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
      @if($projects->count() > 3)
        <div class="mt-4 text-center">
          <a href="{{ route('user.project') }}" class="btn-uiverse" aria-label="View more projects">View All Projects</a>
        </div>
      @endif
    </div>
  </div>
</section>

    <!-- student project end -->

    {{-- <section id="teacher">
      <div class="container">
        <div class="header">
          <h2 style="text-align: center;">Teacher section</h2>
        </div>
        <div class="row">
          @if($teacher->isNotEmpty())
          <div class="row">
              @foreach($teacher->take(3) as $singleTeacher)
                  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                      <div class="cards hidden1">
                          <div class="img">
                              <img src="{{ asset('storage/'.$singleTeacher->image) }}" alt="">
                          </div>
                          <div class="text">
                              <h4 style="text-align: center;">{{ $singleTeacher->name }}</h4>
                              <span> Age: <span style="color: #ff6f00">{{ $singleTeacher->age }}</span> </span>
                              <span> Position: <span style="color: #ff6f00">{{ $singleTeacher->position->name }}</span> </span>
                          </div>
                      </div>
                  </div>
              @endforeach
          </div>
      @endif

        </div>
        <div class="footer">
          <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos, cumque expedita? Fugiat perferendis dolore ipsa omnis, magni harum libero fugit ex aperiam, ut vitae.</p>
          <!-- <button id="btn1"><a href="#">About our teachers</a></button> -->
        </div>
      </div>
    </section> --}}
{{-- ================= FAQ SECTION ================= --}}
<section id="faq" class="py-5" style="background: #f9f9f9; border-top: 2px solid #f3f3f3;">
    <div class="container">
      <div class="mb-4 edu-glass-heading">
        <span class="edu-icon"><i class="fa-solid fa-question-circle"></i></span>
        <h3>Frequently Asked Questions</h3>
        <div class="edu-subtitle">Find answers to common questions about our courses and center</div>
      </div>
      <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq1">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
              What courses does YHA offer?
            </button>
          </h2>
          <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              We offer a wide range of courses including programming, web development, graphic design, and ICT skills for all levels.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
              How can I enroll in a course?
            </button>
          </h2>
          <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              You can enroll online through our website or visit our center for in-person registration. For online, just click on the "Explore Courses" button and follow the instructions.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
              Are there any prerequisites for joining?
            </button>
          </h2>
          <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Most beginner courses require no prior experience. Advanced courses may have prerequisites, which are mentioned in the course details.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq4">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
              Do you provide certificates?
            </button>
          </h2>
          <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Yes, we provide certificates upon successful completion of each course.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq5">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
              How can I contact support?
            </button>
          </h2>
          <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              You can contact us via phone, email, or visit our center. Details are available in the Contact section above.
            </div>
          </div>
        </div>
      </div>
    </div>
    <style>
      #faq .accordion-button { font-weight: 600; color: #ff6b01; background: #fff7f0; }
      #faq .accordion-button:not(.collapsed) { background: #ffe0c2; color: #e65b00; }
      #faq .accordion-body { background: #fff; color: #333; }
      #faq .accordion-item { border-radius: 12px; margin-bottom: 0.7rem; box-shadow: 0 2px 8px rgba(255,107,1,0.07); border: 1px solid #ffe5d0; }
      #faq .accordion-item:last-child { margin-bottom: 0; }
    </style>
  </section>

  {{-- ================= REVIEW SECTION ================= --}}
  <section id="reviews" class="py-5 review-section-modern position-relative">
    <div class="container review-section-modern-container">
      <div class="mb-5 text-center review-modern-heading">
        <span class="review-modern-icon"><i class="fa-solid fa-star"></i></span>
        <h2 class="review-modern-title">Student Reviews</h2>
        <div class="review-modern-accent"></div>
        <div class="review-modern-subtitle">Real voices. Real experiences. See what our students say!</div>
      </div>
      <div id="reviewSlider" class="carousel slide review-modern-carousel" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
          @foreach($homeReviews as $index => $review)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <div class="d-flex justify-content-center">
                <div class="text-center review-modern-card">
                  <div class="review-modern-avatar-wrap">
                    @if($review->photo)
                      <img src="{{ asset('storage/'.$review->photo) }}" class="review-modern-avatar" alt="{{ $review->name }}">
                    @else
                      <img src="https://ui-avatars.com/api/?name={{ urlencode($review->name) }}&background=ffb347&color=fff" class="review-modern-avatar" alt="{{ $review->name }}">
                    @endif
                  </div>
                  <div class="review-modern-quote"><i class="fa-solid fa-quote-left"></i></div>
                  <div class="review-modern-text">{{ $review->review }}</div>
                  <div class="gap-2 mt-3 mb-1 d-flex align-items-center justify-content-center">
                    <span class="review-modern-name">{{ $review->name }}</span>
                    <span class="review-modern-stars">
                      @for($i=1; $i<=5; $i++)
                        @if($i <= $review->rating)
                          <i class="fa-solid fa-star"></i>
                        @else
                          <i class="fa-regular fa-star"></i>
                        @endif
                      @endfor
                    </span>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <button class="carousel-control-prev review-modern-arrow" type="button" data-bs-target="#reviewSlider" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next review-modern-arrow" type="button" data-bs-target="#reviewSlider" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
        <div class="mt-4 carousel-indicators">
          @foreach($homeReviews as $index => $review)
            <button type="button" data-bs-target="#reviewSlider" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index+1 }}"></button>
          @endforeach
        </div>
      </div>
      <div class="mt-4 text-center">
        <a href="{{ route('reviews.index') }}" class="btn-uiverse">View All Reviews</a>
      </div>
    </div>
    <style>
      .review-section-modern {
        background: #fff;
        position: relative;
        z-index: 1;
        padding-top: 4.5rem !important;
        padding-bottom: 4.5rem !important;
      }
      .review-section-modern-container {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
      }
      .review-modern-heading {
        margin-bottom: 2.5rem;
        position: relative;
      }
      .review-modern-icon {
        font-size: 2.2rem;
        color: #ffb347;
        display: block;
        margin-bottom: 0.5rem;
      }
      .review-modern-title {
        font-size: 2.3rem;
        font-weight: 900;
        color: #222;
        margin-bottom: 0.2rem;
        letter-spacing: 1.5px;
        font-family: 'DM Serif Text', serif;
        background: none;
        text-shadow: none;
      }
      .review-modern-accent {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
        border-radius: 2px;
        margin: 0.5rem auto 1.1rem auto;
        opacity: 0.7;
      }
      .review-modern-subtitle {
        font-size: 1.13rem;
        color: #ff6b01;
        font-weight: 600;
        background: none;
        border-radius: 0;
        padding: 0;
        margin-top: 0;
        box-shadow: none;
        display: block;
        text-shadow: none;
      }
      .review-modern-card {
        background: rgba(255,255,255,0.82);
        border-radius: 32px;
        box-shadow: 0 8px 32px 0 rgba(255,107,1,0.10), 0 1.5px 8px 0 rgba(255,107,1,0.08);
        border: 1.5px solid #ffe5d0;
        padding: 3.2rem 2.2rem 2.2rem 2.2rem;
        margin: 1.5rem 0;
        min-width: 320px;
        max-width: 480px;
        width: 100%;
        transition: box-shadow 0.22s, transform 0.18s;
        overflow: visible;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
      }
      .review-modern-card:hover {
        box-shadow: 0 16px 48px rgba(255,107,1,0.18);
        transform: translateY(-6px) scale(1.03);
      }
      .review-modern-avatar-wrap {
        margin-top: -56px;
        margin-bottom: 1.2rem;
        background: #fff7f0;
        border-radius: 50%;
        padding: 6px;
        box-shadow: 0 4px 16px rgba(255,107,1,0.13);
        display: inline-block;
        border: 3px solid #fff;
        position: relative;
      }
      .review-modern-avatar-wrap::before {
        content: '';
        position: absolute;
        top: -8px; left: -8px; right: -8px; bottom: -8px;
        border-radius: 50%;
        background: radial-gradient(circle, #ffb34733 0%, #ff6b0133 100%);
        filter: blur(8px);
        z-index: 0;
        opacity: 0.7;
      }
      .review-modern-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2.5px solid #ffb347;
        background: #fff;
        display: block;
        box-shadow: 0 2px 8px rgba(255,107,1,0.10);
        position: relative;
        z-index: 1;
      }
      .review-modern-quote {
        color: #ffb347;
        font-size: 2rem;
        opacity: 0.13;
        margin-bottom: 0.7rem;
      }
      .review-modern-text {
        font-size: 1.18rem;
        color: #1a1a1a;
        min-height: 48px;
        font-style: italic;
        margin-bottom: 1.1rem;
        margin-top: 0.7rem;
        line-height: 1.7;
        letter-spacing: 0.01em;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        font-weight: 500;
        text-shadow: none;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .review-modern-name {
        font-size: 1.08rem;
        font-weight: 700;
        color: #ff6b01;
        letter-spacing: 0.2px;
        font-family: 'DM Serif Text', serif;
        text-shadow: none;
      }
      .review-modern-stars {
        font-size: 1.08rem;
        letter-spacing: 0.1em;
        color: #ffb347;
        margin-left: 0.5rem;
      }
      .review-modern-stars i { color: #ffb347; }
      .review-modern-arrow {
        filter: drop-shadow(0 2px 8px rgba(255,107,1,0.10));
        opacity: 0.7;
        transition: opacity 0.2s;
      }
      .review-modern-arrow:hover { opacity: 1; }
      .carousel-indicators [data-bs-target] {
        background: #222;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        opacity: 0.7;
        margin: 0 6px;
        transition: opacity 0.2s, background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(255,107,1,0.08);
        display: inline-block;
        vertical-align: middle;
      }
      .carousel-indicators .active {
        background: #ff6b01;
        opacity: 1;
        box-shadow: 0 0 0 3px #ffe5d0;
      }
      @media (max-width: 991.98px) {
        .review-section-modern-container { max-width: 98vw; }
        .review-modern-card { min-height: 180px; padding: 2rem 1rem 1.2rem 1rem; max-width: 98vw; }
        .review-modern-avatar { width: 56px; height: 56px; }
        .review-modern-avatar-wrap { margin-top: -36px; }
        .review-modern-title { font-size: 1.5rem; }
        .review-modern-subtitle { font-size: 1rem; }
      }
      @media (max-width: 767.98px) {
        .review-modern-card { min-height: 120px; padding: 1.1rem 0.3rem 0.7rem 0.3rem; border-radius: 16px; }
        .review-modern-avatar { width: 36px; height: 36px; }
        .review-modern-avatar-wrap { margin-top: -18px; }
        .review-modern-text { font-size: 1.01rem; }
        .review-modern-name { font-size: 1rem; }
        .review-modern-stars { font-size: 1rem; }
        .review-modern-title { font-size: 1.1rem; }
        .review-modern-subtitle { font-size: 0.95rem; }
      }
    </style>
  </section>

<!-- Review Form Modern Redesign -->
<div class="container mt-5 mb-4">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="p-4 mb-4 shadow-lg modern-review-form glassy-gradient">
        @csrf
        <div class="mb-4 form-accent-bar"></div>
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="form-floating">
              <input type="text" name="name" class="form-control" id="reviewerName" placeholder="Name" required value="{{ old('name') }}">
              <label for="reviewerName">Name</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating">
              <select name="rating" class="form-select" id="reviewRating" required>
                @for($i=5; $i>=1; $i--)
                  <option value="{{ $i }}" {{ old('rating', 5)==$i ? 'selected' : '' }}>{{ $i }} Star{{ $i>1 ? 's' : '' }}</option>
                @endfor
              </select>
              <label for="reviewRating">Rating</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating">
              <textarea name="review" class="form-control" id="reviewText" placeholder="Your Review" style="height: 100px" required>{{ old('review') }}</textarea>
              <label for="reviewText">Your Review</label>
            </div>
          </div>
          <div class="col-12">
            <div class="custom-file-input-wrap">
              <input type="file" name="photo" class="custom-file-input" id="reviewPhoto" accept="image/*">
              <label for="reviewPhoto" class="custom-file-label"><i class="fa-solid fa-image"></i> Add Photo (optional)</label>
              <span class="custom-file-preview" id="photoPreview"></span>
            </div>
          </div>
        </div>
        <button type="submit" class="mt-4 btn modern-review-btn w-100"><i class="fa-solid fa-paper-plane"></i> Submit Review</button>
      </form>
    </div>
  </div>
</div>
<!-- Review Form Modern Redesign CSS -->
<style>
.modern-review-form {
  background: rgba(255,255,255,0.85);
  border-radius: 22px;
  box-shadow: 0 8px 32px rgba(255,107,1,0.10);
  border: none;
  position: relative;
  padding: 2.5rem 2.2rem 2.2rem 2.2rem !important;
  margin-top: 2.5rem;
  margin-bottom: 1.2rem;
  overflow: visible;
  transition: box-shadow 0.3s, transform 0.3s;
  backdrop-filter: blur(8px);
  z-index: 2;
}
.form-accent-bar {
  width: 60px;
  height: 5px;
  background: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
  border-radius: 8px;
  margin-bottom: 1.2rem;
  margin-left: 0.2rem;
}
.form-floating > .form-control, .form-floating > .form-select {
  border-radius: 12px;
  border: 1.5px solid #ffe5d0;
  background: rgba(255,255,255,0.95);
  box-shadow: 0 2px 8px rgba(255,107,1,0.06);
  font-size: 1.08rem;
}
.form-floating > label {
  color: #ff6b01;
  font-weight: 500;
  letter-spacing: 0.2px;
}
.custom-file-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  gap: 1rem;
}
.custom-file-input {
  display: none;
}
.custom-file-label {
  display: inline-block;
  padding: 0.5rem 1.2rem;
  background: #fff7f0;
  color: #ff6b01;
  border-radius: 18px;
  border: 1.5px solid #ffe5d0;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}
.custom-file-label:hover {
  background: #ffb347;
  color: #fff;
}
.custom-file-preview {
  display: inline-block;
  vertical-align: middle;
  margin-left: 0.5rem;
  max-width: 48px;
  max-height: 48px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(255,107,1,0.10);
}
.modern-review-btn {
  background: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
  color: #fff;
  border: none;
  border-radius: 18px;
  font-weight: 700;
  font-size: 1.13rem;
  padding: 0.7rem 1.2rem;
  box-shadow: 0 2px 8px rgba(255,107,1,0.10);
  transition: background 0.2s, color 0.2s, transform 0.2s;
}
.modern-review-btn:hover {
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  color: #fff;
  transform: translateY(-2px) scale(1.03);
}
@media (max-width: 767.98px) {
  .modern-review-form { border-radius: 12px; padding: 1.2rem 0.7rem 1rem 0.7rem !important; margin-top: 1.2rem; }
  .form-accent-bar { width: 40px; height: 4px; }
}
</style>
<script>
// Custom file input preview
const reviewPhotoInput = document.getElementById('reviewPhoto');
const photoPreview = document.getElementById('photoPreview');
if (reviewPhotoInput && photoPreview) {
  reviewPhotoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(evt) {
        photoPreview.innerHTML = `<img src="${evt.target.result}" style="max-width:48px; max-height:48px; border-radius:10px;" alt="Preview">`;
      };
      reader.readAsDataURL(file);
    } else {
      photoPreview.innerHTML = '';
    }
  });
}
</script>

  {{-- ================= GO TO TOP BUTTON ================= --}}
  <button id="goToTopBtn" title="Go to top" style="display:none; position:fixed; bottom:32px; right:32px; z-index:9999; background:#ff6b01; color:#fff; border:none; border-radius:50%; width:48px; height:48px; box-shadow:0 2px 8px rgba(255,107,1,0.18); font-size:1.7rem; cursor:pointer; transition:background 0.2s;">
    <i class="fa-solid fa-arrow-up"></i>
  </button>

<section id="contact" class="py-5" style="background: #fff; border-top: 2px solid #f3f3f3;">
  @php $addr = $address->first() ?? null; @endphp
  <div class="container">
    <div class="row g-4 align-items-stretch">
      <div class="mb-4 col-lg-6 col-12">
        <div class="p-4 shadow-sm card h-100 contact-card-light" style="border-radius: 20px; background:#fff; box-shadow: 0 4px 24px rgba(255,107,1,0.07); border: 1.5px solid #ffe5d0;">
          <div class="mb-3 d-flex align-items-center" style="gap:0.7rem;">
            <span style="font-size:2rem; color:#ff6b01;"><i class="fa-solid fa-headset"></i></span>
            <h3 class="mb-0" style="font-weight:800; color:#222; letter-spacing:1px;">Contact Us</h3>
          </div>
          @if($addr)
            <div class="mb-3 d-flex align-items-center contact-info-light">
              <span class="contact-icon-light"><i class="fa-solid fa-location-dot"></i></span>
              <span class="contact-label-light">Address:</span>
              <span class="contact-value-light">{{ $addr->address ?? '-' }}</span>
            </div>
            <div class="mb-3 d-flex align-items-center contact-info-light">
              <span class="contact-icon-light"><i class="fa-solid fa-phone"></i></span>
              <span class="contact-label-light">Phone:</span>
              <span class="contact-value-light">{{ $addr->yphNo ?? '-' }}</span>
            </div>
            <div class="mb-3 d-flex align-items-center contact-info-light">
              <span class="contact-icon-light"><i class="fa-solid fa-envelope"></i></span>
              <span class="contact-label-light">Email:</span>
              <span class="contact-value-light">{{ $addr->yEmail ?? '-' }}</span>
            </div>
            <div class="mb-2 d-flex align-items-center contact-info-light">
              <span class="contact-icon-light"><i class="fa-solid fa-clock"></i></span>
              <span class="contact-label-light">Open Hours:</span>
              <span class="contact-value-light">{{ $addr->OpenClose ?? '-' }}</span>
            </div>
          @endif
        </div>
      </div>
      <div class="mb-4 col-lg-6 col-12">
        <div class="p-2 shadow-sm card h-100 map-card-light" style="border-radius: 20px; overflow:hidden; background:#fff; box-shadow: 0 4px 24px rgba(255,107,1,0.07); border: 1.5px solid #ffe5d0;">
          <div class="mb-2 d-flex align-items-center" style="gap:0.7rem;">
            <span style="font-size:1.5rem; color:#ff6b01;"><i class="fa-solid fa-map"></i></span>
            <h4 class="mb-0" style="font-weight:700; color:#222; letter-spacing:0.5px;">Our Location</h4>
          </div>
          <div class="map-responsive-light">
            <iframe src="{{ $addr->map_url ?? 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d238.68470692968666!2d96.12988827305935!3d16.828572950010972!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2smm!4v1689075973621!5m2!1sen!2smm' }}"
              width="100%" height="280" style="border:0; border-radius:16px; min-height:180px; box-shadow:0 2px 16px rgba(255,107,1,0.10); background:#fff;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  <style>
    .contact-card-light, .map-card-light {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 24px rgba(255,107,1,0.07);
      border: 1.5px solid #ffe5d0;
    }
    .contact-info-light {
      gap: 0.5rem;
      font-size: 1.08rem;
      margin-bottom: 0.7rem;
    }
    .contact-icon-light {
      color: #ff6b01;
      font-size: 1.25rem;
      min-width: 1.7em;
      text-align: center;
      background: rgba(255,107,1,0.08);
      border-radius: 50%;
      padding: 0.3em 0.5em;
      margin-right: 0.3em;
      box-shadow: 0 2px 8px rgba(255,107,1,0.08);
    }
    .contact-label-light {
      font-weight: 700;
      color: #ff6b01;
      min-width: 90px;
      letter-spacing: 0.2px;
      font-size: 1.05rem;
    }
    .contact-value-light {
      color: #222;
      font-weight: 500;
      word-break: break-all;
      font-size: 1.05rem;
      opacity: 0.95;
    }
    .map-card-light {
      min-height: 320px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      border-radius: 20px;
    }
    .map-responsive-light {
      position: relative;
      width: 100%;
      padding-bottom: 56.25%;
      height: 0;
      overflow: hidden;
      border-radius: 16px;
      box-shadow: 0 4px 18px rgba(255,107,1,0.08);
      background: #fff;
    }
    .map-responsive-light iframe {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      border-radius: 16px;
      border: none;
      background: #fff;
    }
    @media (max-width: 991.98px) {
      #contact .card { margin-bottom: 1.5rem; }
      .map-card-light { min-height: 220px; }
    }
    @media (max-width: 576px) {
      .contact-label-light { min-width: 70px; font-size: 0.98rem; }
      .contact-info-light { font-size: 0.98rem; }
      .map-card-light { min-height: 180px; }
      .contact-card-light, .map-card-light { border-radius: 10px; }
      .map-responsive-light, .map-responsive-light iframe { border-radius: 10px; }
    }
  </style>
</section>


<script>
// Go to Top Button
const goToTopBtn = document.getElementById('goToTopBtn');
window.addEventListener('scroll', function() {
  if (window.scrollY > 200) {
    goToTopBtn.style.display = 'block';
  } else {
    goToTopBtn.style.display = 'none';
  }
});
goToTopBtn && goToTopBtn.addEventListener('click', function() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>

    <script>

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if(entry.isIntersecting){
                entry.target.classList.add('show');
            } //else{
            //     entry.target.classList.remove('show');
            // }
        })
    });

    const hiddenElements1 = document.querySelectorAll('.hidden1');
    hiddenElements1.forEach((el) => observer.observe(el));

    const hiddenElements2 = document.querySelectorAll('.hidden2');
    hiddenElements2.forEach((el) => observer.observe(el));

    const hiddenElements3 = document.querySelectorAll('.hidden3');
    hiddenElements3.forEach((el) => observer.observe(el));


    let nums = document.querySelectorAll("#number");
    let section = document.querySelector(".section");

    let started = false;

    window.onscroll = function () {
        if(window.scrollY >= section.offsetTop){
            if(!started){
                nums.forEach((num) => startCount(num));
            }
            started = true;
        }
    };


    function startCount(el){
        let goal = el.dataset.goal;
        let count = setInterval(() => {
            el.textContent ++;
            if(el.textContent == goal){
                clearInterval(count);
              }
        }, 20);
    }

    // Typewriter effect for heading
    const typewriterText = [
      "Unlock your full potentials through curiosity"
    ];
    let twIndex = 0;
    let charIndex = 0;
    let typing = true;
    const twSpan = document.getElementById('typewriter-text');

    function typeWriter() {
      if (charIndex < typewriterText[twIndex].length) {
        twSpan.textContent += typewriterText[twIndex].charAt(charIndex);
        charIndex++;
        setTimeout(typeWriter, 60);
      } else {
        typing = false;
        setTimeout(eraseWriter, 1200);
      }
    }
    function eraseWriter() {
      if (charIndex > 0) {
        twSpan.textContent = typewriterText[twIndex].substring(0, charIndex - 1);
        charIndex--;
        setTimeout(eraseWriter, 30);
      } else {
        typing = true;
        twIndex = (twIndex + 1) % typewriterText.length;
        setTimeout(typeWriter, 400);
      }
    }
    if (twSpan) typeWriter();

    </script>


{{-- ------------------------ bottom ------------------ --}}

@endsection
