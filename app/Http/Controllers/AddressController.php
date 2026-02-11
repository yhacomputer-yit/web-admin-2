<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'OpenClose' => 'required|string|max:255',
            'yphNo' => 'required|string|max:20',
            'yEmail' => 'required|email|max:255',
            'map_url' => 'nullable|string|max:1024',
        ]);

        // dd($validatedData);

        // Create a new address using mass assignment
        Address::create($validatedData);
        // Redirect to a specific route or return a response
        return redirect()->route('admin.home')->with(['success' => 'Updated about description successfully!']);
    }

    public function edit(Request $request, $id){
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'OpenClose' => 'required|string|max:255',
            'yphNo' => 'required|string|max:20',
            'yEmail' => 'required|email|max:255',
            'map_url' => 'nullable|string|max:1024',
        ]);

        Address::where('id',$id)->update($validatedData);
        return redirect()->back()->with('success', 'Address saved successfully!');
    }
}
