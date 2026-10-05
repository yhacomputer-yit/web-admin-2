@extends('admin.master.master')

@section('title', 'Record Drop Out')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Record Drop Out</h4>
        <div class="text-muted small">
            Pick the student and the course they left, then the day. Saving closes that
            course's enrollment for them.
        </div>
    </div>

    @include('admin.partials.feedback')

    @include('admin.dropOut._form', ['action' => route('dropOut.create')])
@endsection