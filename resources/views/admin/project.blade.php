@extends('admin.master.master')

@section('content')
    <div class="container-fluid">

        {{-- Section Data Table  --}} 
        {{-- Section  --}}
        <div class="row mb-2">

            {{-- Class Section  --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div class="fs-4"> <i class="bx bx-folder fs-3 mb-1"></i>Projects</div>
                <a style="background-color: #ff6c0f; color:white;" href="{{ route('project.createPage') }}" class="btn d-inline"> <i
                        class="bx bx-plus"></i> Add Project</a>
            </div>

            <div class="col-12 mb-5">
                @if (count($projects) > 0)
                    <div class="table-responsive text-nowrap bg-light rounded shadow mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($projects as $data)
                                    <tr>
                                        <td> #{{ $data->id }} </td>
                                        <td> {{ $data->student ?? '—' }} </td>
                                        <td> {{ $data->course }} </td>
                                        <td> {{ $data->title }} </td>
                                        <td> {{ \Illuminate\Support\Str::limit($data->desc, 60) }} </td>
                                        <td> 
                                            <img src="{{ asset('storage/'.$data->image) }}" alt="" width="70">
                                        </td>
                                        <td> {{ $data->created_at?->format('Y-m-d H:i') ?? '—' }} </td>
                                        <td> {{ $data->updated_at?->format('Y-m-d H:i') ?? '—' }} </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('project.edit', $data->id) }}"><i
                                                            class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('project.delete', $data->id) }}"
                                                        data-confirm="Delete {{ $data->title }}?"><i
                                                            class="bx bx-trash me-1"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="fs-6 text-uppercase text-center my-4">No Record!</div>
                @endif
                {{ $projects->links() }}
            </div>

        </div>
    </div>
@endsection
