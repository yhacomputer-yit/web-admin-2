<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeachController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\CourseSectionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\FrontendSectionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DropOutController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\GradingResultController;

// Homepage route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('user.home');


    // consumer will see first this routes
    Route::get('/courses', [FrontendSectionController::class, 'courseList'])->name('user.courseList');
    Route::get('/courses/monthl', [FrontendSectionController::class, 'courses'])->name('user.courses');
    Route::get('/course/{id}', [FrontendSectionController::class, 'course'])->name('user.course');
    Route::get('/project/{c_id?}', [FrontendSectionController::class, 'project'])->name('user.project');
    Route::get('/gallery', [FrontendSectionController::class, 'gallery'])->name('user.gallery');
    Route::get('/event', [FrontendSectionController::class, 'event'])->name('user.event');
    Route::get('/eventDetail/{id}', [FrontendSectionController::class, 'eventDetail'])->name('user.eventDetail');
    Route::get('/projects/{id}', [FrontendSectionController::class, 'projects'])->name('user.projects');
    Route::get('/project-detail/{id}', [FrontendSectionController::class, 'projectDetail'])->name('user.projectDetail');
    Route::get('/courses/monthly/{id}', [FrontendSectionController::class, 'monthly_courses'])->name('course.monthly');
        Route::get('/about', [FrontendSectionController::class, 'about'])->name('user.about');
    // user register and login middleware


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    // admin middleware with admin prefix
    Route::middleware(['admin_auth'])->prefix('admin')->group(function(){

        Route::get('/home/admin', [AdminController::class, 'user_interface'])->name('admin.home');
        Route::get('/teacher', [AdminController::class, 'teacher'])->name('admin.teacher');
        Route::get('/course', [AdminController::class, 'course'])->name('admin.course');
        Route::get('/section', [AdminController::class, 'section'])->name('admin.section');
        Route::get('/student', [AdminController::class, 'student'])->name('admin.student');
        Route::get('/enrollment', [AdminController::class, 'enrollment'])->name('admin.enrollment');
        Route::get('/timetable', [AdminController::class, 'timetable'])->name('admin.timetable');
        Route::get('/project', [AdminController::class, 'project'])->name('admin.project');
        Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');

        // welcome section
        Route::prefix('home')->group(function () {
            Route::get('/create', [HomeController::class, 'createWelcome'])->name('welcome.create');
            Route::post('/post', [HomeController::class, 'postWelcome'])->name('welcome.post');
            Route::get('/edit/{id}', [HomeController::class, 'editWelcome'])->name('welcome.edit');
            Route::post('/update', [HomeController::class, 'updateWelcome'])->name('welcome.update');
            Route::get('/delete/{id}', [HomeController::class, 'deleteWelcome'])->name('welcome.delete');
        });

        // about sectoin
        Route::prefix('about')->group(function () {
            Route::get('/post', [HomeController::class, 'postAbout'])->name('about.post');
            Route::post('/create', [HomeController::class, 'createAbout'])->name('about.create');
            Route::get('/edit/{id?}', [HomeController::class, 'editAbout'])->name('about.edit');
            Route::get('/delete/{id}', [HomeController::class, 'deleteAbout'])->name('about.delete');
            Route::post('/update', [HomeController::class, 'updateAbout'])->name('about.update');
            Route::get('/desc/edit/{id}', [HomeController::class, 'editDesc'])->name('about.desc.edit');
            Route::post('/desc/update', [HomeController::class, 'updateDesc'])->name('about.desc.update');
        });

        Route::prefix('address')->group(function(){
            Route::get('/post', [HomeController::class, 'postAddress'])->name('address.post');
            Route::get('/add', [HomeController::class, 'add'])->name('address.add');
            Route::post('/store', [AddressController::class, 'store'])->name('addresses.store');
            Route::put('/edit/{id}', [AddressController::class, 'edit'])->name('address.edit');
        });

        // student project section
        Route::prefix('project')->group(function () {
            Route::get('/createPage', [ProjectController::class, 'createPage'])->name('project.createPage');
            Route::post('/create', [ProjectController::class, 'create'])->name('project.create');
            Route::get('/delete/{id}', [ProjectController::class, 'delete'])->name('project.delete');
            Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
            Route::post('/update/{id}', [ProjectController::class, 'update'])->name('project.update');
        });

        // gallery section
        Route::prefix('gallery')->group(function () {
            Route::get('/createPage', [GalleryController::class, 'createPage'])->name('gallery.createPage');
            Route::post('/create', [GalleryController::class, 'create'])->name('gallery.create');
            Route::get('/delete/{id}', [GalleryController::class, 'delete'])->name('gallery.delete');
            Route::get('/edit/{id}', [GalleryController::class, 'edit'])->name('gallery.edit');
            Route::post('/update/{id}', [GalleryController::class, 'update'])->name('gallery.update');
        });

        // teacher section
        Route::prefix('teacher')->group(function () {
            Route::get('/createPage', [TeacherController::class, 'createPage'])->name('teacher.createPage');
            Route::post('/create', [TeacherController::class, 'create'])->name('teacher.create');
            Route::get('/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
            Route::post('/update', [TeacherController::class, 'update'])->name('teacher.update');
            Route::get('/delete/{id}', [TeacherController::class, 'delete'])->name('teacher.delete');

        });

        // position section
        Route::prefix('position')->group(function() {
            Route::get('/createPage', [PositionController::class, 'createPage'])->name('position.createPage');
            Route::post('/create', [PositionController::class, 'create'])->name('position.create');
            Route::get('/edit/{id}', [PositionController::class, 'edit'])->name('position.edit');
            Route::post('/update', [PositionController::class, 'update'])->name('position.update');
            Route::get('/delete/{id}', [PositionController::class, 'delete'])->name('position.delete');
        });

        // teach section  (teachers <----> subjects)
        Route::prefix('teach')->group(function() {
            Route::get('/createPage', [TeachController::class, 'createPage'])->name('teach.createPage');
            Route::post('/create', [TeachController::class, 'create'])->name('teach.create');
            Route::get('/edit/{id}', [TeachController::class, 'edit'])->name('teach.edit');
            Route::post('/update', [TeachController::class, 'update'])->name('teach.update');
            Route::get('/delete/{id}', [TeachController::class, 'delete'])->name('teach.delete');
        });

        // for course section
        Route::prefix('course')->group(function () {
            Route::get('/createPage', [CourseController::class, 'createPage'])->name('course.createPage');
            Route::post('/create', [CourseController::class, 'create'])->name('course.create');
            Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
            Route::post('/update', [CourseController::class, 'update'])->name('course.update');
            Route::get('/delete/{id}', [CourseController::class, 'delete'])->name('course.delete');
        });

        // for subject section
        Route::prefix('subject')->group(function () {
            Route::get('/createPage', [SubjectController::class, 'createPage'])->name('subject.createPage');
            Route::post('/create', [SubjectController::class, 'create'])->name('subject.create');
            Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
            Route::post('/update', [SubjectController::class, 'update'])->name('subject.update');
            Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('subject.delete');
        });

        // per subject materials (book / video / zip)
        Route::prefix('material')->group(function () {
            Route::get('/', [MaterialController::class, 'index'])->name('material.index');
            Route::get('/createPage', [MaterialController::class, 'createPage'])->name('material.createPage');
            Route::post('/create', [MaterialController::class, 'create'])->name('material.create');
            Route::get('/edit/{id}', [MaterialController::class, 'edit'])->name('material.edit');
            Route::post('/update/{id}', [MaterialController::class, 'update'])->name('material.update');
            Route::get('/delete/{id}', [MaterialController::class, 'delete'])->name('material.delete');
        });

        // exam sittings: the day, the window, the question paper and whether a
        // student can see it at all
        Route::prefix('exam')->group(function () {
            Route::get('/', [ExamController::class, 'index'])->name('exam.index');
            Route::get('/createPage', [ExamController::class, 'createPage'])->name('exam.createPage');
            Route::post('/create', [ExamController::class, 'create'])->name('exam.create');
            Route::get('/edit/{id}', [ExamController::class, 'edit'])->name('exam.edit');
            Route::post('/update/{id}', [ExamController::class, 'update'])->name('exam.update');
            Route::get('/delete/{id}', [ExamController::class, 'delete'])->name('exam.delete');
        });

        // students who left a class before it finished: the event is recorded
        // with its date, because a student can be in one class and out of another
        Route::prefix('drop-out')->group(function () {
            Route::get('/', [DropOutController::class, 'index'])->name('dropOut.index');
            Route::get('/createPage', [DropOutController::class, 'createPage'])->name('dropOut.createPage');
            Route::post('/create', [DropOutController::class, 'create'])->name('dropOut.create');
            Route::get('/edit/{id}', [DropOutController::class, 'edit'])->name('dropOut.edit');
            Route::post('/update/{id}', [DropOutController::class, 'update'])->name('dropOut.update');
            Route::get('/delete/{id}', [DropOutController::class, 'delete'])->name('dropOut.delete');
        });

        // certificates issued to students, and whether they have been collected
        Route::prefix('certificate')->group(function () {
            Route::get('/', [CertificateController::class, 'index'])->name('certificate.index');
            Route::get('/createPage', [CertificateController::class, 'createPage'])->name('certificate.createPage');
            Route::post('/create', [CertificateController::class, 'create'])->name('certificate.create');
            Route::get('/edit/{id}', [CertificateController::class, 'edit'])->name('certificate.edit');
            Route::post('/update/{id}', [CertificateController::class, 'update'])->name('certificate.update');
            Route::get('/delete/{id}', [CertificateController::class, 'delete'])->name('certificate.delete');
        });

        // the grade bands a mark is filed into
        Route::prefix('grading')->group(function () {
            Route::get('/', [GradingController::class, 'index'])->name('grading.index');
            Route::get('/createPage', [GradingController::class, 'createPage'])->name('grading.createPage');
            Route::post('/create', [GradingController::class, 'create'])->name('grading.create');
            Route::get('/edit/{id}', [GradingController::class, 'edit'])->name('grading.edit');
            Route::post('/update/{id}', [GradingController::class, 'update'])->name('grading.update');
            Route::get('/delete/{id}', [GradingController::class, 'delete'])->name('grading.delete');
        });

        // the marks themselves: a student's score on one subject of one course
        Route::prefix('grading-result')->group(function () {
            Route::get('/', [GradingResultController::class, 'index'])->name('gradingResult.index');
            Route::get('/createPage', [GradingResultController::class, 'createPage'])->name('gradingResult.createPage');
            Route::post('/create', [GradingResultController::class, 'create'])->name('gradingResult.create');
            Route::get('/edit/{id}', [GradingResultController::class, 'edit'])->name('gradingResult.edit');
            Route::post('/update/{id}', [GradingResultController::class, 'update'])->name('gradingResult.update');
            Route::get('/delete/{id}', [GradingResultController::class, 'delete'])->name('gradingResult.delete');
        });

        // for course type section
        Route::prefix('courseType')->group(function () {
            Route::get('/createPage', [CourseTypeController::class, 'createPage'])->name('courseType.createPage');
            Route::post('/create', [CourseTypeController::class, 'create'])->name('courseType.create');
            Route::get('/edit/{id}', [CourseTypeController::class, 'edit'])->name('courseType.edit');
            Route::post('/update', [CourseTypeController::class, 'update'])->name('courseType.update');
            Route::get('/delete/{id}', [CourseTypeController::class, 'delete'])->name('courseType.delete');
        });

        // for class section (course <---> subject)
        Route::prefix('class')->group(function() {
            Route::get('/createPage', [ClassController::class, 'createPage'])->name('class.createPage');
            Route::post('/create', [ClassController::class, 'create'])->name('class.create');
            Route::get('/edit/{id}', [ClassController::class, 'edit'])->name('class.edit');
            Route::post('/update', [ClassController::class, 'update'])->name('class.update');
            Route::get('/delete/{id}', [ClassController::class, 'delete'])->name('class.delete');
        });

        // for section page
        Route::prefix('section')->group(function() {
            Route::get('/createPage', [SectionController::class, 'createPage'])->name('section.createPage');
            Route::post('/create', [SectionController::class, 'create'])->name('section.create');
            Route::get('/edit/{id}', [SectionController::class, 'edit'])->name('section.edit');
            Route::post('/update', [SectionController::class, 'update'])->name('section.update');
            Route::get('/delete/{id}', [SectionController::class, 'delete'])->name('section.delete');
        });

        // for couse_section page (course <----> section)
        Route::prefix('course/section')->group(function() {
            Route::get('/', [CourseSectionController::class, 'index'])->name('course.section.index');
            Route::post('/sync', [CourseSectionController::class, 'sync'])->name('course.section.sync');
            Route::get('/{courseId}/sections', [CourseSectionController::class, 'sectionsForCourse'])
                ->name('course.section.forCourse')->whereNumber('courseId');

            // legacy single-pair routes, kept so existing links keep working
            Route::get('/createPage', [CourseSectionController::class, 'createPage'])->name('course.section.createPage');
            Route::post('/create', [CourseSectionController::class, 'create'])->name('course.section.create');
            Route::get('/edit/{id}', [CourseSectionController::class, 'edit'])->name('course.section.edit');
            Route::post('/update', [CourseSectionController::class, 'update'])->name('course.section.update');
            Route::get('/delete/{id}', [CourseSectionController::class, 'delete'])->name('course.section.delete');
        });

        // attendance marking
        Route::prefix('attendance')->group(function() {
            Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::get('/createPage', [AttendanceController::class, 'createPage'])->name('attendance.createPage');
            Route::post('/create', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::patch('/{id}/status', [AttendanceController::class, 'updateStatus'])
                ->name('attendance.updateStatus')->whereNumber('id');
            Route::get('/course/{courseId}/sections', [AttendanceController::class, 'sectionsForCourse'])
                ->name('attendance.forCourse')->whereNumber('courseId');
            Route::get('/course/{courseId}/subjects', [AttendanceController::class, 'subjectsForCourse'])
                ->name('attendance.subjectsForCourse')->whereNumber('courseId');
            // close the class from the marking screen, where the admin already
            // has the course + section in context
            Route::post('/complete-class', [AttendanceController::class, 'completeClass'])
                ->name('attendance.completeClass');
            // exports the review page's own filtered view, status filter included
            Route::get('/export', [AttendanceController::class, 'export'])
                ->name('attendance.exportFiltered');
        });

        // attendance reports (read-only analytics)
        Route::get('/attendance-report', [AttendanceReportController::class, 'index'])->name('attendance.report');
        Route::get('/attendance-report/export', [AttendanceReportController::class, 'export'])->name('attendance.exportReport');

        // for student
        Route::prefix('student')->group(function() {
            Route::get('/createPage', [StudentController::class, 'createPage'])->name('student.createPage');
            Route::post('/create', [StudentController::class, 'create'])->name('student.create');
            Route::post('/generate-credentials', [StudentController::class, 'generateCredentials'])->name('student.generateCredentials');
            Route::get('/{id}', [StudentController::class, 'show'])->name('student.show')->whereNumber('id');
            Route::post('/bulk-status', [StudentController::class, 'bulkStatus'])->name('student.bulkStatus');
            Route::get('/reset-password/{id}', [StudentController::class, 'resetPassword'])->name('student.resetPassword');
            Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
            Route::post('/update/{id}', [StudentController::class, 'update'])->name('student.update');
            Route::get('/delete/{id}', [StudentController::class, 'delete'])->name('student.delete');
        });

        // for course enrollment (student <----> course)
        Route::prefix('enrollment')->group(function() {
            Route::get('/createPage', [EnrollmentController::class, 'createPage'])->name('enrollment.createPage');
            Route::post('/create', [EnrollmentController::class, 'create'])->name('enrollment.create');
            Route::post('/search-students', [EnrollmentController::class, 'searchStudents'])->name('enrollment.searchStudents');
            Route::get('/edit/{id}', [EnrollmentController::class, 'edit'])->name('enrollment.edit');
            Route::post('/update/{id}', [EnrollmentController::class, 'update'])->name('enrollment.update');
            Route::get('/delete/{id}', [EnrollmentController::class, 'delete'])->name('enrollment.delete');
            Route::post('/complete-class', [EnrollmentController::class, 'completeClass'])->name('enrollment.completeClass');
            Route::get('/{courseId}/sections', [EnrollmentController::class, 'sectionsForCourse'])
                ->name('enrollment.forCourse')->whereNumber('courseId');
        });

        // for timetable
        Route::prefix('timetable')->group(function() {
            Route::get('/createPage', [TimeTableController::class, 'createPage'])->name('timetable.createPage');
            Route::post('/create', [TimeTableController::class, 'create'])->name('timetable.create');
            Route::get('/edit/{id}', [TimeTableController::class, 'edit'])->name('timetable.edit');
            Route::post('/update/{id}', [TimeTableController::class, 'update'])->name('timetable.update');
            Route::get('/delete/{id}', [TimeTableController::class, 'delete'])->name('timetable.delete');
        });

        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('event.index');
            Route::get('/create', [EventController::class, 'create'])->name('event.create');
            Route::post('/store', [EventController::class, 'store'])->name('events.store');
            Route::get('/delete/{id}', [EventController::class, 'delete'])->name('event.delete');
            Route::get('/update/{id}', [EventController::class, 'update_form'])->name('event.update.form');
            Route::put('/update/{id}', [EventController::class, 'update'])->name('events.update');

        });

        // for timetable
        Route::prefix('ajax')->group(function() {
            Route::get('/course/list', [AjaxController::class, 'courseList'])->name('ajax.courseList');
        });

        Route::prefix('monthly')->group(function () {
            Route::get('/monthly/add', [CourseController::class, 'monthlyAdd'])->name('monthly.add');
            Route::post('/monthly/create', [CourseController::class, 'monthlycreate'])->name('monthly.create');
            Route::get('/monthly/edit/{id}', [CourseController::class, 'monthlyedit'])->name('monthly.edit');
            Route::post('/monthly/update/{id}', [CourseController::class, 'monthlyupdate'])->name('monthly.update');
            Route::get('/monthly/delete/{id}', [CourseController::class, 'monthlydelete'])->name('monthly.delete');
        });
    });

    // user
    Route::group(['middleware' => 'user_auth', 'prefix' => 'student'], function(){
        // Route::get('/home/user', [UserController::class, 'home'])->name('user.home');

    });

});


