@extends('admin.master.master')

@section('content')

<main>
      
    <a href="{{route('admin.course')}}"><button class="btn btn-secondary">Back</button></a>

      <h2>Monthly Course Add</h2>
  <form action="{{route('monthly.update', $monthly->id)}}" method="POST"  enctype="multipart/form-data">
      @csrf
    <!-- Course Selection Dropdown -->
    <div class="mb-3">
      <label for="course" class="form-label">Choose Course</label>
      <select name="course_id" class="form-select" id="course" required>
        <option selected value="{{$monthly->course_id}}" >{{$monthly->course->name}}</option>
        @foreach($courses  as $course)
          <option value="{{$course->id}}" > {{$course->name}} </option>
          @endforeach
      </select>
    </div> 

    <!-- Section Selection Dropdown -->
    <div class="mb-3">
      <label for="section" class="form-label">Choose Section</label>
      <select name="section_id" class="form-select" id="section" required>
        <option selected value="{{$monthly->section_id}}">{{$monthly->section_id}}</option>
        @foreach($sections as $section)
              <option value="{{$section->id}}">{{$section->name}} - {{$section->start}} / {{$section->end}}</option>
          @endforeach
      </select>
    </div>

    <!-- Start Date --> 
    <div class="mb-3">
      <label for="startDate" class="form-label">Start Date</label>
      <input name="start_date" value="{{$monthly->start_date}}" type="date" class="form-control" id="startDate" required>
    </div>

    <!-- End Date -->
    <div class="mb-3">
      <label for="endDate" class="form-label">End Date</label>
      <input name="end_date" value="{{$monthly->end_date}}" type="date" class="form-control" id="endDate" required>
    </div> 

    <!-- Limited Students -->
    <div class="mb-3">
      <label for="limitedStudents" class="form-label">Limited Students</label>
      <input required value="{{$monthly->limited_seat}}" name="limited_seat" type="number" class="form-control" id="limitedStudents" placeholder="Enter student limit" required>
    </div>
    
    <div class="mb-3">
      <label class="form-label" for="m_img">Choose Image</label>
      <input class="form-control" type="file" id="m_img" name="new_m_img">
      <input class="form-control " type="hidden" id="old_m_img" name="old_m_img" value="{{$monthly->m_img}}">
    </div>
 
    @if($monthly->m_img)
        <div class="mb-3">
            <label class="form-label" for="old_image">Current Image</label><br>
            <img src="{{ asset('storage/' . $monthly->m_img) }}" alt="Course Image" width="100px">
        </div>
    @endif

    <div class="mb-3">
      <label class="form-label" for="des">Course Description</label>
      <textarea class="form-control" style="width: 100%" id="des" name="m_desc">{{$monthly->m_desc}}</textarea>
    </div>
    

    <!-- Submit Button -->
    <button type="submit" class="btn btn-outline-primary">Submit</button>
  </form>


  
  </main>

@endsection