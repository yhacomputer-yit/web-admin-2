<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\About;
use App\Models\Event;
use App\Models\Address;
use App\Models\Course;
use App\Models\Monthly;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Voucher;
use App\Models\Welcome;
use App\Models\finalPay;
use App\Models\Register;
use App\Models\AboutDesc;
use App\Models\ClassModel;
use App\Models\EventDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FrontendSectionController extends Controller
{
    private function share(){
        // Fetch all courses
        $courses = Course::all();
        $address = Address::get();

        // Define specific data for each type
        $ict = $courses->where('type', '3');
        $prog = $courses->where('type', '1');
        $graph = $courses->where('type', '2');

        return compact('ict', 'prog', 'graph', 'courses', 'address');
    }

    public function fronthome(){
        $sliders = Welcome::all();
        $abouts = About::all();
        $aboutDesc = AboutDesc::all();
        $project = Project::all();
        $teacher = Teacher::with('position')->get();
        $events = Event::orderBy('edate', 'desc')->get();
        $monthies = Monthly::all();
        $projects = Project::with('course')->orderBy('created_at', 'desc')->get();
        $address = \App\Models\Address::get();
        // dd($address);

        $data = $this->share();

        return view("frontend_section.homepage", [
            'sliders' => $sliders,
            'abouts' => $abouts,
            'aboutDesc' => $aboutDesc,
            'project' => $project,
            'teacher' => $teacher,
            'events' => $events,
            'monthies' => $monthies,
            'projects' => $projects,
            'address' => $address,
        ], $data);
    }
    public function courses(){

        $monthies = Monthly::with('course')->paginate(6, ['*'], 'monthly');
        // dd($monthies);

        $data = $this->share();

        return view("frontend_section.courses", [
            'monthies' => $monthies
        ], $data);

    }
    public function project($c_id = 1){
        $data = $this->share();
        $prog = 1;
        $photo = 2;

        if($c_id == 2){
            $projects = Project::with('course:id,name')
            ->whereHas('course', function ($query) use ($photo) {
                $query->where('type', $photo); // Fetch courses of type 2 (Graphic Design)
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        } else{
            $projects = Project::with('course:id,name')
            ->whereHas('course', function ($query) use ($prog) {
                $query->where('type', $prog); // Fetch courses of type 1 (Programming)
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        }
         $data = $this->share();

        return view("frontend_section.project", [
            'projects' => $projects,
        ], $data);
    }


    // about us section

 public function about(Request $request){
     $data = $this->share();
    return view ('frontend_section.about' , $data);
 }



    public function event(Request $request){
         $data = $this->share();

        $name = session('name');
        $phone = session('phone');

        $events = Event::orderBy('edate', 'desc')->paginate(6);

        return view("frontend_section.event", [
            'events' => $events,
            'name' => $name,
            'phone' => $phone,

        ], $data);
    }



    public function eventDetail($id){
        $data = $this->share();

        $details = EventDetail::where('event_id',$id)->get();
        $event = Event::where('id', $id)->first();
        return view('frontend_section.eventDetail', [
            'details' => $details,
            'event' => $event
        ],$data);
    }

    public function course(Request $request, $id){
        $courses = Course::all();
        $address = Address::get();
        // dd($address);

        // Define specific data for each type
        $ict = $courses->where('type', '3');
        $prog = $courses->where('type', '1');
        $graph = $courses->where('type', '2');

        View::share('ict', $ict);
        View::share('prog', $prog);
        View::share('graph', $graph);

        $subjects = ClassModel::where('course_id', $id)->get();
        $course = Course::where('id', $id)->first();
        return view("frontend_section.course", [
            'ict' => $ict,
            'course' => $course,
            'subjects' => $subjects,
            'address' => $address,
        ]);


    }
//return view("frontend_section.course");

    public function projects(Request $request, $c_id = 1){

        $courses = Course::whereNotIn('id', [2, 3, 4])->get();
        $projects = Project::where('course_id', $c_id)
                          ->with('course')
                          ->orderBy('created_at', 'desc')
                          ->paginate(9);

        return view("frontend_section.project", [
            'courses' => $courses,
            'projects' => $projects,
        ]);
    }

    public function fetchProjects($courseId)
    {
        // Fetch projects related to the selected course
        $projects = Project::where('course_id', $courseId)->with('course')->get();

        // Return projects as JSON response
        return response()->json($projects);
    }

    public function projectDetail($id)
    {
        $data = $this->share();

        $project = Project::with('course')->findOrFail($id);

        return view('frontend_section.projectDetail', [
            'project' => $project
        ], $data);
    }

    // pos
    public function pos()
    {
        $courses = Course::all();
        $address = Address::get();
        $sections = Section::all();

        foreach ($sections as $section) {
            $startTime = Carbon::parse($section->start);
            $endTime = Carbon::parse($section->end);

            $section->start_hour = $startTime->format('H');
            $section->start_minute = $startTime->format('i');
            $section->end_hour = $endTime->format('H');
            $section->end_minute = $endTime->format('i');
        }

        return view('admin.POS.print_form', [
            'courses' => $courses,
            'address' => $address,
            'sections' => $sections
        ]);
    }

    public function invoice(Request $request){
        return view("admin.POS.invoice");
    }


    public function student_signup(){
        return view('frontend_section.stu_signup');
    }

    public function getPhone(Request $request){
        $phoneNumber = $request->input('ph');
        $user = Register::where('phone', $phoneNumber)->first();

        if ($user) {
            return response()->json(['name' => $user->name]);
        } else {
            return response()->json(['name' => 'Name not found']);
        }

    }

    public function student_signup_process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ph' => 'required|string|max:255',
            'password' => 'required|string|min:2|confirmed',
            'email' => 'nullable|email|max:255'
        ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $user = User::create([
            'name' => $request->input('name'),
            'phone' => $request->input('ph'),
            'password' => $request->input('password'),
            'role' => 'user',
            'email' => 'noemail@gmail.com'
        ]);

        //dd($user);

        if($user->save()){
            return redirect()->route('user.signup')->with('success', 'User registered successfully');
        } else{
            return redirect()->back()->with('error', 'Error saving user.');
        }
    }

    public function insertData(Request $request)
    {
        try {
            $data = $request->json()->all(); // Get all JSON data

            // Validate JSON data
            $validatedData = Validator::make($data, [
                'studentNames' => 'required|array',
                'classStartDate' => 'required|date|date_format:Y-m-d',
                'className' => 'required|string',
                'classTime' => 'required|integer',
                'classid' => 'required|integer',
                'classPrice' => 'required|numeric',
                'totalPrice' => 'required|numeric',
                'discount' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'balance' => 'required|numeric',
                'voucher_no' => 'required|numeric',
                'status' => 'required',
                'tableData' => 'required|array',
            ])->validate();

            // Insert payment data
            Payment::create([
                'voucher_no' => $validatedData['voucher_no'],
                'total_amu' => $validatedData['totalPrice'],
                'discount' => $validatedData['discount'],
                'balance' => $validatedData['balance'],
                'paid' => $validatedData['subtotal'],
                'vou_date' => now(),
                'status' => $validatedData['status'],
            ]);

            // Prepare and insert voucher data
            $voucherDataTableData = array_map(function ($row) use ($validatedData) {
                return [
                    'voucher_no' => $validatedData['voucher_no'],
                    'stu_name' => $row['studentName'],
                    'course_id' => $validatedData['classid'],
                    'section_id' => $validatedData['classTime'],
                    'enroll_date' => $row['classStartDate'],
                    'fees' => $row['classPrice'],
                    'vou_date' => now(),
                ];
            }, $validatedData['tableData']);

            Voucher::insert($voucherDataTableData);

            return response()->json(['message' => 'Data inserted successfully']);
        } catch (\Exception $e) {
            // Log the error message
            // \Log::error('Error in insertData: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error inserting data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //

    public function student_login_process(Request $request){
        $phoneNumber = $request->input('phno');
        $password = $request->input('pass');

        $user = User::select('name', 'password', 'phone')
        ->where('phone', $phoneNumber)
        ->where('password', $password)
        ->first();
        //dd($user);
        if($user) {
            session(['name' => $user->name, 'phone' => $user->phone]);
            return redirect()->route('user.event');
        } else {
            return redirect()->route('user.signup')->with('error', 'Invalid phone number or password');
        }
    }

    public function income_list(){
        $payment = Payment::paginate(10);
        return view('admin.POS.income_list', ['payment' => $payment]);
    }

    // public function final_pay(){
    //     return view('admin.POS.final_pay');
    // }

    public function final_pay(Request $request){
        $voucherNo = $request->input('voucher_no');

        $payment = Payment::where('voucher_no', $voucherNo)->first();

        if($payment) {
            return view('admin.POS.final_pay', ['payment' => $payment]);
        } else {
            return view('admin.POS.final_pay')->with('error', 'Payment not found for Voucher No: ' . $voucherNo);
        }
    }

    public function process_final_pay(Request $request)
    {
        $status = $request->input('status');
        $vou_no = $request->input('vou_no');
        $f_paid = $request->input('f_paid');
        $vou_date = now()->toDateTimeString();

        finalPay::create([
            'vou_no' => $vou_no,
            'f_paid' => $f_paid,
            'vou_date' => $vou_date,
        ]);

        DB::table('payment')->where('voucher_no', $vou_no)->update(['status' => $status]);
        Session::flash('success', 'Paid Successfully');
        Session::flash('voucher_no', $vou_no);
        return redirect()->route('final_pay_print', ['voucher_no' => $vou_no]);

        // return view('admin.POS.final_pay');
    }


    public function final_pay_print(Request $request, $voucherNo){
        $payment = Payment::where('voucher_no', $voucherNo)->get();
        $voucher = voucher::where('voucher_no', $voucherNo)->get();

        return view('admin.POS.final_pay_print', [
            'voucherNo' => $voucherNo,
            'payment' => $payment,
            'voucher' => $voucher,
        ]);

        return view('admin.POS.final_pay_print', ['voucherNo' => $voucherNo]);
    }

    // get the monthly course
    public function monthly_courses($id){
        $courses = Course::all();
        $address = Address::get();
        $monthies = Monthly::with(['course', 'section'])->where('id', $id)->get();
        // dd($monthies);
        // Define specific data for each type
        $ict = $courses->where('type', '3');
        $prog = $courses->where('type', '1');
        $graph = $courses->where('type', '2');

        // Share data with views
        View::share('ict', $ict);
        View::share('prog', $prog);
        View::share('graph', $graph);

        return view('frontend_section.monthly_course', [
            'courses' => $courses,
            'address' => $address,
            'monthies' => $monthies
        ]);
    }



}


