@extends('layout.front_layout')

@section('content')
<style>
.review-list-section {
  background: linear-gradient(120deg, #fff7f0 60%, #ffe5d0 100%);
  animation: reviewBgMove 12s ease-in-out infinite alternate;
  padding: 2rem 0 1.2rem 0;
  position: relative;
  overflow: hidden;
}
@keyframes reviewBgMove {
  0% { background-position: 0 0; }
  100% { background-position: 100% 100%; }
}
.review-list-heading {
  background: rgba(255,255,255,0.32);
  border-radius: 28px;
  box-shadow: 0 4px 24px rgba(255,107,1,0.13);
  border: 2px solid #ffe5d0;
  backdrop-filter: blur(12px);
  padding: 1rem 1.2rem 1rem 1.2rem;
  margin-bottom: 1.5rem;
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
}
.review-list-heading h1 {
  font-size: 2.2rem;
  font-weight: 900;
  background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  letter-spacing: 1.5px;
  font-family: 'DM Serif Text', serif;
  line-height: 1.2;
  text-shadow: 0 2px 12px rgba(255,107,1,0.10);
  margin: 0;
}
.review-filter-bar {
  background: rgba(255,255,255,0.92);
  border-radius: 18px;
  box-shadow: 0 4px 24px rgba(255,107,1,0.10);
  border: 2px solid #ffe5d0;
  padding: 0.7rem 1rem;
  margin-bottom: 2rem;
  display: flex;
  flex-wrap: wrap;
  gap: 1.2rem;
  align-items: end;
  justify-content: center;
  position: sticky;
  top: 70px;
  z-index: 2;
  animation: fadeInDown 0.7s;
}
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}
.review-card-list {
  border-radius: 28px;
  box-shadow: 0 8px 32px rgba(255,107,1,0.13), 0 2px 16px rgba(255,107,1,0.10);
  border: 2.5px solid transparent;
  border-image: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
  border-image-slice: 1;
  background: rgba(255,255,255,0.97);
  padding: 4.2rem 1.1rem 1.1rem 1.1rem;
  margin-top: 3.2rem;
  margin-bottom: 1.2rem;
  text-align: center;
  transition: box-shadow 0.2s, transform 0.2s;
  position: relative;
  animation: fadeInUp 0.7s;
  overflow: visible;
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.review-card-list:hover {
  box-shadow: 0 20px 64px 0 rgba(255,107,1,0.22), 0 4px 24px 0 rgba(255,107,1,0.13);
  transform: translateY(-10px) scale(1.04) rotate(-1deg);
}
.review-avatar-list {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #fff;
  margin: 0 auto 1rem auto;
  background: #fff;
  box-shadow: 0 4px 16px rgba(255,107,1,0.18);
  position: absolute;
  left: 50%;
  top: -48px;
  transform: translateX(-50%);
  z-index: 2;
  animation: floatAvatar 2.5s ease-in-out infinite alternate;
}
@keyframes floatAvatar {
  from { transform: translateX(-50%) translateY(0); }
  to { transform: translateX(-50%) translateY(-8px); }
}
.review-avatar-list.premium-avatar-glow {
  box-shadow: 0 0 0 0 #ffb347, 0 0 16px 4px #ffb34780, 0 0 32px 8px #ff6b0180;
  animation: avatarGlow 2.2s infinite alternate, floatAvatar 2.5s ease-in-out infinite alternate;
}
@keyframes avatarGlow {
  from { box-shadow: 0 0 0 0 #ffb347, 0 0 16px 4px #ffb34780, 0 0 32px 8px #ff6b0180; }
  to { box-shadow: 0 0 0 8px #ffb34744, 0 0 32px 12px #ffb34760, 0 0 48px 16px #ff6b0140; }
}
.review-quote-icon-list {
  position: absolute;
  left: 32px;
  top: 32px;
  font-size: 2.5rem;
  color: #ffb347;
  opacity: 0.16;
  z-index: 1;
  filter: blur(0.5px);
}
.review-stars-list {
  color: #ffb347;
  font-size: 1.25rem;
  margin-bottom: 0.5rem;
  text-shadow: 0 1px 8px #fff7f0;
}
.review-text-list {
  font-size: 1.18rem;
  color: #333;
  margin-bottom: 0.7rem;
  font-style: italic;
  min-height: 60px;
  margin-top: 0.7rem;
  position: relative;
  z-index: 2;
  line-height: 1.7;
  letter-spacing: 0.01em;
  text-shadow: 0 1px 8px #fff7f0;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
@media (max-width: 767.98px) {
  .review-card-list { border-radius: 14px; padding: 3rem 0.4rem 0.8rem 0.4rem; margin-top: 2.5rem; margin-bottom: 0.7rem; }
  .review-avatar-list { width: 60px; height: 60px; top: -28px; }
  .review-list-heading h1 { font-size: 1.1rem; }
}
.review-pagination {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 2rem;
}
.review-pagination .page-link {
  border-radius: 50%;
  background: #fff7f0;
  color: #ff6b01;
  border: 1.5px solid #ffb347;
  box-shadow: 0 2px 8px rgba(255,107,1,0.08);
  font-weight: 600;
  transition: background 0.2s, color 0.2s, transform 0.2s;
}
.review-pagination .page-link.active, .review-pagination .page-link:hover {
  background: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
  color: #fff;
  transform: scale(1.08);
}
</style>
<section class="review-list-section">
  <div class="container">
    <div class="review-list-heading mb-4">
      <h1><i class="fa-solid fa-star"></i> Student Reviews</h1>
    </div>
    {{-- Filter Form --}}
    <form method="GET" action="{{ route('reviews.index') }}" class="review-filter-bar">
      <div>
        <label for="rating" class="form-label mb-0"><i class="fa-solid fa-filter"></i> Rating</label>
        <select name="rating" id="rating" class="form-select" onchange="this.form.submit()">
          <option value="">All</option>
          @for($i=5; $i>=1; $i--)
            <option value="{{ $i }}" {{ (isset($rating) && $rating==$i) ? 'selected' : '' }}>{{ $i }} Star{{ $i>1 ? 's' : '' }}</option>
          @endfor
        </select>
      </div>
      <div>
        <label for="sort" class="form-label mb-0"><i class="fa-solid fa-sort"></i> Sort</label>
        <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
          <option value="latest" {{ (isset($sort) && $sort=='latest') ? 'selected' : '' }}>Latest</option>
          <option value="oldest" {{ (isset($sort) && $sort=='oldest') ? 'selected' : '' }}>Oldest</option>
        </select>
      </div>
      @if(isset($rating) && $rating)
        <div>
          <a href="{{ route('reviews.index', array_merge(request()->except('rating'), ['sort' => $sort ?? 'latest'])) }}" class="btn btn-link">Clear Filter</a>
        </div>
      @endif
    </form>
    <div class="row justify-content-center">
      @forelse($reviews as $review)
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4 d-flex align-items-stretch">
          <div class="review-card-list w-100 animate__animated animate__fadeInUp">
            <span class="review-quote-icon-list"><i class="fa-solid fa-quote-left"></i></span>
            @if($review->photo)
              <img src="{{ asset('storage/'.$review->photo) }}" class="review-avatar-list premium-avatar-glow" alt="{{ $review->name }}">
            @else
              <img src="https://ui-avatars.com/api/?name={{ urlencode($review->name) }}&background=ffb347&color=fff" class="review-avatar-list premium-avatar-glow" alt="{{ $review->name }}">
            @endif
            <h5 class="mb-1 mt-4">{{ $review->name }}</h5>
            <div class="review-stars-list mb-2">
              @for($i=1; $i<=5; $i++)
                @if($i <= $review->rating)
                  <i class="fa-solid fa-star"></i>
                @else
                  <i class="fa-regular fa-star"></i>
                @endif
              @endfor
            </div>
            <div class="review-text-list">{{ $review->review }}</div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center text-muted">No reviews found.</div>
      @endforelse
    </div>
    <div class="review-pagination">
      {{ $reviews->links() }}
    </div>
  </div>
</section>
@endsection
