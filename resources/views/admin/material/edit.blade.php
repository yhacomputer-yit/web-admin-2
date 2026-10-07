@extends('admin.master.master')

@section('title', 'Edit Subject Resource')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Subject Resource</h4>
    </div>

    @include('admin.material._form', ['action' => route('material.update', ['id' => $material->id])])
@endsection
