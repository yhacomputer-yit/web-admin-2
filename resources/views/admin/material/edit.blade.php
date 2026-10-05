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


    @include('admin.material._form', ['action' => route('material.update', ['id' => $material->id])])
@endsection