// unified login routes for all users
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// student area: guarded by the "student" auth guard
Route::middleware(['student_auth'])->prefix('student-portal')->group(function () {
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/attendance', [StudentPortalController::class, 'attendance'])->name('student.attendance');
    // an empty shell until the assignment tables exist
    Route::get('/assignments', [StudentPortalController::class, 'assignments'])->name('student.assignments');
    // published sittings for the courses the student is enrolled in
    Route::get('/exam', [StudentExamController::class, 'index'])->name('student.exam');
    // the sitting itself: the paper, the countdown and the submit panel, reachable
    // only while the window is open
    Route::get('/exam/{examId}', [StudentExamController::class, 'show'])
        ->name('student.exam.show')
        ->whereNumber('examId');
    // the paper, streamed rather than linked, so the window keeps applying after
    // the page has loaded
    Route::get('/exam/{examId}/paper', [StudentExamController::class, 'paper'])
        ->name('student.exam.paper')
        ->whereNumber('examId');
    Route::post('/exam/{examId}/submit', [StudentExamController::class, 'submit'])
        ->name('student.exam.submit')
        ->whereNumber('examId');
    Route::get('/courses', [StudentPortalController::class, 'courses'])->name('student.courses');
    Route::get('/courses/{courseId}', [StudentPortalController::class, 'courseDetail'])
        ->name('student.courseDetail')
        ->whereNumber('courseId');
    Route::post('/logout', [StudentPortalController::class, 'logout'])->name('student.logout');
});

