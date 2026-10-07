@extends('admin.master.master')

@section('title', 'Add Grading Result')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Add Grading Result</h4>
        <div class="text-muted small">
            Pick the student and the subject, then the mark. The band can wait until it is graded.
        </div>
    </div>


    @include('admin.gradingResult._form', ['action' => route('gradingResult.create')])
@endsection
