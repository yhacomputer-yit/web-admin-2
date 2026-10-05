@extends('admin.master.master')

@section('title', 'Edit Drop Out')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Drop Out</h4>
        <div class="text-muted small">
            {{ $dropOut->student?->name ?? 'Unknown student' }} &mdash;
            {{ $dropOut->course?->name ?? 'no course' }} &mdash;
            {{ $dropOut->drop_out_date?->format('j M Y') ?? 'no date' }}
        </div>
    </div>


    @include('admin.dropOut._form', ['action' => route('dropOut.update', ['id' => $dropOut->id])])
@endsection
