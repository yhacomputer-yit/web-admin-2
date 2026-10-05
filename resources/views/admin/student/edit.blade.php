@extends('admin.master.master')

@section('content')

<main>
    <div class="container-fluid p-2 p-md-4">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-10 col-lg-9">

                <div class="card my-3 border-warning shadow">
                    {{-- Card Header  --}}
                    <div class="card-header border-warning">
                        <h3 class="h5 text-primary"><i class="bx bx-user fs-3"></i> Edit Student</h3>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('student.update', $student->id) }}" method="POST" enctype="multipart/form-data" id="studentForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $student->id }}">

                            {{-- Current image  --}}
                            @if ($student->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->name }}"
                                        class="rounded" width="120">
                                </div>
                            @endif

                            {{-- Login credentials: auto generated, editable by admin  --}}
                            <div class="card mb-4 border-primary">
                                <div class="card-header bg-light">
                                    <span class="fw-semibold"><i class="bx bx-key me-1"></i> Login Credentials</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <label for="username" class="form-label h6 my-2">Username</label>
                                            <input type="text" name="username" id="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                value="{{ old('username', $student->username) }}" readonly>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="password" class="form-label h6 my-2">Password</label>
                                            <input type="text" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                value="{{ old('password') }}" placeholder="Click Generate to reset" readonly>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn w-100" style="background-color: #ff6c0f; color: white;"
                                                id="generateBtn">
                                                <i class="bx bx-refresh"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Username: first 3 letters of the name + "yha" + 2 random digits (e.g. Ayeyha13).
                                        Password: "yha" + 5 random digits (e.g. yha12943).
                                    </small>
                                </div>
                            </div>

                            {{-- Personal info  --}}
                            <h5 class="text-primary mb-3">Personal Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label h6 my-2">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $student->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nickname" class="form-label h6 my-2">Nick Name</label>
                                    <input type="text" name="nickname" id="nickname"
                                        class="form-control @error('nickname') is-invalid @enderror"
                                        value="{{ old('nickname', $student->nickname) }}">
                                    @error('nickname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="father_name" class="form-label h6 my-2">Father Name</label>
                                    <input type="text" name="father_name" id="father_name"
                                        class="form-control @error('father_name') is-invalid @enderror"
                                        value="{{ old('father_name', $student->father_name) }}">
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="mother_name" class="form-label h6 my-2">Mother Name</label>
                                    <input type="text" name="mother_name" id="mother_name"
                                        class="form-control @error('mother_name') is-invalid @enderror"
                                        value="{{ old('mother_name', $student->mother_name) }}">
                                    @error('mother_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="phone" class="form-label h6 my-2">Phone Number</label>
                                    <input type="text" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $student->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="viber_phone" class="form-label h6 my-2">Viber Phone Number</label>
                                    <input type="text" name="viber_phone" id="viber_phone"
                                        class="form-control @error('viber_phone') is-invalid @enderror"
                                        value="{{ old('viber_phone', $student->viber_phone) }}">
                                    @error('viber_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="telegram_username" class="form-label h6 my-2">Telegram Username</label>
                                    <input type="text" name="telegram_username" id="telegram_username"
                                        class="form-control @error('telegram_username') is-invalid @enderror"
                                        value="{{ old('telegram_username', $student->telegram_username) }}">
                                    @error('telegram_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label h6 my-2">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $student->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label h6 my-2">Address</label>
                                    <input type="text" name="address" id="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address', $student->address) }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="facebook_acc_name" class="form-label h6 my-2">Facebook Account Name</label>
                                    <input type="text" name="facebook_acc_name" id="facebook_acc_name"
                                        class="form-control @error('facebook_acc_name') is-invalid @enderror"
                                        value="{{ old('facebook_acc_name', $student->facebook_acc_name) }}">
                                    @error('facebook_acc_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label h6 my-2">Birthday (DOB)</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth"
                                        class="form-control @error('date_of_birth') is-invalid @enderror"
                                        value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nrc" class="form-label h6 my-2">NRC Number</label>
                                    <input type="text" name="nrc" id="nrc"
                                        class="form-control @error('nrc') is-invalid @enderror"
                                        value="{{ old('nrc', $student->nrc) }}">
                                    @error('nrc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label h6 my-2">Gender</label>
                                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="male" @selected(old('gender', $student->gender) === 'male')>Male</option>
                                        <option value="female" @selected(old('gender', $student->gender) === 'female')>Female</option>
                                        <option value="other" @selected(old('gender', $student->gender) === 'other')>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="education" class="form-label h6 my-2">Education</label>
                                    <input type="text" name="education" id="education"
                                        class="form-control @error('education') is-invalid @enderror"
                                        value="{{ old('education', $student->education) }}">
                                    @error('education')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="native_town" class="form-label h6 my-2">Native Town</label>
                                    <input type="text" name="native_town" id="native_town"
                                        class="form-control @error('native_town') is-invalid @enderror"
                                        value="{{ old('native_town', $student->native_town) }}">
                                    @error('native_town')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="religious_status" class="form-label h6 my-2">Religious Status</label>
                                    <input type="text" name="religious_status" id="religious_status"
                                        class="form-control @error('religious_status') is-invalid @enderror"
                                        value="{{ old('religious_status', $student->religious_status) }}">
                                    @error('religious_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="race" class="form-label h6 my-2">Race</label>
                                    <input type="text" name="race" id="race"
                                        class="form-control @error('race') is-invalid @enderror"
                                        value="{{ old('race', $student->race) }}">
                                    @error('race')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label h6 my-2">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="inactive" @selected(old('status', $student->status) === 'inactive')>Inactive</option>
                                        <option value="active" @selected(old('status', $student->status) === 'active')>Active</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Only active students can log in.</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="image" class="form-label h6 my-2">Student Image</label>
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Enrollments: read-only here, managed on the Course Enrollment page  --}}
                            <h5 class="text-primary mt-4 mb-3">Course Enrollment</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Section</th>
                                            <th>Enroll Date</th>
                                            <th style="width: 90px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($student->enrollments as $enrollment)
                                            <tr>
                                                <td>{{ $enrollment->course?->name ?? '—' }}</td>
                                                <td>{{ $enrollment->section?->name ?? '—' }}</td>
                                                <td>{{ $enrollment->enroll_date?->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('enrollment.edit', ['id' => $enrollment->id]) }}"
                                                        class="text-decoration-none">
                                                        <i class="bx bx-edit-alt"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">
                                                    Not enrolled in any course yet
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('enrollment.createPage') }}" class="btn btn-sm d-inline"
                                style="background-color: #ff6c0f; color: white;">
                                <i class="bx bx-plus"></i> Add Enrollment
                            </a>

                            <button type="submit" class="btn mt-4" style="background-color: #ff6c0f; color: white;">
                                <i class="bx bx-up-arrow-alt"></i> Update Student
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

{{-- Back button at bottom --}}
<a href="{{ route('admin.student') }}" class="btn btn-back">
    <i class="bx bx-left-arrow-alt"></i> Back to Students
</a>

@include('admin.student.partials.credential-script')

@endsection
