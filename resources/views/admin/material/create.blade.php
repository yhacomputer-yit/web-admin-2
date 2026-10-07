@extends('admin.master.master')

@section('title', 'Add Subject Resource')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Add Subject Resource</h4>
    </div>

    @include('admin.material._form', ['action' => route('material.create')])
@endsection
