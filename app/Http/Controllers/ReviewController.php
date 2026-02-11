<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // For /reviews page
    public function index(Request $request)
    {
        $query = \App\Models\Review::where('status', 1);
        $rating = $request->input('rating');
        $sort = $request->input('sort', 'latest');
        if ($rating && in_array($rating, [1,2,3,4,5])) {
            $query->where('rating', $rating);
        }
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $reviews = $query->paginate(12)->appends($request->only('rating', 'sort'));
        $progType = \App\Models\course_type::where('name', 'Programming')->first();
        $graphType = \App\Models\course_type::where('name', 'Graphic Design')->first();
        $ictType = \App\Models\course_type::where('name', 'ICT')->first();
        $prog = $progType ? $progType->courses : collect();
        $graph = $graphType ? $graphType->courses : collect();
        $ict = $ictType ? $ictType->courses : collect();
        $address = \App\Models\Address::all();
        return view('frontend_section.reviews', compact('reviews', 'rating', 'sort', 'prog', 'graph', 'ict', 'address'));
    }

    // For homepage (static call)
    public static function latestHomeReviews($limit = 3)
    {
        return Review::where('status', 1)->orderByDesc('created_at')->take($limit)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('reviews', 'public');
        }
        $validated['status'] = 0; // pending
        \App\Models\Review::create($validated);
        return redirect()->route('reviews.index')->with('success', 'Thank you for your review! It will be published after approval.');
    }
}
