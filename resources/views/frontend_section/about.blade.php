@extends('layout.front_layout')
@section('content')
<!-- Hero Section -->
<section class="about-hero" style="background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%); color: white; padding: 60px 0 40px 0; text-align: center;">
    <div class="container">
        <div style="font-size:3rem; margin-bottom:1rem;"><i class="fa-solid fa-graduation-cap"></i></div>
        <h1 class="fw-bold mb-3" style="font-size:2.5rem;">About Us</h1>
        <p style="opacity:0.95; max-width:600px; margin:0 auto; font-size:1.15rem;">Welcome to our organization, where innovation meets excellence. We are a dynamic team of dedicated professionals committed to driving progress in various industries.</p>
    </div>
</section>

<!-- About Content Section -->
<section class="about-content-section" style="background:#f8f9fa; padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="about-card" style="background:white; border-radius:20px; box-shadow:0 8px 32px rgba(255,107,1,0.08); padding:2.5rem 2rem;">
                    <h2 class="mb-3" style="color:#ff6b01; font-weight:700;">Our Mission</h2>
                    <p style="font-size:1.1rem; color:#555;">Our mission is to empower individuals and organizations with the knowledge and skills needed to thrive in the ever-evolving world of technology. We believe that by fostering a culture of learning and innovation, we can create a brighter future for all.</p>
                    <h2 class="mb-3 mt-5" style="color:#ff6b01; font-weight:700;">Our Vision</h2>
                    <p style="font-size:1.1rem; color:#555;">At our core, we are driven by a vision to be the go-to destination for those seeking to excel in the ever-evolving landscape of technology. We envision a world where every individual has access to the knowledge and resources they need to succeed, and where businesses thrive on a foundation of innovation and collaboration.</p>
                    <h2 class="mb-3 mt-5" style="color:#ff6b01; font-weight:700;">Our Values</h2>
                    <ul class="about-values-list" style="font-size:1.08rem; color:#444; padding-left:1.2rem;">
                        <li><b>Integrity:</b> We hold ourselves to the highest standards of honesty, transparency, and ethical conduct.</li>
                        <li><b>Creativity:</b> We embrace innovation and constantly seek new ideas to drive progress.</li>
                        <li><b>Collaboration:</b> We believe in the power of teamwork and celebrate the diversity of perspectives within our organization.</li>
                        <li><b>Customer Focus:</b> We prioritize delivering exceptional products and services that meet and exceed our customers' expectations.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
.about-hero h1 { letter-spacing: 1px; }
.about-card { transition: box-shadow 0.2s; }
.about-card:hover { box-shadow:0 16px 48px rgba(255,107,1,0.13); }
@media (max-width: 768px) {
    .about-hero { padding: 36px 0 24px 0; }
    .about-card { padding: 1.2rem 0.7rem; }
}
</style>
@endsection
