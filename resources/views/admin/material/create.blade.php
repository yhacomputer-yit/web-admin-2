@extends('admin.master.master')

@section('title', 'Add Materials')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Add Reference</h4>
        <div class="text-muted small">
            Pick a course and one of its subjects, say what kind of file this is,
            then upload it. A subject can hold any number of files.
        </div>
    </div>


    @include('admin.material._form', ['action' => route('material.create')])
@endsection
