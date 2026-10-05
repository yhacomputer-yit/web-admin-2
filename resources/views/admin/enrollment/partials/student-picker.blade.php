{{--
    The student picker now lives with the other shared admin partials, because four
    pages pick a student and not all of them are about an enrollment. Kept as an
    include of its own so the enrollment form does not have to know that.
--}}
@include('admin.partials.student-picker', ['selectedStudent' => $selectedStudent ?? null])