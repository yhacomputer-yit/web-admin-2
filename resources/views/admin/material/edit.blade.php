@extends('admin.master.master')

@section('title', 'Edit Materials')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Materials</h4>
        <div class="text-muted small">
            {{ $material->course?->name ?? 'Unknown course' }} &rarr;
            {{ $material->subject?->name ?? 'Unknown subject' }} &rarr;
            {{ $material->displayTitle() }}
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @include('admin.material._form', ['action' => route('material.update', ['id' => $material->id])])
@endsection
