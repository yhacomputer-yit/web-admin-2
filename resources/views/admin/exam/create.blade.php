@extends('admin.master.master')

@section('title', 'Schedule Exam')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Schedule Exam</h4>
        <div class="text-muted small">
            Pick a course and one of its subjects, set the day and the window, then add the question
            paper. Leave it unpublished while you prepare it — students cannot see an unpublished exam
            at all, not even the date.
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @include('admin.exam._form', ['action' => route('exam.create')])
@endsection