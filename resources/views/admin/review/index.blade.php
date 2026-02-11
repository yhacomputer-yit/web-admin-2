@extends('admin.master.master')

@section('content')
<style>
    .review-avatar-admin {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ffb347;
        box-shadow: 0 2px 8px rgba(255,107,1,0.10);
        background: #fff7f0;
    }
    .review-row-admin:hover {
        background: #fff7f0;
        box-shadow: 0 2px 12px rgba(255,107,1,0.07);
    }
    .review-status-badge {
        font-size: 0.95em;
        padding: 0.4em 1em;
        border-radius: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .review-status-badge.active {
        background: #e6f9e6;
        color: #1a7f37;
        border: 1px solid #b6e6b6;
    }
    .review-status-badge.inactive {
        background: #f7eaea;
        color: #b30000;
        border: 1px solid #f5b6b6;
    }
    .review-action-btns .btn {
        margin-right: 0.3em;
    }
    .review-table-responsive {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(255,107,1,0.07);
        background: #fff;
    }
    @media (max-width: 767.98px) {
        .review-avatar-admin { width: 32px; height: 32px; }
        .review-table-responsive { border-radius: 10px; }
    }
</style>
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row mb-2">
        <div class="fs-4 mb-4"> <i class="fa-solid fa-star fs-3 mb-1"></i> Student Reviews</div>
        <div class="col-12 mb-3">
            <a href="{{ route('admin.review.create') }}" class="btn btn-primary mb-3">
                <i class="fa-solid fa-plus"></i> Add Review
            </a>
            <div class="table-responsive review-table-responsive mb-3">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($reviews as $review)
                        <tr class="review-row-admin">
                            <td class="fw-bold">{{ $review->id }}</td>
                            <td>
                                @if($review->photo)
                                    <img src="{{ asset('storage/'.$review->photo) }}" alt="photo" class="review-avatar-admin">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->name) }}&background=ffb347&color=fff" class="review-avatar-admin" alt="{{ $review->name }}">
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $review->name }}</td>
                            <td style="max-width: 260px; white-space: normal;">{{ Str::limit($review->review, 80) }}</td>
                            <td>
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star" style="color: #ffb347;"></i>
                                    @else
                                        <i class="fa-regular fa-star" style="color: #ffb347;"></i>
                                    @endif
                                @endfor
                            </td>
                            <td>
                                @if($review->status)
                                    <span class="review-status-badge active"><i class="fa-solid fa-check-circle"></i> Active</span>
                                @else
                                    <span class="review-status-badge inactive"><i class="fa-solid fa-clock"></i> Pending</span>
                                @endif
                            </td>
                            <td class="review-action-btns">
                                <a href="{{ route('admin.review.edit', $review->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                <form action="{{ route('admin.review.toggleStatus', $review->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $review->status ? 'btn-secondary' : 'btn-success' }}" title="{{ $review->status ? 'Reject' : 'Approve' }}">
                                        @if($review->status)
                                            <i class="fa-solid fa-xmark"></i>
                                        @else
                                            <i class="fa-solid fa-check"></i>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.review.destroy', $review->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
