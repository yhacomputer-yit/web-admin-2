@extends('admin.master.master')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Search by Name" id="searchInput">
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-2">
        <div class="fs-4 mb-4"> <i class="bx bx-user fs-3 mb-1"></i>Student</div>

        <div class="col-12 mb-5">
            <div class="table-responsive text-nowrap bg-light rounded shadow mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Ph No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="studentTableBody">
                        @foreach ($students as $student)
                        <tr class="student-row" data-student="{{ json_encode($student) }}">
                            <td>{{ $student->id }}</td>
                            <td class="student-name">{{ $student->name }}</td>
                            <td class="student-name">{{ $student->email }}</td>
                            <td class="student-name">{{ $student->phone }}</td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a style="background-color: #ff6c0f; color:white;" href="{{ route('student.createPage') }}" class="btn mb-5 d-inline">
                <i class="bx bx-plus"></i> Register Student
            </a>
        </div>
    </div>
</div>

<!-- Modal for showing student details -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <strong>Image:</strong>
                    <img id="modalStudentImage" src="" width="100" alt="Student Image">
                </div>
                <p><strong>ID:</strong> <span id="modalStudentID"></span></p>
                <p><strong>Name:</strong> <span id="modalStudentName"></span></p>
                <p><strong>Email:</strong> <span id="modalStudentEmail"></span></p>
                <p><strong>Phone:</strong> <span id="modalStudentPhone"></span></p>
                <p><strong>Course:</strong> <span id="modalStudentCourse"></span></p>
                <p><strong>Gender:</strong> <span id="modalStudentGender"></span></p>
                <p><strong>Date of Birth:</strong> <span id="modalStudentDOB"></span></p>
                <p><strong>Viber Number:</strong> <span id="modalStudentVbNo"></span></p>
                <p><strong>Father Name:</strong> <span id="modalStudentFather"></span></p>
                <p><strong>Mother Name:</strong> <span id="modalStudentMother"></span></p>
                <p><strong>NRC No:</strong> <span id="modalStudentNRC"></span></p>
                <p><strong>City:</strong> <span id="modalStudentCity"></span></p>
                <p><strong>Township:</strong> <span id="modalStudentTownship"></span></p>
                <p><strong>Education:</strong> <span id="modalStudentEdu"></span></p>
                <p><strong>Enroll Date:</strong> <span id="modalStudentEnroll"></span></p>
                <p><strong>Register Date:</strong> <span id="modalStudentRegister"></span></p>



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

        // Search functionality
        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();
            const studentRows = studentTableBody.getElementsByClassName('student-row');

            Array.from(studentRows).forEach(function (row) {
                const studentName = row.querySelector('.student-name').textContent.toLowerCase();

                if (studentName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Show details in modal when "View Detail" is clicked
        const studentModal = new bootstrap.Modal(document.getElementById('studentModal'));
        const modalStudentID = document.getElementById('modalStudentID');
        const modalStudentName = document.getElementById('modalStudentName');
        const modalStudentEmail = document.getElementById('modalStudentEmail');
        const modalStudentPhone = document.getElementById('modalStudentPhone');
        const modalStudentCourse = document.getElementById('modalStudentCourse');
        const modalStudentImage = document.getElementById('modalStudentImage');
        const modalStudentAddress = document.getElementById('modalStudentAddress');
        const modalStudentGender = document.getElementById('modalStudentGender');
        const modalStudentDOB = document.getElementById('modalStudentDOB');
        const modalStudentVbNo = document.getElementById('modalStudentVbNo');
        const modalStudentFather = document.getElementById('modalStudentFather');
        const modalStudentMother = document.getElementById('modalStudentMother');
        const modalStudentNRC = document.getElementById('modalStudentNRC');
        const modalStudentCity = document.getElementById('modalStudentCity');
        const modalStudentTownship = document.getElementById('modalStudentTownship');
        const modalStudentEdu = document.getElementById('modalStudentEdu');
        const modalStudentEnroll = document.getElementById('modalStudentEnroll');
        const modalStudentRegister = document.getElementById('modalStudentRegister');






        studentTableBody.addEventListener('click', function (event) {
            const target = event.target.closest('.view-detail-link');
            if (target) {
                const row = target.closest('.student-row');
                const studentData = JSON.parse(row.dataset.student);

                // Populate modal with student data
                modalStudentID.textContent = studentData.id;
                modalStudentName.textContent = studentData.name;
                modalStudentEmail.textContent = studentData.email;
                modalStudentPhone.textContent = studentData.phone;
                modalStudentCourse.textContent = studentData.course_id;
                modalStudentImage.src = `/storage/${studentData.image}`;
                modalStudentGender.textContent = studentData.gender == 0 ? 'Female' : 'Male';
                modalStudentDOB.textContent = studentData.date_of_birth;
                modalStudentVbNo.textContent = studentData.viber_phone;
                modalStudentFather.textContent = studentData.father_name;
                modalStudentMother.textContent = studentData.mother_name;
                modalStudentNRC.textContent = studentData.nrc;
                modalStudentCity.textContent = studentData.city;
                modalStudentTownship.textContent = studentData.township;
                modalStudentEdu.textContent = studentData.education;
                modalStudentEnroll.textContent = studentData.enroll_date;
                modalStudentRegister.textContent = studentData.register_date;

                // Show the modal
                studentModal.show();
            }
        });
    });
</script>
@endsection
