@extends('layout.front_layout')
@section('content')

<style>
/* Modern Event Page Styles */
.events-hero {
  background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%);
  padding: 3rem 0;
  margin-bottom: 3rem;
  border-radius: 0 0 30px 30px;
  color: white;
  text-align: center;
}

.events-hero h1 {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  letter-spacing: 1px;
}

.events-hero p {
  font-size: 1.1rem;
  opacity: 0.9;
  max-width: 600px;
  margin: 0 auto;
}

.event-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  margin-bottom: 2rem;
  border: 1px solid rgba(255,107,1,0.1);
  /* NEW: Add subtle hover effect */
  position: relative;
}

.event-card:hover {
  transform: translateY(-8px) scale(1.01);
  box-shadow: 0 12px 40px rgba(255,107,1,0.15);
}

.event-image {
  width: 100%;
  aspect-ratio: 16/9;
  height: auto;
  max-height: 320px;
  object-fit: cover;
  transition: transform 0.3s ease, box-shadow 0.3s;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(255,107,1,0.07);
  /* NEW: Add border for better separation */
  border: 2px solid #fff;
}

.event-card:hover .event-image {
  transform: scale(1.05);
  box-shadow: 0 6px 24px rgba(255,107,1,0.13);
}

.event-content {
  padding: 2rem 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}

.event-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.event-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #333;
  margin: 0;
  flex: 1;
  min-width: 200px;
}

.event-date {
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.9rem;
  white-space: nowrap;
}

.event-description {
  color: #666;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.event-btn {
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  color: white;
  padding: 0.8rem 2rem;
  border-radius: 25px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  border: none;
  font-size: 1rem;
  box-shadow: 0 2px 8px rgba(255,107,1,0.08);
}

.event-btn i {
  font-size: 1.1em;
  margin-right: 0.5em;
}

.event-btn:hover, .event-btn:focus {
  background: #e65b00;
  color: white;
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 4px 15px rgba(255,107,1,0.18);
  text-decoration: none;
}

.event-content .event-btn-row {
  display: flex;
  justify-content: center;
  margin-top: 1.2rem;
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
@media (max-width: 992px) {
  .event-content {
    padding: 1.2rem 1rem;
  }
  .event-image {
    max-height: 220px;
  }
}

@media (max-width: 768px) {
  .events-hero {
    padding: 2rem 0;
    border-radius: 0 0 20px 20px;
  }

  .events-hero h1 {
    font-size: 2rem;
  }

  .events-hero p {
    font-size: 1rem;
  }

  .event-card {
    border-radius: 14px;
  }
  .event-image {
    border-radius: 14px 14px 0 0;
    max-height: 180px;
  }
  .event-content {
    padding: 1rem 0.7rem;
  }
  .row.g-0.align-items-center {
    flex-direction: column !important;
  }

  .event-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .event-title {
    font-size: 1.2rem;
    min-width: auto;
  }

  .event-date {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
  }
  .event-content .event-btn-row {
    justify-content: center;
  }
  .event-btn {
    width: 100%;
    justify-content: center;
    font-size: 1.05rem;
    padding: 0.9rem 0;
  }
}

@media (max-width: 576px) {
  .events-hero h1 {
    font-size: 1.5rem;
  }

  .event-title {
    font-size: 1.1rem;
  }

  .event-date {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
  }
}
</style>

<section class="events-hero">
  <div class="container">
    <h1>Our Events</h1>
    <p>Discover exciting workshops, seminars, and special events designed to enhance your learning journey and connect with the tech community.</p>
  </div>
</section>

<section class="main">
  <div class="container">
    @if($events->count() > 0)
      @foreach ($events as $event)
        <div class="event-card">
          <div class="row g-0 align-items-center">
            @if($loop->iteration % 2 == 1)
              <!-- ပထမကောင်၊ တတိယကောင်... ပုံ left, စာ right -->
              <div class="col-lg-6">
                <img src="{{ asset('storage/'.$event->image) }}" class="event-image" alt="{{ $event->title }}">
              </div>
              <div class="col-lg-6">
                <div class="event-content">
                  <div class="event-header">
                    <h3 class="event-title">{{ $event->title }}</h3>
                    <div class="event-date">
                      <i class="fa-solid fa-calendar-alt me-2"></i>
                      {{ \Carbon\Carbon::parse($event->edate)->format('M d, Y') }}
                    </div>
                  </div>
                  <div class="event-description">
                    {!! strip_tags($event->aboute) !!}
                  </div>
                  <div class="event-btn-row">
                    <a href="{{ route('user.eventDetail', $event->id) }}" class="event-btn">
                      <span>View Details</span>
                      <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                  </div>
                </div>
              </div>
            @else
              <!-- ဒုတိယကောင်၊ စတုတ္ထကောင်... စာ left, ပုံ right -->
              <div class="col-lg-6 order-lg-2">
                <img src="{{ asset('storage/'.$event->image) }}" class="event-image" alt="{{ $event->title }}">
              </div>
              <div class="col-lg-6 order-lg-1">
                <div class="event-content">
                  <div class="event-header">
                    <h3 class="event-title">{{ $event->title }}</h3>
                    <div class="event-date">
                      <i class="fa-solid fa-calendar-alt me-2"></i>
                      {{ \Carbon\Carbon::parse($event->edate)->format('M d, Y') }}
                    </div>
                  </div>
                  <div class="event-description">
                    {!! strip_tags($event->aboute) !!}
                  </div>
                  <div class="event-btn-row">
                    <a href="{{ route('user.eventDetail', $event->id) }}" class="event-btn">
                      <span>View Details</span>
                      <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      @endforeach

      <div class="pagination-container">
        {{ $events->links() }}
      </div>
    @else
      <div class="py-5 text-center">
        <div class="alert alert-info" role="alert">
          <i class="fa-solid fa-calendar-xmark me-2"></i>
          No events available at the moment. Check back soon for upcoming events!
        </div>
      </div>
    @endif
  </div>
</section>

@endsection
