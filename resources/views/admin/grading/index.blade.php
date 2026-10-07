@extends('admin.master.master')

@section('title', 'Grading')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Grading</h4>
            <div class="text-muted small">
                The bands a mark is filed into: a name and the top mark of that band. Marks
                pick one of these on
                <a href="{{ route('gradingResult.index') }}" class="text-decoration-none">Marks</a>.
            </div>
        </div>
        <a href="{{ route('grading.createPage') }}" class="btn" style="background-color: #ff6c0f; color: white;">
            <i class="bx bx-plus me-1"></i> Add Band
        </a>
    </div>


    <div class="card">
        @if ($gradings->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bx bx-award" style="font-size: 2rem; color: #cbd5e1;"></i>
                <div class="fw-semibold mt-2">No grade bands yet</div>
                <div class="text-muted small mb-3">
                    Add the bands your school uses &mdash; A up to 100, B up to 80 &mdash; before
                    recording marks, so a mark has something to be filed against.
                </div>
                <a href="{{ route('grading.createPage') }}" class="btn btn-sm" style="background-color: #ff6c0f; color: white;">
                    <i class="bx bx-plus me-1"></i> Add Band
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <caption class="visually-hidden">Grade bands, highest top mark first</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Band</th>
                            <th scope="col">Top mark</th>
                            <th scope="col">Marks filed</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gradings as $grading)
                            <tr>
                                <td class="fw-semibold">{{ $grading->name }}</td>
                                <td>{{ $grading->scoreLabel() }}</td>
                                <td>
                                    <span class="badge bg-light text-muted">{{ $grading->results_count }}</span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('grading.edit', ['id' => $grading->id]) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit this band">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a href="{{ route('grading.delete', ['id' => $grading->id]) }}"
                                        class="btn btn-sm btn-outline-danger" title="Delete this band"
                                        data-confirm="Delete the {{ $grading->name }} band?{{ $grading->results_count > 0 ? ' It is used by ' . $grading->results_count . ' mark(s), so this will be refused.' : '' }}">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($gradings->isNotEmpty())
        <p class="text-muted small mt-2 mb-0">
            <i class="bx bx-info-circle me-1"></i>
            A band that marks are already filed under cannot be deleted &mdash; the delete is
            refused rather than quietly un-grading those marks. Move them to another band first.
        </p>
    @endif
@endsection
