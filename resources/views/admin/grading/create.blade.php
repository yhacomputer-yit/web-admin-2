@extends('admin.master.master')

@section('title', 'Add Grade Band')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Add Grade Band</h4>
        <div class="text-muted small">
            A name and the top mark of that band. Marks pick one of these when they are graded.
        </div>
    </div>


    @include('admin.grading._form', ['action' => route('grading.create')])
@endsection
