{{-- @extends('admin.master.master')

@section('content') --}}
@include("admin.summerNote.summerNote");

    <div class="container">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="title" name="title" maxlength="255" required value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label for="edate" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="edate" name="edate" required>
            </div>

            <div class="mb-3">
                <label for="about" class="form-label">About Event</label>
                <textarea id="summernote" class="form-control" id="about" name="about" rows="3" required>{{ old('about') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Event Poster Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>

            <h5>Event Detail Images</h5>
            <div class="mb-3" id="details">

            </div>

            <span id="add_img" class="btn btn-secondary mb-5">Add Images</span> <br>

            <button type="submit" class="btn btn-primary">Add Event</button>
            <a href="{{route("event.index")}}" class="btn btn-outline-secondary">Back</a>
        </form>

    </div>

    <script>
        let addBtn = document.querySelector("#add_img");
        let details = document.querySelector("#details");

        addBtn.addEventListener("click", function() {
            // Create a new container div for the file input and cancel button
            let fileContainer = document.createElement("div");
            fileContainer.classList.add("file-container", "d-flex", "my-2");

            // Create the file input element
            let fileInput = document.createElement("input");
            fileInput.type = "file";
            fileInput.classList.add("form-control");
            fileInput.name = "details_image[]";
            fileInput.accept = "image/*";
            fileInput.required = true;

            // Create the cancel button
            let cancelBtn = document.createElement("span");
            cancelBtn.classList.add("btn", "btn-danger", "cancel_img");
            cancelBtn.textContent = "X";

            // Append the input and cancel button to the container div
            fileContainer.appendChild(fileInput);
            fileContainer.appendChild(cancelBtn);

            // Append the container to the details section
            details.appendChild(fileContainer);

            // Add event listener for the cancel button to remove the file input
            cancelBtn.addEventListener("click", function() {
                fileContainer.remove();
            });
        });
    </script>



{{-- @endsection --}}
