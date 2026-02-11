@extends("layout.front_layout")
@section('content')

<!-- Hero Section -->
<section class="course-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="course-breadcrumb">
                    <a href="{{ route('user.home') }}" class="breadcrumb-link">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="{{ route('user.courses') }}" class="breadcrumb-link">Courses</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ $course->name }}</span>
                </div>

                <h1 class="course-title">{{ $course->name }}</h1>
                <p class="course-description">{{ $course->description }}</p>

                <div class="course-meta">
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $course->duration }} Hours</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>Live Classes</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-certificate"></i>
                        <span>Certificate</span>
                    </div>
                </div>

                <div class="course-actions">
                    @if($course->links)
                        <a href="{{ $course->links }}" class="btn-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            View Details
                        </a>
                    @endif
                    <a href="#subjects" class="btn-secondary">
                        <i class="fas fa-list"></i>
                        View Subjects
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="course-image-wrapper">
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" class="course-image">
                    <div class="course-badge">
                        <span>Featured Course</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course About Section -->
<section class="course-about">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="about-content">
                    <h2 class="section-title">About This Course</h2>
                    <div class="about-text">
                        {!! $course->about !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="course-sidebar">
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Course Information</h3>
                        <div class="info-list">
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <div class="info-content">
                                    <span class="info-label">Duration</span>
                                    <span class="info-value">{{ $course->duration }} Hours</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-dollar-sign"></i>
                                <div class="info-content">
                                    <span class="info-label">Price</span>
                                    <span class="info-value">Ks {{ number_format($course->normal_price) }}</span>
                                </div>
                            </div>
                            @if($course->special_price)
                            <div class="info-item">
                                <i class="fas fa-tag"></i>
                                <div class="info-content">
                                    <span class="info-label">Special Price</span>
                                    <span class="info-value special">Ks {{ number_format($course->special_price) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subjects Section -->
<section id="subjects" class="subjects-section">
    <div class="container">
        <div class="text-center section-header">
            <h2 class="section-title">Course Subjects</h2>
            <p class="section-subtitle">Comprehensive curriculum designed for your success</p>
        </div>

        <div class="row">
            @if($subjects && $subjects->count() > 0)
                @foreach($subjects as $index => $subject)
                    <div class="mb-4 col-lg-4 col-md-6">
                        <div class="subject-card">
                            <div class="subject-number">{{ $index + 1 }}</div>
                            <div class="subject-content">
                                <h3 class="subject-title">{{ $subject->subject->name }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <h3>No Subjects Available</h3>
                        <p>Course subjects will be updated soon. Please check back later.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="text-center cta-content">
            <h2>Ready to Start Learning?</h2>
            <p>Join our course and take the first step towards your career goals</p>
            <div class="cta-buttons">
                <a href="{{ route('user.courses') }}" class="btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Courses
                </a>
                @php
                    $ctaCourseUrl = $course->links;
                    if ($ctaCourseUrl && !preg_match('/^https?:\/\//', $ctaCourseUrl)) {
                        $ctaCourseUrl = 'https://' . $ctaCourseUrl;
                    }
                @endphp
                @if($course->links)
                    <a href="{{ $ctaCourseUrl }}" class="btn-secondary" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        Learn More
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.course-hero {
    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
    padding: 80px 0;
    color: white;
    position: relative;
    overflow: hidden;
}

.course-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.course-breadcrumb {
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

.course-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.course-description {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.course-meta {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item i {
    color: #ffd700;
    font-size: 1.2rem;
}

.course-actions {
    display: flex;
    gap: 1rem;
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

.course-image-wrapper {
    position: relative;
    text-align: center;
}

.course-image {
    width: 100%;
    max-width: 400px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.course-image:hover {
    transform: scale(1.05);
}

.course-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.9);
    color: #ff6b01;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Course About Section */
.course-about {
    padding: 80px 0;
    background: #f8f9fa;
}

.about-content {
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

.about-text {
    color: #666;
    line-height: 1.8;
    font-size: 1.1rem;
}

.course-sidebar {
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

.info-value.special {
    color: #ff6b01;
}

/* Subjects Section */
.subjects-section {
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

.subject-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #eee;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.subject-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(255, 107, 1, 0.15);
}

.subject-number {
    position: absolute;
    top: -10px;
    right: -10px;
    background: linear-gradient(45deg, #ff6b01, #ffb347);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}

.subject-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    padding-right: 50px;
}

.subject-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.subject-duration, .subject-level {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.9rem;
    color: #666;
}

.subject-duration i, .subject-level i {
    color: #ff6b01;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8f9fa;
    border-radius: 20px;
    border: 2px dashed #dee2e6;
}

.empty-state i {
    font-size: 3rem;
    color: #ff6b01;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #333;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #666;
    max-width: 400px;
    margin: 0 auto;
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

/* Responsive Design */
@media (max-width: 992px) {
    .course-title {
        font-size: 2.5rem;
    }

    .course-meta {
        flex-direction: column;
        gap: 1rem;
    }

    .course-actions {
        flex-direction: column;
    }

    .btn-primary, .btn-secondary {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .course-hero {
        padding: 60px 0;
    }

    .course-title {
        font-size: 2rem;
    }

    .course-description {
        font-size: 1rem;
    }

    .course-about {
        padding: 60px 0;
    }

    .about-content {
        padding: 1.5rem;
    }

    .sidebar-card {
        padding: 1.5rem;
    }

    .subjects-section {
        padding: 60px 0;
    }

    .cta-content h2 {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .course-title {
        font-size: 1.8rem;
    }

    .course-image {
        max-width: 300px;
    }

    .section-title {
        font-size: 1.6rem;
    }

    .subject-card {
        padding: 1rem;
    }

    .subject-title {
        font-size: 1.1rem;
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
