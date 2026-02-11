@extends('admin.master.master')

@section('content')

    {{-- alert section  --}}
    @if (session('success'))
        <div class="bg-white shadow-sm alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif




    {{-- Welcome section  --}}
    <div class="mb-5 container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Card  --}}
                <div class="mb-2 shadow card">
                    <div class="card-header">
                        <div class="fs-4 fw-bolder text-uppercase">Welcome Page</div>
                    </div>
                    <div class="card-body">
                        {{-- Table Start  --}}
                        @if (count($welcome) != 0)
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Image</th>
                                            <th>Image Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($welcome as $data)
                                            <tr>
                                                <td>#{{ $data->id }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $data->image) }}" alt=""
                                                        class="" width="100">
                                                </td>
                                                <td>
                                                    {{ $data->image }}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="p-0 btn dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ route('welcome.edit', $data->id) }}"><i
                                                                    class="bx bx-edit-alt me-2"></i> Edit</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('welcome.delete', $data->id) }}"><i
                                                                    class="bx bx-trash me-2"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center fs-5">No image</div>
                        @endif
                    </div>
                </div>
                {{-- Pagination  --}}
                {{ $welcome->appends(request()->query())->links() }}
                {{-- Button  --}}
                <a style="background-color: #ff6c0f; color:white;" href="{{ route('welcome.create') }}" class="btn btn-sm-sm"> <i class="bx bx-plus"></i>Add
                    Image</a>
            </div>
        </div>
    </div>




    {{-- About Page Section  --}}
    <div class="mb-5 container-fluid">
        <div class="row">
            <div class="mb-4 col-lg-8">
                <div class="mb-2 shadow card">
                    <div class="card-header">
                        <div class="fs-4 fw-bolder text-uppercase">About Page</div>
                    </div>
                    <div class="card-body">
                        {{-- Table Start  --}}
                        @if (count($about) != 0)
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Image</th>
                                            <th>Image Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($about as $data)
                                            <tr>
                                                <td>#{{ $data->id }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $data->image) }}" alt=""
                                                        class="" width="100">
                                                </td>
                                                <td>
                                                    {{ $data->image }}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="p-0 btn dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ route('about.edit', $data->id) }}"><i
                                                                    class="bx bx-edit-alt me-2"></i> Edit</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('about.delete', $data->id) }}"><i
                                                                    class="bx bx-trash me-2"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center fs-5">No image</div>
                        @endif
                    </div>
                </div>
                {{-- Button  --}}
                @if (count($about) < 3)
                    <a style="background-color: #ff6c0f; color:white;" href="{{ route('about.post') }}" class="btn btn-sm-sm"> <i class="bx bx-plus"></i>Add
                        Image</a>
                @endif
            </div>
            {{-- About Description --}}
            <div class="col-lg-4">
                <div class="mb-2 shadow card">
                    <div class="card-header">
                        <div class="fs-4 fw-bolder text-uppercase">Who we are?</div>
                    </div>
                    <div class="card-body">
                        <textarea style="resize: none;" class="form-control" rows="10" readonly>{!! $about_desc->desc !!}</textarea>
                    </div>
                </div>
                <a style="background-color: #ff6c0f; color:white;" href="{{ route('about.desc.edit', $about_desc->id) }}" class="mb-4 btn w-25">
                    <i class="bx bx-edit-alt"></i> Edit
                </a>
            </div>
        </div>
    </div>
    {{-- END OF ABOUT PAGE SECTION  --}}

    {{-- address section  --}}
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Contact Information</h5>
              @foreach ($address as $data)
                  <form action="{{ route('address.edit', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" value="{{$data->address}}">
                </div>
                <div class="form-group">
                  <label for="openHours">Open Hours</label>
                  <input type="text" class="form-control" id="openHours" name="OpenClose" placeholder="Enter open hours" value="{{$data->OpenClose}}">
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="tel" class="form-control" id="phone" name="yphNo" placeholder="Enter phone number" value="{{$data->yphNo}}">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="yEmail" placeholder="Enter email" value="{{$data->yEmail}}">
                </div>
                <div class="form-group">
                  <label for="map_url">Map Link</label>
                  <input type="text" class="form-control" id="map_url" name="map_url" placeholder="Enter Google Map embed link" value="{{$data->map_url}}">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
              @endforeach

            </div>
          </div>

          @if (count($address) == 0)
            <a style="background-color: #ff6c0f; color:white;" href="{{ route('address.post') }}" class="mb-4 btn w-25">
                <i class="bx bx-edit-alt"></i> Add
            </a>
          @endif

    </div>



    {{-- Project section  --}}
    {{-- <div class="mb-5 container-fluid">
        <div class="row">
            <div class="col">
                This is welcome page
            </div>
        </div>
    </div> --}}


@endsection
