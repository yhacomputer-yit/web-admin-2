<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Monthly;
use App\Models\Section;
use App\Models\course_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    // course create page
    public function createPage(){
        $courseTypes = course_type::select('id', 'name')->get();
        return view('admin.course.create', ['courseTypes' => $courseTypes]);
    }
    // edit course page
    public function edit($id){
        $data = Course::where('id', $id)->first();
        $course_types = course_type::where('id', $data->type)->first();

        return view('admin.course.edit', compact('data','course_types'));
    }
    // create course
    public function create(Request $request)
    {
        // Validate the incoming request
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|numeric',
            'normal_price' => 'required|numeric',
            'special_price' => 'nullable|numeric',
            'type' => 'required|exists:course_types,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about' => 'required|string',
            'links' => 'nullable|string',
        ]);

        // Get the request data including the new fields
        $data = $request->only([
            'name',
            'description',
            'duration',
            'normal_price',
            'special_price',
            'type',
            'about',
            'links',
        ]);

        // Handle the image upload if present
        if ($request->hasFile('image')) {
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public', $filename);
            $data["image"] = $filename;
        }

        // Create the course with the collected data
        Course::create($data);

        // Redirect with a success message
        return redirect()->route('admin.course')->with(['success' => 'Added course ' . $data['name']]);
    }

    // update course
    public function update(Request $request)
    {
        $this->validation($request, "update");

        $data = $this->get_request_data($request);
        $data['type'] = $request->type;
        $id = $request->id;

        if ($request->hasFile('image')) {
            $old = Course::select('image')->where('id', $id)->first()->toArray();
            $oldImage = $old['image'];
            if ($oldImage != null) {
                Storage::delete('public/' . $oldImage);
            }

            // Store the new image
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName(); // Generate a unique filename
            $request->file('image')->storeAs('public', $filename);
            $data["image"] = $filename; // Add the new image path to data array
        }

        // Update the course with new data, including the 'about' and 'link' fields
        Course::where('id', $id)->update($data);

        // Redirect back to the course list with a success message
        return redirect()->route('admin.course')->with(['success' => 'Updated course ' . $request->name . ' successfully']);
    }

    // delete course
    public function delete($id){
        $course = Course::where('id', $id)->first();
        $old = Course::select('image')->where('id', $id)->first()->toArray();
        $old = $old['image'];
        if($old != null){
            Storage::delete('public/'.$old);
        }
        Course::where('id', $id)->delete();
        return redirect()->route('admin.course')->with(['success' => 'Deleted '.$course->name.' course.']);
    }




    private function get_request_data($request)
    {
        $array = [
            'name' => $request->name,
            'description' => $request->description,
            'duration' => $request->duration,
            'normal_price' => $request->normal_price,
            'special_price' => $request->special_price,
            'about' => $request->about,  // Added this line
            'links' => $request->link,    // Added this line
        ];
        return $array;
    }

    // validation
    private function validation($request, $mode){
        $rule = [
            'name' => 'required|min:1|unique:courses,name,'.$request->id,
            'description' => 'required|min:1',
            'image' => ($mode == 'create') ? 'required|file|mimes:jpg,png,jpeg,webp' : 'file|mimes:jpg,png,jpeg,webp',
            'duration' => 'required|min:1',
            'normal_price' => 'required|min:1',
            'special_price' => 'required|min:1',
        ];
        $message = [
            'name.required' => 'Course name is required',
            'normal_price.required' => 'Course normal price is required',
            'special_price.required' => 'Course special price is required',
            'description.required' => 'Course description is required',
            'image.required' => 'Course image is required',
            'duration.required' => 'Course duration is required',
            'name.min' => 'Course name must have minimum 5 letters',
            'description.min' => 'Course description must have minimun 5 letters',
            'duration.min' => 'Course duration must have minimum 2 letters',
            'special_price.min' => 'Course special price must have minimum 4 number',
        ];
        Validator::make($request->all(), $rule, $message)->validate();
    }

    public function monthlyAdd(){
        $courses = Course::all();
        $sections = Section::all();

        return view('admin.monthlyCourse.create', [
            'courses' => $courses,
            'sections' => $sections
        ]);
    }

    public function monthlycreate(Request $request){

        $this->validate($request, [
            'course_id' => 'required',
            'section_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'm_img' => 'required',
            'm_desc' => 'required'
        ]);

        $data = $request->only([
            'course_id',
            'section_id',
            'start_date',
            'end_date',
            'limited_seat',
            'm_img',
            'm_desc'
        ]);

        if ($request->hasFile('m_img')) {
            $filename = uniqid() . '_' . $request->file('m_img')->getClientOriginalName();
            $request->file('m_img')->storeAs('public', $filename);
            $data["m_img"] = $filename;
        }
        Monthly::create($data);

        return redirect()->route('admin.course')->with(['success' => 'New Monthly course is added']);

    }

    public function monthlyedit($id) {
        $monthly = Monthly::with(['course', 'section'])->where('id', $id)->first();
        $courses = Course::all();
        $sections = Section::all();

        return view('admin.monthlyCourse.edit', [
            'monthly' => $monthly,
            'courses' => $courses,
            'sections' => $sections
        ]);
    }
    public function monthlyupdate(Request $request)
    {
        $id = $request->id;
        $monthly = Monthly::find($id);
        $new_img = $request->new_m_img;
        $old_img = $request->old_m_img;
        // dd($new_img);

        // Handle image upload if a new image is uploaded
        if ($request->hasFile('new_m_img')) {
            // Get the file and store it
            $image = $request->file('new_m_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Move the image to public/images directory
            $image->move(public_path('images'), $imageName);

            // Delete old image if it exists
            if ($monthly->m_img) {
                $oldImagePath = public_path('images/' . $monthly->m_img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Save new image name
            $monthly->m_img = $imageName;
        } else {
            // Keep the old image if no new image is uploaded
            $monthly->m_img = $old_img;
        }

        // Update other fields
        $monthly->course_id = $request->input('course_id');
        $monthly->section_id = $request->input('section_id');
        $monthly->start_date = $request->input('start_date');
        $monthly->end_date = $request->input('end_date');
        $monthly->limited_seat = $request->input('limited_seat');
        $monthly->m_desc = $request->input('m_desc');

        // Save the changes
        $monthly->save();

        // Redirect to the appropriate route (adjust the route as needed)
        return redirect()->route('admin.course', $monthly->id)->with('success', 'Monthly course updated successfully!');

    }


    public function monthlydelete($id){
        $course = Monthly::where('id', $id)->first();
        $old = Monthly::select('m_img')->where('id', $id)->first()->toArray();
        $old = $old['m_img'];
        if($old != null){
            Storage::delete('public/'.$old);
        }
        Monthly::where('id', $id)->delete();
        return redirect()->route('admin.course')->with(['success' => 'Deleted a Monthly course.']);

    }


}
