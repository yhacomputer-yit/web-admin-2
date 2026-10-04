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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @include('admin.material._form', ['action' => route('material.create')])
@endsection
