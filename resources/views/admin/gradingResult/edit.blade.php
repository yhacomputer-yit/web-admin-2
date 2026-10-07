@extends('admin.master.master')

@section('title', 'Edit Grading Result')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Grading Result</h4>
        <div class="text-muted small">
            {{ $result->student?->name ?? 'Unknown student' }} &mdash;
            {{ $result->subject?->name ?? 'Unknown subject' }} &mdash;
            {{ $result->isGraded() ? ($result->grade?->name ?? 'Band #' . $result->grade_id) : 'not graded' }}
        </div>
    </div>


    @include('admin.gradingResult._form', ['action' => route('gradingResult.update', ['id' => $result->id])])
@endsection
