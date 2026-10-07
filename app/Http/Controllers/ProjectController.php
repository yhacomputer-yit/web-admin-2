<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Project;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller 
{
    // direct student project create page
    public function createPage(){
        $courses = Course::get();
        $students = Student::orderBy('name')->get(['id', 'name']);
        return view('admin.project.create', compact('courses', 'students'));
    }
    // create student project
    public function create(Request $request){

        $data = $this->get_request_data($request);
        $rule = [
            'title' => 'required|min:3|unique:projects,title,'.$request->id,
            'image' => 'required|image|mimes:png,jpeg,jpg',
            'course' => 'required',
            'student' => 'required|exists:students,id',
            'desc' => 'required|min:5',
        ];
        Validator::make($request->all(), $rule)->validate();
        if($request->hasfile('image')){
            $filename = uniqid() .'_'. $request->file('image')->getClientOriginalName();
            $request->file('image')->storeas('public', $filename);
            $data["image"] = $filename;
        }
        Project::create($data);
        return redirect()->route('admin.project')->with(['success' => 'Added student project successfully!']);
    }

    // delete student project
    public function delete($id){
        Project::where('id', $id)->delete();
        return back()->with(['success' => 'Deleted student project successfully!']);
    }

    // edit student project
    public function edit($id){
        $courses = Course::get();
        $students = Student::orderBy('name')->get(['id', 'name']);
        $data = Project::where('id', $id)->first();
        return view('admin.project.edit', compact('data', 'courses', 'students'));
    }

    // update student project
    public function update(Request $request, $id){
        $rule = [
            'title' => 'required|min:3|unique:projects,title,'.$request->id,
            'image' => 'image|mimes:png,jpeg,jpg',
            'course' => 'required',
            'student' => 'required|exists:students,id',
            'desc' => 'required|min:5', 
        ];
        Validator::make($request->all(), $rule)->validate();
        $data = $this->get_request_data($request);
        if($request->hasfile('image')){ 
            $old = Project::select('image')->where('id', $id)->first()->toArray();
            $old = $old['image'];
            if($old != null){
                Storage::delete('public/'.$old);
            }
            $filename = uniqid() .'_'. $request->file('image')->getClientOriginalName();
            $request->file('image')->storeas('public', $filename);
            $data["image"] = $filename;
        }
        Project::where('id', $id)->update($data);
        return redirect()->route('admin.project')->with(['success' => 'Updated student project successfully']);
    }

    // student project data
    private function get_request_data($request){
        $arr = [
            'title' => $request->title,
            'student_id' => $request->student,
            'course_id' => $request->course,
            'desc' => $request->desc,
        ];
        return $arr;
    }

}
