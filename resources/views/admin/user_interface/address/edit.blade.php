@extends('admin.master.master')

@section('content')
 
    <main>
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Contact Information</h5> 
              <form action="{{ route('addresses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 
                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" value="">
                </div>
                <div class="form-group">
                  <label for="openHours">Open Hours</label>
                  <input type="text" class="form-control" id="openHours" name="OpenClose" placeholder="Enter open hours" value="">
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="tel" class="form-control" id="phone" name="yphNo" placeholder="Enter phone number" value="">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="yEmail" placeholder="Enter email" value="">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
            </div>
          </div>
    </main>

@endsection