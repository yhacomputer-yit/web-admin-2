<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\course_type;
use App\Models\Event;
use App\Models\EventDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use validation;

class EventController extends Controller
{
    public function index(){
        $results = Event::all();
        // dd($res);
        return view('admin.event' , compact("results" ));
    }

    public function create(){
        return view('admin.events.create');
    }
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'title' => 'required|min:2',
            'about' => 'required|min:4',
        ]);

        if($validate->fails()){
            return redirect()->route('event.create') // or redirect()->back()
                     ->withErrors($validate)
                     ->withInput();
        }

        // $detail_imgs = $request->details_image;
        $data = [
            'title' => $request->title,
            'edate' => $request->edate,
            'aboute' => $request->about,
            'image' => $request->image,

        ];

        if($request->hasfile('image')){
            $filename = uniqid() .'_'. $request->file('image')->getClientOriginalName(); // filename with unique
            $request->file('image')->storeas('public', $filename);
            $data["image"] = $filename;
        }
        $done = Event::create($data);

        if ($request->hasFile('details_image')) {
            foreach ($request->file('details_image') as $file) {
                // Generate a unique filename for each image
                $detailFilename = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public', $detailFilename);

                // dd($detailFilename);

                // Insert each image as a new row in the EventDetail table
                EventDetail::create([
                    'event_id' => $done->id,
                    'images' => $detailFilename,
                ]);
            }
        } else{
            dd("no file");
        }


        if($done){
            return redirect()->route("event.index")->with('success','Created new Event');
        } else{
            return redirect()->route('event.create')->with('error', 'Failed to create event');
        }
    }

    public function delete($id){

        $done = Event::where('id', $id)->delete();;
        if($done){
            return redirect()->route("event.index")->with('success','Event is Deleted');

        }
    }

    public function update_form($id)
        {
            $event = Event::findOrFail($id);

            return view('admin.events.edit', compact('event'));
        }

    public function update(Request $request, $id)
        {
            $request->validate([
                'title' => 'required|max:255',
                'edate' => 'required|date',
                'about' => 'required',
                'image' => 'nullable|image|max:2048',
            ]);

            $event = Event::findOrFail($id);

            $event->title = $request->input('title');
            $event->edate = $request->input('edate');
            $event->aboute = $request->input('about');

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('events', 'public');
                $event->image = $path;
            }

            $event->save();

            return redirect()->route('event.index')->with('success', 'Event updated successfully');
        }

}
