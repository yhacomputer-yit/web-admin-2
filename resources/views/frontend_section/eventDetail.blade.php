@extends('layout.front_layout')

@section('content')

<!-- Hero Section -->
<section class="event-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="event-breadcrumb">
                    <a href="{{ route('user.home') }}" class="breadcrumb-link">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="{{ route('user.event') }}" class="breadcrumb-link">Events</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ $event->title }}</span>
                </div>

                <div class="event-date">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ \Carbon\Carbon::parse($event->edate)->format('F d, Y') }}</span>
                </div>

                <h1 class="event-title">{{ $event->title }}</h1>

                <div class="event-actions">
                    <a href="{{ route('user.event') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Events
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="event-hero-image">
                    <div class="floating-elements">
                        <div class="floating-icon">
                            <i class="fas fa-champagne-glasses"></i>
                        </div>
                        <div class="floating-text">
                            <span>Special Event</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Event Content Section -->
<section class="event-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="content-card">
                    <h2 class="section-title">Event Details</h2>
                    <div class="event-description">
                        {!! $event->aboute !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="event-sidebar">
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Event Information</h3>
                        <div class="info-list">
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <div class="info-content">
                                    <span class="info-label">Date</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($event->edate)->format('F d, Y') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <div class="info-content">
                                    <span class="info-label">Time</span>
                                    <span class="info-value">6:00 PM - 9:00 PM</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="info-content">
                                    <span class="info-label">Location</span>
                                    <span class="info-value">YHA Computer Center</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Event Gallery Section -->
@if($details && $details->count() > 0)
<section class="event-gallery">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Event Gallery</h2>
            <p class="section-subtitle">Capturing the moments from our special event</p>
        </div>

        <div class="gallery-grid">
            @foreach($details as $detail)
                <div class="gallery-item">
                    <img src="{{ asset('storage/'.$detail->images) }}" alt="Event Image" class="gallery-image">
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<style>
/* Hero Section */
.event-hero {
    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
    padding: 80px 0;
    color: white;
    position: relative;
    overflow: hidden;
}

.event-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.event-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: white;
}

.breadcrumb-separator {
    color: rgba(255, 255, 255, 0.6);
}

.breadcrumb-current {
    color: white;
    font-weight: 600;
}

.event-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    opacity: 0.9;
}

.event-date i {
    color: #ffd700;
}

.event-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 2rem;
    line-height: 1.2;
}

.event-actions {
    display: flex;
    gap: 1rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 1rem 2rem;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-back:hover {
    background: white;
    color: #ff6b01;
    transform: translateY(-3px);
}

.event-hero-image {
    position: relative;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.floating-elements {
    text-align: center;
}

.floating-icon {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    animation: float 3s ease-in-out infinite;
}

.floating-icon i {
    font-size: 2.5rem;
    color: #ffd700;
}

.floating-text {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 0.8rem 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Event Content Section */
.event-content {
    padding: 80px 0;
    background: #f8f9fa;
}

.content-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
}

.event-description {
    color: #666;
    line-height: 1.8;
    font-size: 1.1rem;
}

.event-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.sidebar-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.info-item i {
    color: #ff6b01;
    font-size: 1.2rem;
    width: 20px;
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.9rem;
    color: #666;
}

.info-value {
    font-weight: 600;
    color: #333;
}

/* Event Gallery Section */
.event-gallery {
    padding: 80px 0;
    background: white;
}

.section-header {
    margin-bottom: 3rem;
}

.section-subtitle {
    color: #666;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.gallery-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(255, 107, 1, 0.2);
}

.gallery-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.overlay-content i {
    font-size: 2rem;
    color: white;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
    padding: 60px 0;
    color: white;
    text-align: center;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-primary {
    background: white;
    color: #ff6b01;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    color: #ff6b01;
}

.btn-secondary {
    background: transparent;
    color: white;
    border-color: white;
}

.btn-secondary:hover {
    background: white;
    color: #ff6b01;
    transform: translateY(-3px);
}

/* Responsive Design */
@media (max-width: 992px) {
    .event-title {
        font-size: 2.5rem;
    }

    .event-actions {
        flex-direction: column;
    }

    .btn-back {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .event-hero {
        padding: 60px 0;
    }

    .event-title {
        font-size: 2rem;
    }

    .event-content {
        padding: 60px 0;
    }

    .content-card {
        padding: 1.5rem;
    }

    .sidebar-card {
        padding: 1.5rem;
    }

    .event-gallery {
        padding: 60px 0;
    }

    .gallery-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .cta-content h2 {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .event-title {
        font-size: 1.8rem;
    }

    .section-title {
        font-size: 1.6rem;
    }

    .gallery-grid {
        grid-template-columns: 1fr;
    }

    .gallery-image {
        height: 200px;
    }

    .cta-content h2 {
        font-size: 1.8rem;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

@endsection
