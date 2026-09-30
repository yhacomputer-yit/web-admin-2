<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentPortalController extends Controller
{
    // student dashboard: profile + enrolled courses
    public function dashboard()
    {
        $student = Auth::guard('student')->user();

        $enrollments = $student->enrollments()
            ->with(['course:id,name,image,type', 'course.courseType:id,name', 'section:id,name'])
            ->orderByDesc('enroll_date')
            ->get();

        return Inertia::render('StudentDashboard', [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'nickname' => $student->nickname,
                'username' => $student->username,
                'email' => $student->email,
                'phone' => $student->phone,
                'address' => $student->address,
                'date_of_birth' => $student->date_of_birth?->format('Y-m-d'),
                'nrc' => $student->nrc,
                'gender' => $student->gender,
                'education' => $student->education,
                'native_town' => $student->native_town,
                'religious_status' => $student->religious_status,
                'race' => $student->race,
                'image' => $student->image,
                'status' => $student->status,
                'register_date' => ($student->register_date ?: $student->created_at)?->format('Y-m-d'),
            ],
            'enrollments' => $enrollments->map(fn($e) => [
                'id' => $e->id,
                'course_id' => $e->course_id,
                'course_name' => $e->course?->name,
                'course_image' => $e->course?->image,
                'course_type' => $e->course?->courseType?->name,
                'section_name' => $e->section?->name,
                'enroll_date' => $e->enroll_date?->format('Y-m-d'),
            ])->values(),
        ]);
    }

    // student logout
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
