<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // direct to create page
    public function createPage()
    {
        $courses = Course::orderBy('name')->get();
        $sections = Section::orderBy('start', 'asc')->get();

        return view('admin.student.create', compact('courses', 'sections'));
    }

    // create new student
    public function create(Request $request)
    {
        $this->validation($request);

        $data = $this->get_request_data($request);
        $data['password'] = bcrypt($data['password']);

        $student = Student::create($data);

        if ($request->hasFile('image')) {
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public', $filename);
            $student->image = $filename;
            $student->save();
        }

        return redirect()->route('admin.student')->with([
            'success' => 'Added student ' . $request->name . ' successfully. Username: ' . $student->username . ' / Password: ' . $data['password'],
        ]);
    }

    // generate username + password for the admin form
    public function generateCredentials(Request $request)
    {
        $credentials = Student::generateCredentials($request->input('name'));

        if ($request->expectsJson()) {
            return response()->json($credentials);
        }

        return back()->with($credentials);
    }

    // edit student
    public function edit($id)
    {
        $student = Student::with('enrollments')->findOrFail($id);
        $courses = Course::orderBy('name')->get();
        $sections = Section::orderBy('start', 'asc')->get();

        return view('admin.student.edit', compact('student', 'courses', 'sections'));
    }

    // update student
    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $this->validation($request);

        $student = Student::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($student->image) {
                Storage::delete('public/' . $student->image);
            }
            $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public', $filename);
        } else {
            $filename = $student->image;
        }

        $data = $this->get_request_data($request);
        $data['image'] = $filename;
        $newPassword = null;

        // an empty password field on edit means "keep the current password"
        if (filled($data['password'])) {
            $newPassword = $data['password'];
            $data['password'] = bcrypt($newPassword);
        } else {
            unset($data['password']);
        }

        $student->update($data);

        $message = 'Updated student ' . $request->name . ' successfully.';
        if ($newPassword) {
            $message .= ' New password: ' . $newPassword;
        }

        return redirect()->route('admin.student')->with(['success' => $message]);
    }

    // delete student
    public function delete($id)
    {
        $student = Student::find($id);

        if ($student) {
            if ($student->image) {
                Storage::delete('public/' . $student->image);
            }
            $name = $student->name;
            $student->delete();

            return redirect()->route('admin.student')->with(['success' => 'Deleted student ' . $name . ' successfully.']);
        }

        return redirect()->route('admin.student')->with(['error' => 'Student not found.']);
    }

    // get request data
    private function get_request_data(Request $request)
    {
        return [
            'name' => $request->input('name'),
            'nickname' => $request->input('nickname'),
            'father_name' => $request->input('father_name'),
            'mother_name' => $request->input('mother_name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'facebook_acc_name' => $request->input('facebook_acc_name'),
            'viber_phone' => $request->input('viber_phone'),
            'telegram_username' => $request->input('telegram_username'),
            'date_of_birth' => $request->input('date_of_birth'),
            'nrc' => $request->input('nrc'),
            'gender' => $request->input('gender'),
            'education' => $request->input('education'),
            'native_town' => $request->input('native_town'),
            'religious_status' => $request->input('religious_status'),
            'race' => $request->input('race'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'status' => $request->input('status', 'inactive'),
        ];
    }

    // validation the request data
    private function validation(Request $request)
    {
        $isUpdate = $request->filled('id');

        $rule = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:students,username,' . $request->input('id'),
            'password' => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'status' => 'required|in:active,inactive',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $message = [
            'name.required' => 'Student name is required.',
            'username.required' => 'Username is required. Use the Generate button to create one.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'Password is required. Use the Generate button to create one.',
            'status.required' => 'Status is required.',
            'email.email' => 'Please enter a valid email address.',
        ];

        Validator::make($request->all(), $rule, $message)->validate();
    }
}
