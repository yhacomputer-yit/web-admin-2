@extends('admin.master.master')

@section('title', 'Edit Exam')

@section('content')
    <div class="mb-3">
        <h4 class="mb-1">Edit Exam</h4>
        <div class="text-muted small">
            {{ $exam->course?->name ?? 'Unknown course' }} &rarr;
            {{ $exam->subject?->name ?? 'Unknown subject' }} &rarr;
            {{ $exam->exam_date?->format('d M Y') ?? 'no date' }}
        </div>
        <div class="text-muted small">
            @if ($exam->isPublished())
                Published — students can open it while the window is open.
            @else
                Not published — hidden from students until you publish it.
            @endif
            Currently {{ \App\Models\ExamQuestion::SCHEDULE_STATUSES[$exam->scheduleStatus()] }}.
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @include('admin.exam._form', ['action' => route('exam.update', ['id' => $exam->id])])
@endsection