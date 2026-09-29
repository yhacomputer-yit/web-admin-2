@extends('admin.master.master')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Search by Name / Username / Phone" id="searchInput">
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-2">
        <div class="fs-4 mb-4"><i class="bx bx-user fs-3 mb-1"></i>Student</div>

        <div class="col-12 mb-5">
            <div class="table-responsive text-nowrap bg-light rounded shadow mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Ph No</th>
                            <th>Email</th>
                            <th>Courses</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="studentTableBody">
                        @forelse ($students as $student)
                            <tr class="student-row"
                                data-student="{{ json_encode([
                                    'id' => $student->id,
                                    'name' => $student->name,
                                    'nickname' => $student->nickname,
                                    'username' => $student->username,
                                    'email' => $student->email,
                                    'phone' => $student->phone,
                                    'viber_phone' => $student->viber_phone,
                                    'telegram_username' => $student->telegram_username,
                                    'address' => $student->address,
                                    'facebook_acc_name' => $student->facebook_acc_name,
                                    'date_of_birth' => $student->date_of_birth?->format('Y-m-d'),
                                    'nrc' => $student->nrc,
                                    'gender' => $student->gender,
                                    'education' => $student->education,
                                    'native_town' => $student->native_town,
                                    'religious_status' => $student->religious_status,
                                    'race' => $student->race,
                                    'father_name' => $student->father_name,
                                    'mother_name' => $student->mother_name,
                                    'image' => $student->image,
                                    'status' => $student->status,
                                    'enrollments' => $student->enrollments->map(fn($e) => [
                                        'course' => $e->course?->name,
                                        'section' => $e->section?->name,
                                        'enroll_date' => $e->enroll_date?->format('Y-m-d'),
                                    ]),
                                ], JSON_UNESCAPED_UNICODE) }}">
                                <td>{{ $student->id }}</td>
                                <td class="student-name">{{ $student->name }}</td>
                                <td class="student-name">{{ $student->username ?? '—' }}</td>
                                <td><code class="text-muted">••••••••</code></td>
                                <td class="student-name">{{ $student->phone }}</td>
                                <td class="student-name">{{ $student->email }}</td>
                                <td>{{ $student->enrollments_count }}</td>
                                <td>
                                    <span class="badge {{ $student->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="text-decoration-none me-2 view-detail-link">
                                        <i class="bx bx-show me-1"></i> View Detail
                                    </a>
                                    <a href="{{ route('student.edit', ['id' => $student->id]) }}" class="text-decoration-none me-2">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('student.delete', ['id' => $student->id]) }}" class="text-decoration-none text-danger">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No students yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $students->links() }}

            <a style="background-color: #ff6c0f; color:white;" href="{{ route('student.createPage') }}" class="btn mb-5 d-inline">
                <i class="bx bx-plus"></i> Add Student
            </a>
        </div>
    </div>
</div>

<!-- Modal for showing student details -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img id="modalStudentImage" src="/image/no-image.jpg" width="120" height="120"
                        class="rounded-circle object-fit-cover" alt="Student Image">
                </div>
                <div class="row g-3">
                    <div class="col-md-4"><strong>ID:</strong> <span id="mId"></span></div>
                    <div class="col-md-4"><strong>Name:</strong> <span id="mName"></span></div>
                    <div class="col-md-4"><strong>Nickname:</strong> <span id="mNickname"></span></div>
                    <div class="col-md-4"><strong>Username:</strong> <span id="mUsername"></span></div>
                    <div class="col-md-4"><strong>Status:</strong> <span id="mStatus"></span></div>
                    <div class="col-md-4"><strong>Gender:</strong> <span id="mGender"></span></div>
                    <div class="col-md-4"><strong>Father Name:</strong> <span id="mFather"></span></div>
                    <div class="col-md-4"><strong>Mother Name:</strong> <span id="mMother"></span></div>
                    <div class="col-md-4"><strong>Phone:</strong> <span id="mPhone"></span></div>
                    <div class="col-md-4"><strong>Viber:</strong> <span id="mViber"></span></div>
                    <div class="col-md-4"><strong>Telegram:</strong> <span id="mTelegram"></span></div>
                    <div class="col-md-4"><strong>Email:</strong> <span id="mEmail"></span></div>
                    <div class="col-md-4"><strong>Facebook:</strong> <span id="mFacebook"></span></div>
                    <div class="col-md-4"><strong>Date of Birth:</strong> <span id="mDob"></span></div>
                    <div class="col-md-4"><strong>NRC No:</strong> <span id="mNrc"></span></div>
                    <div class="col-md-4"><strong>Education:</strong> <span id="mEducation"></span></div>
                    <div class="col-md-4"><strong>Native Town:</strong> <span id="mNativeTown"></span></div>
                    <div class="col-md-4"><strong>Religion:</strong> <span id="mReligion"></span></div>
                    <div class="col-md-4"><strong>Race:</strong> <span id="mRace"></span></div>
                    <div class="col-12"><strong>Address:</strong> <span id="mAddress"></span></div>
                </div>

                <hr>
                <h6>Enrolled Courses</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Section</th>
                                <th>Enroll Date</th>
                            </tr>
                        </thead>
                        <tbody id="mEnrollments"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const studentTableBody = document.getElementById('studentTableBody');

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();
            const studentRows = studentTableBody.getElementsByClassName('student-row');

            Array.from(studentRows).forEach(function (row) {
                const text = row.querySelector('.student-name').textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        const studentModal = new bootstrap.Modal(document.getElementById('studentModal'));
        const set = (id, value) => {
            document.getElementById(id).textContent = (value === null || value === undefined || value === '') ? '-' : value;
        };

        studentTableBody.addEventListener('click', function (event) {
            const target = event.target.closest('.view-detail-link');
            if (!target) return;

            const row = target.closest('.student-row');
            const d = JSON.parse(row.dataset.student);

            document.getElementById('modalStudentImage').src = d.image ? `/storage/${d.image}` : '/image/no-image.jpg';

            set('mId', d.id);
            set('mName', d.name);
            set('mNickname', d.nickname);
            set('mUsername', d.username);
            set('mStatus', d.status);
            set('mGender', d.gender);
            set('mFather', d.father_name);
            set('mMother', d.mother_name);
            set('mPhone', d.phone);
            set('mViber', d.viber_phone);
            set('mTelegram', d.telegram_username);
            set('mEmail', d.email);
            set('mFacebook', d.facebook_acc_name);
            set('mDob', d.date_of_birth);
            set('mNrc', d.nrc);
            set('mEducation', d.education);
            set('mNativeTown', d.native_town);
            set('mReligion', d.religious_status);
            set('mRace', d.race);
            set('mAddress', d.address);

            const body = document.getElementById('mEnrollments');
            body.innerHTML = '';
            (d.enrollments || []).forEach(function (e) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td>' + (e.course || '-') + '</td><td>' + (e.section || '-') + '</td><td>' + (e.enroll_date || '-') + '</td>';
                body.appendChild(tr);
            });
            if (!(d.enrollments || []).length) {
                body.innerHTML = '<tr><td colspan="3" class="text-muted">No enrollments</td></tr>';
            }

            studentModal.show();
        });
    });
</script>
@endsection
