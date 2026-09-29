<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    //
    public function courseList(Request $request){
        $course_id = $request->status;

        $data = Course::where('id', $course_id)
                ->with('subjects', 'sections')
                ->get();
        $students = Student::whereHas('enrollments', function ($query) use ($course_id) {
                    $query->where('course_id', $course_id);
                })
                ->get();

        return [$data, $students];
    }
}
