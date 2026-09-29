@extends('admin.master.master')

@section('content')

<main>
    <div class="container-fluid p-2 p-md-4">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-10 col-lg-9">

                <a href="{{ route('admin.enrollment') }}" class="btn btn-secondary">
                    <i class="bx bx-left-arrow-alt"></i> Back
                </a>

                <div class="card my-3 border-warning shadow">
                    {{-- Card Header  --}}
                    <div class="card-header border-warning">
                        <h3 class="h5 text-primary"><i class="bx bx-book-add fs-3"></i> Add Course Enrollment</h3>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('enrollment.create') }}" method="POST">
                            @csrf

                            @include('admin.enrollment.partials.form', ['enrollment' => null])

                            <button type="submit" class="btn mt-4" style="background-color: #ff6c0f; color: white;">
                                <i class="bx bx-down-arrow-alt"></i> Save Enrollment
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

@endsection
