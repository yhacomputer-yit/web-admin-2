{{-- @extends('admin.master.master')

@section('content') --}}
@include("admin.summerNote.summerNote")
<main>
    <div class="container-fluid p-2 p-md-4">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8">
                <a href="{{ route('admin.course') }}" class="btn btn-secondary">
                    <i class="bx bx-left-arrow-alt"></i> Back
                </a>

                <div class="card my-3 border-warning shadow">
                    {{-- Card Header  --}}
                    <div class="card-header border-primary">
                        <h3 class="h5 text-primary">
                            <i class="bx bx-edit-alt fs-3"></i> Edit Course
                        </h3>
                    </div>

                    {{-- Card Image  --}}
                    <img src="{{ asset('storage/'.$data->image) }}" alt="" class="card-img-top p-3 rounded">

                    {{-- Card Body  --}}
                    <div class="card-body">
                        {{-- Insert image  --}}
                        <form action="{{ route('course.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{ $data->id }}">

                            {{-- Title  --}}
                            <div class="mb-3 form-group">
                                <label for="name" class="form-label h6 my-2">Course Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $data->name }}" id="name" placeholder="Course Name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description  --}}
                            <div class="mb-3 form-group">
                                <label for="description" class="form-label h6 my-2">Course Description</label>
                                <textarea name="description" rows="7" style="resize: none;" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Course Description">{{ $data->description }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-group">
                                <label for="type" class="form-label h6 my-2">Course Types</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="">{{ $course_types->name}}</option>
                                    <option value="1">Programming</option>
                                    <option value="2">Graphic Design</option>
                                    <option value="3">ICT</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Duration  --}}
                            <div class="mb-3 form-group">
                                <label for="duration" class="form-label h6 my-2">Duration</label>
                                <input type="number" name="duration" value="{{ $data->duration }}" class="form-control @error('duration') is-invalid @enderror" id="duration" placeholder="Course Duration">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Normal Price  --}}
                            <div class="mb-3 form-group">
                                <label for="normal_price" class="form-label h6 my-2">Normal Price</label>
                                <input type="number" name="normal_price" value="{{ $data->normal_price }}" class="form-control @error('normal_price') is-invalid @enderror" id="normal_price" placeholder="Normal Price">
                                @error('normal_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Special Price  --}}
                            <div class="mb-3 form-group">
                                <label for="special_price" class="form-label h6 my-2">Special Price</label>
                                <input type="number" name="special_price" value="{{ $data->special_price }}" class="form-control @error('special_price') is-invalid @enderror" id="special_price" placeholder="Special Price">
                                @error('special_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- About  --}}
                            <div class="mb-3 form-group">
                                <label for="about" class="form-label h6 my-2">About</label>
                                <textarea name="about" rows="7" style="resize: none;" class="form-control @error('about') is-invalid @enderror" id="summernote" placeholder="About">{{ $data->about }}</textarea>
                                @error('about')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Link  --}}
                            <div class="mb-3 form-group">
                                <label for="link" class="form-label h6 my-2">Link</label>
                                <textarea name="link" rows="3" style="resize: none;" class="form-control @error('link') is-invalid @enderror" id="link" placeholder="Link">{{ $data->links }}</textarea>
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Image  --}}
                            <div class="mb-3 form-group">
                                <label for="image" class="form-label h6 my-2">Choose New Image</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button style="background-color: #ff6c0f; color:white;" class="btn mt-3">
                                <i class="bx bx-up-arrow-alt"></i> Update
                            </button>
                            <a href="{{ route('course.delete', $data->id) }}" class="btn btn-danger mt-3">
                                <i class="bx bx-trash"></i> Delete
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- @endsection --}}
