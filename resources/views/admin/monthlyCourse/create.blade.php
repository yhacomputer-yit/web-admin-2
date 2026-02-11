{{-- @extends('admin.master.master')

@section('content')
  --}}

  @include('admin.summerNote.summerNote')
    <main>
        <div class="container">
            <a href="{{route('admin.course')}}"><button class="btn btn-secondary">Back</button></a>

            <h2>Monthly Course Add</h2>
            <form action="{{route('monthly.create')}}" method="POST"  enctype="multipart/form-data">
                @csrf

            <!-- Course Selection Dropdown -->
            <div class="mb-3">
                <label for="course" class="form-label">Choose Course</label>
                <select name="course_id" class="form-select" id="course" required>
                <option selected disabled>Select a course</option>
                @foreach($courses  as $course)
                    <option value="{{$course->id}}" > {{$course->name}} </option>
                    @endforeach
                </select>
            </div>

            <!-- Section Selection Dropdown -->
            <div class="mb-3">
                <label for="section" class="form-label">Choose Section</label>
                <select name="section_id" class="form-select" id="section" required>
                <option selected disabled>Select a section</option>
                @foreach($sections as $section)
                        <option value="{{$section->id}}">{{$section->name}} - {{$section->start}} / {{$section->end}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date -->
            <div class="mb-3">
                <label for="startDate" class="form-label">Start Date</label>
                <input name="start_date" type="date" class="form-control" id="startDate" required>
            </div>

            <!-- End Date -->
            <div class="mb-3">
                <label for="endDate" class="form-label">End Date</label>
                <input name="end_date" type="date" class="form-control" id="endDate" required>
            </div>

            <!-- Limited Students -->
            <div class="mb-3">
                <label for="limitedStudents" class="form-label">Limited Students</label>
                <input required name="limited_seat" type="number" class="form-control" id="limitedStudents" placeholder="Enter student limit" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="m_img">Choose Image</label>
                <input class="form-control" type="file" id="m_img" name="m_img">
            </div>

            <div class="mb-3">
                <label class="form-label" for="des">Course Description</label>
                <textarea class="form-control" style="width: 100%" id="summernote" name="m_desc"></textarea>
            </div>


            <!-- Submit Button -->
            <button type="submit" class="btn btn-outline-primary">Submit</button>
            </form>

        </div>
    </main>

{{-- @endsection --}}
