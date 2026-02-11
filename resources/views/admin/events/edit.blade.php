{{-- @extends('admin.master.master')

@section('content') --}}

@include("admin.summerNote.summerNote")

    <div class="container">
        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="title" name="title" maxlength="255" required value="{{ old('title', $event->title) }}">
            </div>

            <div class="mb-3">
                <label for="edate" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="edate" name="edate" required value="{{ old('edate', $event->edate) }}">
            </div>

            <div class="mb-3">
                <label for="about" class="form-label">About Event</label>
                <textarea class="form-control" id="summernote" name="about" rows="3" required>{{ old('about', $event->aboute) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Event Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                @if($event->image)
                    <p>Current Image: <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" style="max-width: 100px;"></p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('event.index') }}" class="btn btn-outline-secondary">Back</a>
        </form>

    </div>

{{-- @endsection --}}
