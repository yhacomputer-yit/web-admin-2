@extends('admin.master.master')

@section('content')

    <main>
        <div class="container-fluid p-2 p-md-4">

            {{-- Message Box  --}}
            <div class="row">
                <div class="col">
                    @if (session('success'))
                        <div class="alert-message">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <span>{{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

             {{-- Subject  --}}
             <div class="row mb-4">
                <div class="col">
                    <h4> <i class="bx bx-book-open fs-3"></i> Subject</h4>
                    @if (count($subjects) > 0)
                    <div class="table-responsive text-nowrap bg-light shadow rounded mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>#{{ $subject->id }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('subject.edit', $subject->id) }}"><i
                                                            class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('subject.delete', $subject->id) }}"><i
                                                            class="bx bx-trash me-1"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <h6 class="text-center text-secondary mt-3 text-uppercase">No Subject</h6>
                    @endif
                    {{-- Pagination  --}}
                    {{ $subjects->appends(['course' => $courses->currentPage(), 'class' => $classes->currentPage()])->links() }}
                    <a style="background-color: #ff6c0f; color:white;" href="{{ route('subject.createPage') }}" class="btn mb-4"> <i class="bx bx-plus"></i>Subject</a>
                </div>
            </div>
            {{-- Course --}}
            <div class="row mb-4">
                <div class="col">
                    <h4> <i class="bx bx-book-alt fs-3"></i> Course</h4>
                    @if (count($courses) > 0)
                    <div class="table-responsive text-nowrap bg-light shadow rounded mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Normal Price</th>
                                    <th>Special Price</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($courses as $course)
                                    <tr>
                                        <td>#{{ $course->id }}</td>
                                        <td>{{ Str::of($course->name)->limit(20) }}</td>
                                        <td>{{ $course->normal_price }} MMK</td>
                                        <td>{{ $course->special_price }} MMK</td>
                                        <td>{{ $course->duration }} hr</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('course.edit', $course->id) }}"><i
                                                            class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('course.delete', $course->id) }}"><i
                                                            class="bx bx-trash me-1"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <h6 class="text-center text-secondary mt-3 text-uppercase">No Course</h6>
                    @endif
                    {{-- Pagination  --}}
                    {{ $courses->appends(['subject' => $subjects->currentPage(), 'class' => $classes->currentPage()])->links() }}
                    <a style="background-color: #ff6c0f; color:white;" href="{{ route('course.createPage') }}" class="btn mb-4"> <i class="bx bx-plus"></i>Course</a>
                </div>
            </div>

            {{-- Class List  --}}
            <div class="row mb-4">
                <h4> <i class="bx bx-book-bookmark fs-3"></i> Class</h4>
                <div class="col-12">
                    @if (count($classes) > 0)
                    <div class="table-responsive text-nowrap bg-light shadow rounded">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Subjects</th>
                                    <th>Instructor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            {{-- Data Table  --}}
                            <tbody class="table-border-bottom-0">
                                @foreach ($classes as $class)
                                    <tr>
                                        <td>#{{ $class->id }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>{{ $class->duration }} hr</td>
                                        <td class="w-25">
                                            {{-- List of Subjects with Modal UX --}}
                                            @if(count($class->subjects) > 0)
                                                {{-- Show first 2 subjects directly --}}
                                                @php
                                                    $visibleSubjects = $class->subjects->take(2);
                                                    $hiddenSubjectsCount = $class->subjects->count() - 2;
                                                @endphp
                                                
                                                @foreach ($visibleSubjects as $subject)
                                                    <div class="rounded-pill d-inline text-white py-1 px-2 mx-1 mb-1"
                                                        style="
                                                            background-color: rgba({{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }}, 0.8);
                                                            ">
                                                        {{ $subject->name }}
                                                    </div>
                                                @endforeach
                                                
                                                {{-- Show "See All" button if there are more subjects --}}
                                                @if($hiddenSubjectsCount > 0)
                                                    <button type="button" 
                                                        class="rounded-pill d-inline text-white py-1 px-2 mx-1 mb-1 border-0"
                                                        style="background-color: #6c757d; cursor: pointer; font-size: 0.8rem;"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#subjectsModal{{ $class->id }}">
                                                        <i class="bx bx-plus"></i> See All ({{ $hiddenSubjectsCount + 2 }})
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-muted">No subjects assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                                {{-- List of Teachers (Distinct) for each class --}}
                                                @php
                                                    $distinctTeachers = collect();

                                                    foreach ($class->subjects as $subject) {
                                                        $distinctTeachers = $distinctTeachers->merge($subject->teachers);
                                                    }

                                                    $distinctTeachers = $distinctTeachers->unique('id');
                                                @endphp

                                                @foreach ($distinctTeachers as $teacher)
                                                    <a href="{{ route('teacher.edit', $teacher->id) }}">
                                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                            data-bs-placement="top" class="avatar avatar-xs pull-up"
                                                            title="{{ $teacher->name }}">
                                                            <img src="{{ asset('storage/' . $teacher->image) }}" alt="Avatar"
                                                                class="rounded-circle shadow" />
                                                        </li>
                                                    </a>
                                                @endforeach
                                            </ul>

                                            </ul>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('class.edit', $class->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    <a class="dropdown-item" href="{{ route('class.delete', $class->id) }}"><i class="bx bx-trash me-1"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    @else
                        <h6 class="text-center text-secondary mt-3 text-uppercase">No Class</h6>
                    @endif
                </div>
            </div>
            {{-- Pagination  --}}
            {{ $classes->appends(['course' => $courses->currentPage(), 'subject' => $subjects->currentPage()])->links() }}
            <a style="background-color: #ff6c0f; color:white;" href="{{ route('class.createPage') }}" class="btn mb-5"> <i class="bx bx-plus"></i>Class</a>



              {{-- monthly course  --}}
            <div class="container">

                <h3><i class="bx bx-calendar fs-3"></i> Monthly Courses</h3>
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($monthlies as $monthly)

                            <tr>
                                <td>{{$monthly->course->name}}</td>
                                <td>{{$monthly->course->normal_price}} mmk</td>
                                <td>{{$monthly->start_date}} / {{$monthly->start_date}}</td>
                                <td>{{$monthly->section->name}} / {{$monthly->section->start}} / {{$monthly->section->end}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{route('monthly.edit',$monthly->id)}}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{route('monthly.delete',$monthly->id)}}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $monthlies->links() }}

                </div>
            </div>

            <a style="background-color: #ff6c0f; color:white;" href="{{route('monthly.add')}}" class="btn"><i class="bx bx-plus"></i>Monthly Courses</a>

        </main>
        
        {{-- Subject Modals --}}
        @foreach ($classes as $class)
            @if(count($class->subjects) > 2)
                <!-- Modal for Course {{ $class->id }} -->
                <div class="modal fade" id="subjectsModal{{ $class->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bx bx-book-open me-2"></i>All Subjects for {{ $class->name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <?php $subjectIndex = 0; ?>
                                    @foreach ($class->subjects as $subject)
                                        <?php $subjectIndex++; ?>
                                        <div class="col-6 col-md-4 col-lg-3 mb-2">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body text-center p-2">
                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width: 35px; height: 35px; background-color: #6c757d; color: white; font-weight: bold; font-size: 0.8rem;">
                                                        {{ substr($subject->name, 0, 1) }}
                                                    </div>
                                                    <h6 class="card-title mb-0" style="font-size: 0.85rem;">{{ Str::limit($subject->name, 8) }}</h6>
                                                    <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $subject->id }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="{{ route('class.edit', $class->id) }}" class="btn btn-primary">
                                    <i class="bx bx-edit me-1"></i>Edit Subjects
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
@endsection
