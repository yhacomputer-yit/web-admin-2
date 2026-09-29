<?php

namespace App\Http\Controllers;

use App\Models\course_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTypeController extends Controller
{
    // direct create new course type page
    public function createPage()
    {
        return view('admin.courseType.create');
    }

    // create new course type
    public function create(Request $request)
    {
        $this->validation($request);
        $data = $this->get_request_data($request);
        course_type::create($data);
        cache()->forget('nav_course_types_v2');

        return redirect()->route('admin.course')->with(['success' => 'Added course type ' . $data['name']]);
    }

    // edit course type
    public function edit($id)
    {
        $data = course_type::where('id', $id)->first();
        return view('admin.courseType.edit', compact('data'));
    }

    // update course type
    public function update(Request $request)
    {
        $this->validation($request);
        $data = $this->get_request_data($request);
        $id = $request->id;
        course_type::where('id', $id)->update($data);
        cache()->forget('nav_course_types_v2');

        return redirect()->route('admin.course')->with(['success' => 'Updated course type ' . $request->name . ' successfully']);
    }

    // delete course type
    public function delete($id)
    {
        $type = course_type::where('id', $id)->first();
        course_type::where('id', $id)->delete();
        cache()->forget('nav_course_types_v2');

        return redirect()->route('admin.course')->with(['success' => 'Deleted course type ' . $type->name]);
    }

    // get request data
    private function get_request_data($request)
    {
        return [
            'name' => $request->name,
        ];
    }

    // validation the request data
    private function validation($request)
    {
        $rule = [
            'name' => 'required|min:3|unique:course_types,name,' . $request->id,
        ];
        $message = [
            'name.required' => 'Course type name is required.',
            'name.min' => 'Course type name must have upper 3 letters!',
            'name.unique' => 'Course type name has been taken!',
        ];
        Validator::make($request->all(), $rule, $message)->validate();
    }
}
