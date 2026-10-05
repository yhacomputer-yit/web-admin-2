@extends('admin.master.master')

@section('title', 'Edit Certificate')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Certificate</h4>
        <div class="text-muted small">
            {{ $certificate->student?->name ?? 'Unknown student' }} &mdash;
            {{ $certificate->statusLabel() }}
            @if ($certificate->complete_date)
                &mdash; completed {{ $certificate->complete_date->format('j M Y') }}
            @endif
        </div>
    </div>


    @include('admin.certificate._form', ['action' => route('certificate.update', ['id' => $certificate->id])])
@endsection
