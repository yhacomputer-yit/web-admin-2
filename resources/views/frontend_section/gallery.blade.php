@extends('layout.front_layout')
@section('content')


<style>
    .home .container{
    width: 100%;
    columns: 3;
    column-gap: 15px;
}
.home img{
    width: 100%;
    height: auto;
    margin: 10px 10px;
    border-radius: 5px;
    box-shadow: 2px 2px 10px rgb(209, 209, 209);
    cursor: pointer;
}
.home img:hover{
    box-shadow: 2px 2px 10px grey;
}
</style>

<section class="home mt-5">
    <div class="container">

              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/02.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/03.png')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/18.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/19.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/21.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/home1.png')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/home2.png')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/home3.png')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">
              <img src="{{asset('image/pic/01.jpg')}}" alt="" onclick="openLightbox(this)">

    </div>
</section>
<!-- Lightbox Modal -->
<div id="lightboxModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.85); align-items:center; justify-content:center;">
  <span onclick="closeLightbox()" style="position:absolute; top:30px; right:40px; color:#fff; font-size:2.5rem; cursor:pointer; z-index:10001;">&times;</span>
  <img id="lightboxImg" src="" style="max-width:90vw; max-height:80vh; border-radius:10px; box-shadow:0 4px 32px #000;">
</div>
<script>
function openLightbox(img) {
  document.getElementById('lightboxImg').src = img.src;
  document.getElementById('lightboxModal').style.display = 'flex';
}
function closeLightbox() {
  document.getElementById('lightboxModal').style.display = 'none';
}
document.addEventListener('keydown', function(e) {
  if(e.key === 'Escape') closeLightbox();
});
</script>


@endsection
