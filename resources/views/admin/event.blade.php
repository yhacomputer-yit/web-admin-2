@extends('admin.master.master')

@section('content')

    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{route("event.create")}}" class="btn btn-primary mb-4">
            <i class="fa-solid fa-plus"></i>
            Add
        </a>
        <h4>Events we held</h4>

        <table>

            <div class="table-responsive text-nowrap bg-light rounded shadow mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>About</th>
                            <th>Date</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($results as $res)
                        <tr>
                            <td>{{ $res->id}}</td>
                            <td>{{ $res->title}}</td>
                            <td>{!! $res->aboute !!}</td>
                            <td>{{ $res->edate }}</td>
                            <td><img width="50px" src="{{ asset('storage/'.$res->image) }}" alt=""></td>
                            <td>
                                <a href="{{route("events.update", $res->id)}}" class="btn btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="{{route("event.delete", $res->id)}}" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </table>


    </div>

@endsection
