@extends('admin.master.master')

@section('title', 'Edit Grade Band')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Grade Band</h4>
        <div class="text-muted small">
            {{ $grading->label() }} &mdash; {{ $grading->results_count }} mark(s) filed under it
        </div>
    </div>


    @include('admin.grading._form', ['action' => route('grading.update', ['id' => $grading->id])])
@endsection