// unified register routes for all users
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');

// admin register and login middleware
Route::prefix('admin')->group(function(){
    Route::get('/registerPage', [AuthController::class, 'register'])->name('registerPage');
});

Route::prefix('user')->group(function(){
    Route::redirect('/', 'loginPage');
    Route::get('/loginPage', [FrontendSectionController::class, 'student_signup'])->name('user.signup');
    Route::post('/loginPage', [FrontendSectionController::class, 'student_signup_process'])->name('user.signup.process');
    Route::post('/getPhone', [FrontendSectionController::class, 'getPhone'])->name('user.signup.getPhone');
    Route::post('/loginPage/login', [FrontendSectionController::class, 'student_login_process'])->name('login.process');
});

// for printer pos-----------------------------------
Route::any('/invoice', [PrinterController::class, 'invoice'])->name('invoice');
Route::get('/print_form', [PrinterController::class, 'print_form'])->name('print_form');
Route::post('/datasend', [PrinterController::class, 'datasend'])->name('datasend');

Route::prefix('system')->group(function() {
    Route::get('/pos', [FrontendSectionController::class, 'pos'])->name('pos');
    Route::get('/invoice', [FrontendSectionController::class, 'invoice'])->name('system.invoice');

    Route::get('/income_list', [FrontendSectionController::class, 'income_list'])->name('income_list');
    Route::get('/final_pay', [FrontendSectionController::class, 'final_pay'])->name('final_pay');
    Route::get('/final_pay_print/{voucher_no}', [FrontendSectionController::class, 'final_pay_print'])->name('final_pay_print');
    Route::post('/process_final_pay', [FrontendSectionController::class, 'process_final_pay'])->name('process_final_pay');

});

Route::post('/insertdata', [FrontendSectionController::class, 'insertData'])->name('insertData');
Route::get('/fetch-projects/{courseId}', [FrontendSectionController::class, 'fetchProjects']);

// for course type in navbar
// Route::get('user', 'UserController@index')->name('user');

// Admin Review CRUD
Route::resource('admin/review', App\Http\Controllers\Admin\ReviewController::class)->names('admin.review');

// Frontend Review Page
Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

// Admin Review Approve/Reject
Route::post('admin/review/{id}/toggle-status', [App\Http\Controllers\Admin\ReviewController::class, 'toggleStatus'])->name('admin.review.toggleStatus');
