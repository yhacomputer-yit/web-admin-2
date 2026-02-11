<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::orderByDesc('created_at')->paginate(10);
        return view('admin.review.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.review.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|boolean',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('reviews', 'public');
        }
        Review::create($validated);
        return redirect()->route('admin.review.index')->with('success', 'Review created successfully.');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('admin.review.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|boolean',
        ]);
        if ($request->hasFile('photo')) {
            if ($review->photo) {
                Storage::disk('public')->delete($review->photo);
            }
            $validated['photo'] = $request->file('photo')->store('reviews', 'public');
        }
        $review->update($validated);
        return redirect()->route('admin.review.index')->with('success', 'Review updated successfully.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if ($review->photo) {
            Storage::disk('public')->delete($review->photo);
        }
        $review->delete();
        return redirect()->route('admin.review.index')->with('success', 'Review deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $review = Review::findOrFail($id);
        $review->status = !$review->status;
        $review->save();
        return redirect()->route('admin.review.index')->with('success', 'Review status updated.');
    }
}
