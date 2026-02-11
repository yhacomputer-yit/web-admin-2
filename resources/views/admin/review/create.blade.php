@extends('admin.master.master')

@section('content')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="mb-4 fs-4"> <i class="mb-1 fa-solid fa-star fs-3"></i> Add Review</div>
        <div class="col-12 col-md-8 col-lg-6">
            <form action="{{ route('admin.review.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="review" class="form-label">Review</label>
                    <textarea name="review" class="form-control @error('review') is-invalid @enderror" rows="4" required>{{ old('review') }}</textarea>
                    @error('review')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="rating" class="form-label">Rating</label>
                    <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}" {{ old('rating', 5)==$i ? 'selected' : '' }}>{{ $i }} Star{{ $i>1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', 1)==1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', 1)==0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Save</button>
                <a href="{{ route('admin.review.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
