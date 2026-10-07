@extends('admin.master.master')

@section('title', 'Exam Answer')

@section('content')
    @php
        use App\Support\StoredFile;
        use App\Support\TimeOfDay;
        use Illuminate\Support\Facades\Storage;

        /* One read of a script: what it is called, how big it is,
           and whether it is still on the disk. A row can outlive its
           upload -- a file removed off disk, or a restore that skipped
           storage -- so a filled-in path is not the same as a readable
           file, and the list has to be able to say "missing" rather
           than offer a download that will not come. */
        $script = function (?string $path): array {
            if (blank($path)) {
                return ['label' => null, 'size' => null, 'missing' => false];
            }

            $label = StoredFile::label($path);

            if (! Storage::disk('public')->exists($path)) {
                return ['label' => $label, 'size' => null, 'missing' => true];
            }

            $bytes = Storage::disk('public')->size($path);

            foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
                if ($bytes < 1024) {
                    return [
                        'label' => $label,
                        'size' => round($bytes, $unit === 'B' ? 0 : 1) . ' ' . $unit,
                        'missing' => false,
                    ];
                }

                $bytes /= 1024;
            }

            return ['label' => $label, 'size' => round($bytes, 1) . ' TB', 'missing' => false];
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Exam Answer</h4>
            <div class="text-muted small">
                {{ $exam->subject?->name ?? 'Subject #' . $exam->subject_id }}
                &middot; {{ $exam->course?->name ?? 'Course #' . $exam->course_id }}
                &middot; {{ $exam->exam_date?->format('d M Y') ?? '—' }},
                {{ TimeOfDay::format($exam->start_time) ?? '—' }}&ndash;{{ TimeOfDay::format($exam->end_time) ?? '—' }}
            </div>
        </div>
        <a href="{{ route('exam.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i> All Exams
        </a>
    </div>

    <div class="card">
        @if ($answers->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bx bx-file" style="font-size: 2rem; color: #cbd5e1;"></i>
                <div class="fw-semibold mt-2">No scripts handed in yet</div>
                <div class="text-muted small mb-3">
                    Scripts students upload from the exam page appear here, newest first.
                </div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <caption class="visually-hidden">
                        Scripts handed in for {{ $exam->subject?->name ?? 'this subject' }}, newest first
                    </caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Student</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Script</th>
                            <th scope="col" class="text-end">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answers as $answer)
                            @php($file = $script($answer->answer_file))
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $answer->student->name ?? 'Student #' . $answer->student_id }}
                                    </div>
                                    {{-- <div class="text-muted small">
                                        @if ($answer->student?->username)
                                            @{{ $answer->student->username }}
                                            &middot;
                                        @endif
                                        @if ($answer->student?->status)
                                            {{ ucfirst($answer->student->status) }}
                                        @endif
                                    </div> --}}
                                </td>
                                <td class="text-nowrap">
                                    {{ $answer->submitted_date?->format('d M Y, g:i A') ?? '—' }}
                                </td>
                                <td>
                                    @if ($file['label'] === null)
                                        <span class="text-muted small">Recorded, no file on disk</span>
                                    @else
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bx bx-file"></i>
                                            <span class="text-truncate" style="max-width: 18rem;" title="{{ $file['label'] }}">
                                                {{ $file['label'] }}
                                            </span>
                                            @if ($file['missing'])
                                                <span class="badge text-bg-danger">missing</span>
                                            @else
                                                <span class="text-muted small">{{ $file['size'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    @if ($file['label'] !== null && ! $file['missing'])
                                        <a href="{{ route('exam.scripts.download', $answer->id) }}"
                                            class="btn btn-sm btn-outline-secondary me-1"
                                            title="Download {{ $file['label'] }}">
                                            <i class="bx bx-download me-1"></i>Download
                                        </a>
                                        <form action="{{ route('exam.scripts.delete', $answer->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this script? This cannot be undone.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Delete this script">
                                                <i class="bx bx-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted small">
                {{ $answers->count() }} script(s) handed in &middot; {{ $enrolled }} enrolled in this course
            </div>
        @endif
    </div>
@endsection
