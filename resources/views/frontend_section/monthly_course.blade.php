@extends('layout.front_layout')

@section('content')
    <!-- Hero/Header Section -->
    <section class="course-hero" style="background: linear-gradient(135deg, #ff6b01 0%, #ffb347 100%); color: white; padding: 60px 0 30px 0; text-align: center;">
        <div class="container">
            <h1 class="fw-bold mb-2" style="font-size:2.2rem;">Monthly Courses Detail</h1>
            <p style="opacity:0.9; max-width:600px; margin:0 auto;">Explore our monthly courses with flexible schedules and hands-on learning. Find the right course for you and enroll today!</p>
        </div>
    </section>
    <div class="main w-100 mt-0">
        <div class="container">
            @foreach ($monthies as $monthly)
            <div class="row w-100 mb-5 d-flex justify-content-between align-items-center">
                <!-- course image  -->
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <img class="w-100 rounded-4" src="{{ asset('storage/'.$monthly->m_img) }}" alt="" style="object-fit:cover; max-height:340px;">
                    </div>
                </div>
                <!-- course detail  -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                        <h2 class="fw-bold mb-3" style="color: #ff6b01;">{{$monthly->course->name}}</h2>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fa-solid fa-money-bill-wave me-2" style="color:#ff6b01;"></i> <b>Price:</b> {{$monthly->course->normal_price}} MMKs</li>
                            <li class="mb-2"><i class="fa-solid fa-calendar-days me-2" style="color:#ff6b01;"></i> <b>Date:</b> {{$monthly->start_date}} / {{$monthly->end_date}}</li>
                            <li class="mb-2"><i class="fa-solid fa-clock me-2" style="color:#ff6b01;"></i> <b>Time:</b> {{$monthly->section->start}} - {{$monthly->section->end}} ({{$monthly->section->name}})</li>
                            <li class="mb-2"><i class="fa-solid fa-users me-2" style="color:#ff6b01;"></i> <b>Limited Seat:</b> {{$monthly->limited_seat}} Student</li>
                        </ul>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-2" style="color:#ff6b01;">Subjects</h5>
                            <ul class="list-group list-group-flush">
                                @if($monthly->course && $monthly->course->subjects && $monthly->course->subjects->count())
                                    @foreach($monthly->course->subjects as $subject)
                                        <li class="list-group-item">{{ $subject->name }}</li>
                                    @endforeach
                                @else
                                    <li class="list-group-item text-muted">No subjects available.</li>
                                @endif
                            </ul>
                        </div>
                        <div class="d-flex gap-3 align-items-center justify-content-center mt-3 flex-wrap">
                            <a href="#" class="btn enroll-btn px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-plus"></i> Enroll Now
                            </a>
                            <a href="#" class="btn contact-btn px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- course description session  -->
            <div class="row w-100 px-md-5 px-2 mb-4">
                <div class="col-12 w-100">
                    <div class="border p-4 w-100 shadow-sm rounded-4 bg-white">
                        <h4 class="primary-color mb-3" style="color:#ff6b01;">Description</h4>
                        <p class="lh-lg mb-0" style="color:#333;">{!! $monthly->m_desc !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
            <hr class="d-block">
        </div>
    </div>
    <style>
        .card { background: #fff; }
        .list-group-item { border: none; padding-left: 0; }
        @media (max-width: 991.98px) {
            .card { margin-bottom: 1.5rem; }
        }
        @media (max-width: 767.98px) {
            .main .card.h-100 { margin-top: 1.2rem; }
        }
        .enroll-btn {
            background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(255,107,1,0.10);
            border: none;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        }
        .enroll-btn:hover {
            background: linear-gradient(90deg, #ffb347 0%, #ff6b01 100%);
            color: #fff !important;
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 6px 18px rgba(255,107,1,0.18);
        }
        .contact-btn {
            background: #fff;
            color: #ff6b01 !important;
            border: 2px solid #ff6b01;
            transition: background 0.2s, color 0.2s, border 0.2s, transform 0.2s;
        }
        .contact-btn:hover {
            background: linear-gradient(90deg, #ff6b01 0%, #ffb347 100%);
            color: #fff !important;
            border-color: #ff6b01;
            transform: translateY(-2px) scale(1.04);
        }
    </style>
@endsection
