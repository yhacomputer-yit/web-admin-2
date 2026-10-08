@extends('layout.front_layout')

@section('seo_title', 'Student Projects - YHA ACADEMY OF TECHNOLOGY')

@section('seo_description', 'Explore student projects from YHA ACADEMY OF TECHNOLOGY. Programming, Graphic Design, and ICT projects showcasing hands-on learning and practical skills.')

@section('seo_keywords', 'student projects Myanmar, YHA ACADEMY OF TECHNOLOGY projects, programming projects, graphic design portfolio, ICT projects Yangon')

@section('og_title', 'Student Projects - YHA ACADEMY OF TECHNOLOGY')

@section('og_description', 'Browse student projects from YHA ACADEMY OF TECHNOLOGY courses. Real-world applications built by our students.')

@section('og_image', asset('image/logo/logo.png'))

@section('og_url', url('/project'))

@section('twitter_title', 'Student Projects - YHA ACADEMY OF TECHNOLOGY')

@section('twitter_description', 'Explore programming, graphic design, and ICT student projects')

@section('twitter_image', asset('image/logo/logo.png'))

@section('canonical_url', url('/project'))

@section('content')
<!-- Add this in your HTML head section -->
{{-- <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head> --}}

<style>
/* Modern Projects Page Styles */
.projects-hero {
  background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
  padding: 3rem 0;
  margin-bottom: 3rem;
  border-radius: 0 0 30px 30px;
  color: white;
  text-align: center;
}

.projects-hero h1 {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  letter-spacing: 1px;
}

.projects-hero p {
  font-size: 1.1rem;
  opacity: 0.9;
  max-width: 600px;
  margin: 0 auto;
}

.course-filter {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  padding: 2rem;
  margin-bottom: 3rem;
  border: 1px solid rgba(255,107,1,0.1);
}

.filter-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.filter-btn {
  background: #f8f9fa;
  color: #666;
  border: 2px solid #e9ecef;
  padding: 0.8rem 2rem;
  border-radius: 25px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-block;
}

.filter-btn:hover,
.filter-btn.active {
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  color: white;
  border-color: #ff6b01;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(255,107,1,0.3);
}

.project-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  margin-bottom: 2rem;
  border: 1px solid rgba(255,107,1,0.1);
  height: 100%;
}

.project-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(255,107,1,0.15);
}

.project-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.project-card:hover .project-image {
  transform: scale(1.05);
}

.project-content {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  /* height: 100%; */
}

.project-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 0.5rem;
}

.project-title-link {
  color: #333;
  text-decoration: none;
  transition: color 0.3s ease;
}

.project-title-link:hover {
  color: #ff6b01;
}

.project-course {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  color: #ff6b01;
  font-weight: 600;
}

.project-description {
  color: #666;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  /* flex-grow: 1; */
  /* display: -webkit-box; */
  /* -webkit-line-clamp: 3; */
  /* -webkit-box-orient: vertical; */
  /* overflow: hidden; */
}

.project-links {
  display: flex;
  gap: 0.8rem;
  justify-content: center;
}

.project-student {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.project-student-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ff6c0f;
}

.project-student-info {
  display: flex;
  flex-direction: column;
}

.project-student-name {
  font-weight: 600;
  color: #333;
  font-size: 0.95rem;
}

.project-student-edu {
  font-size: 0.8rem;
  color: #666;
}

.project-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  border-radius: 20px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  border: 1px solid transparent;
  flex: 1;
  justify-content: center;
}


.project-link.disabled {
  pointer-events: none;
}

.project-link.disabled:hover {
  transform: none;
}

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

.pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 3rem;
}

.pagination .page-link {
  color: #ff6b01;
  border: 1px solid #ff6b01;
  margin: 0 0.2rem;
  border-radius: 8px;
}

.pagination .page-item.active .page-link {
  background: #ff6b01;
  border-color: #ff6b01;
  color: white;
}

.pagination .page-link:hover {
  background: #ff6b01;
  color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
  .projects-hero {
    padding: 2rem 0;
    border-radius: 0 0 20px 20px;
  }

  .projects-hero h1 {
    font-size: 2rem;
  }

  .projects-hero p {
    font-size: 1rem;
  }

  .course-filter {
    padding: 1.5rem;
  }

  .filter-buttons {
    flex-direction: column;
    align-items: center;
  }

  .filter-btn {
    width: 100%;
    max-width: 200px;
    text-align: center;
  }

  .project-content {
    padding: 1rem;
  }

  .project-links {
    flex-direction: column;
  }
}

@media (max-width: 576px) {
  .projects-hero h1 {
    font-size: 1.5rem;
  }

  .project-title {
    font-size: 1.1rem;
  }

  .project-image {
    height: 180px;
  }
}
</style>

<section class="projects-hero">
  <div class="container">
    <h1>Student Projects</h1>
    <p>Explore innovative projects created by our talented students across different courses and technologies.</p>
  </div>
</section>

<section id="proj">
  <div class="container">
    <div class="course-filter">
      <div class="filter-buttons">
        <a href="{{ route('user.project', 1) }}" class="filter-btn {{ request()->route('c_id') == 1 ? 'active' : '' }}">
          <i class="fa-solid fa-code me-2"></i>
          Programming
        </a>
        <a href="{{ route('user.project', 2) }}" class="filter-btn {{ request()->route('c_id') == 2 ? 'active' : '' }}">
          <i class="fa-solid fa-palette me-2"></i>
          Graphic Design
        </a>
      </div>
    </div>

    <div class="row" id="projects-container">
      @if($projects->isEmpty())
        <div class="col-12">
          <div class="empty-state">
            <i class="fa-solid fa-code-branch"></i>
            <h3>Coming Soon!</h3>
            <p>We're working on some amazing projects. Check back soon to see what our students have been creating!</p>
          </div>
        </div>
      @else
        @foreach($projects as $project)
          <div class="mb-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="project-card">
              <img src="{{ asset('storage/'.$project->image) }}" class="project-image" alt="{{ $project->title }}">
              <div class="project-content">
                <h5 class="project-title">
                  <a href="{{ route('user.projectDetail', $project->id) }}" class="project-title-link">{{ $project->title }}</a>
                </h5>
                <div class="project-course">
                  <i class="fa-solid fa-graduation-cap"></i>
                  <span>{{ $project->course->name }}</span>
                </div>
                <p class="project-description">{{ $project->desc }}</p>

                @if($project->student)
                  <div class="project-student">
                    <img src="{{ asset('storage/'.($project->student->image ?? 'image/logo/student-placeholder.svg')) }}" alt="{{ $project->student->name ?? 'Student' }}" class="project-student-img">
                    <div class="project-student-info">
                      <span class="project-student-name">{{ $project->student->name }}</span>
                      <span class="project-student-edu">{{ $project->student->education }}</span>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      @endif
    </div>

    @if($projects->hasPages())
      <div class="pagination-container">
        {{ $projects->links() }}
      </div>
    @endif
  </div>
</section>

@endsection
