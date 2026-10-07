@extends('layout.front_layout')

@section('content')

<!-- Hero Section -->
<section class="project-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="project-breadcrumb">
                    <a href="{{ route('user.home') }}" class="breadcrumb-link">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="{{ route('user.project') }}" class="breadcrumb-link">Projects</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">{{ $project->title }}</span>
                </div>

                <div class="project-category">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ $project->course->name }}</span>
                </div>

                <h1 class="project-title">{{ $project->title }}</h1>
                <p class="project-description">{{ $project->desc }}</p>

                <div class="project-actions">
                    <a href="{{ route('user.project') }}" class="btn-back">
                        <i className="fas fa-arrow-left"></i>
                        Back to Projects
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="project-image-wrapper">
                    <img src="{{ asset('storage/'.$project->image) }}" alt="{{ $project->title }}" class="project-image">
                    <div class="project-badge">
                        <span>Student Project</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Student Information Section -->
@if($project->student)
<section class="project-student-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 text-center">
                <img src="{{ asset('storage/'.($project->student->image ?? 'image/logo/student-placeholder.svg')) }}" alt="{{ $project->student->name ?? 'Student' }}" class="project-student-avatar">
            </div>
            <div class="col-lg-9">
                <h2 class="project-student-name">{{ $project->student->name }}</h2>
                <p class="project-student-education">{{ $project->student->education }}</p>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Project Details Section -->
<section class="project-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="details-content">
                    <h2 class="section-title">Project Overview</h2>
                    <div class="project-overview">
                        <p>{{ $project->desc }}</p>
                    </div>

                    <div class="project-features">
                        <h3 class="subsection-title">Key Features</h3>
                        <div class="features-grid">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Responsive Design</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Modern UI/UX</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Cross-browser Compatible</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Performance Optimized</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="project-sidebar">
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Project Information</h3>
                        <div class="info-list">
                            <div class="info-item">
                                <i class="fas fa-graduation-cap"></i>
                                <div class="info-content">
                                    <span class="info-label">Course</span>
                                    <span class="info-value">{{ $project->course->name }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <div class="info-content">
                                    <span class="info-label">Created</span>
                                    <span class="info-value">{{ $project->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-code"></i>
                                <div class="info-content">
                                    <span class="info-label">Status</span>
                                    <span class="info-value">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Technology Stack</h3>
                        <div class="tech-stack">
                            <span class="tech-tag">HTML5</span>
                            <span class="tech-tag">CSS3</span>
                            <span class="tech-tag">JavaScript</span>
                            <span class="tech-tag">PHP</span>
                            <span class="tech-tag">MySQL</span>
                            <span class="tech-tag">Bootstrap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Links Section removed -->

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content text-center">
            <h2>Inspired by This Project?</h2>
            <p>Join our courses and create amazing projects like this one</p>
            <div class="cta-buttons">
                <a href="{{ route('user.courses') }}" class="btn-primary">
                    <i class="fas fa-graduation-cap"></i>
                    Explore Courses
                </a>
                <a href="{{ route('user.project') }}" class="btn-secondary">
                    <i class="fas fa-code"></i>
                    View More Projects
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.project-hero {
    background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
    padding: 80px 0;
    color: white;
    position: relative;
    overflow: hidden;
}

.project-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.project-breadcrumb {
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

.project-category {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    opacity: 0.9;
}

.project-category i {
    color: #ffd700;
}

.project-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.project-description {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.project-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary, .btn-back {
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

.btn-back {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-back:hover {
    background: white;
    color: #ff6b01;
    transform: translateY(-3px);
}

.project-image-wrapper {
    position: relative;
    text-align: center;
}

.project-image {
    width: 100%;
    max-width: 400px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.project-image:hover {
    transform: scale(1.05);
}

.project-badge {
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

/* Project Details Section */
.project-details {
    padding: 80px 0;
    background: #f8f9fa;
}

.details-content {
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

.project-overview {
    color: #666;
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.subsection-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.feature-item i {
    color: #ff6b01;
    font-size: 1.2rem;
}

.project-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
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

.tech-stack {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tech-tag {
    background: linear-gradient(45deg, #ff6b01, #ffb347);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Project Student Section */
.project-student-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.project-student-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ff6c0f;
    margin-bottom: 1rem;
}

.project-student-name {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.project-student-education {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 0;
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
    .project-title {
        font-size: 2.5rem;
    }

    .project-actions {
        flex-direction: column;
    }

    .btn-primary, .btn-secondary, .btn-back {
        justify-content: center;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .project-hero {
        padding: 60px 0;
    }

    .project-title {
        font-size: 2rem;
    }

    .project-description {
        font-size: 1rem;
    }

    .project-details {
        padding: 60px 0;
    }

    .details-content {
        padding: 1.5rem;
    }

    .sidebar-card {
        padding: 1.5rem;
    }

    .project-student-section {
        padding: 60px 0;
    }

    .cta-content h2 {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .project-title {
        font-size: 1.8rem;
    }

    .project-image {
        max-width: 300px;
    }

    .section-title {
        font-size: 1.6rem;
    }

    .project-student-avatar {
        width: 80px;
        height: 80px;
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
