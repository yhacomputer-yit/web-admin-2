@extends('admin.master.master')

@section('title', 'Add Certificate')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Add Certificate</h4>
        <div class="text-muted small">
            Record a student who has finished. The certificate starts as not received; tick it
            off when they come back for it.
        </div>
    </div>

    @include('admin.partials.feedback')

    @include('admin.certificate._form', ['action' => route('certificate.create')])
@endsection