<?php

namespace App\Http\Controllers;

use App\Models\Grading;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradingController extends Controller
{
    /**
     * The grade bands marks are filed into.
     *
     * A short list, so it is shown in full rather than paginated, and each row
     * carries the number of marks filed under it: a band nobody has used yet is
     * either a mistake or a band that is about to be.
     */
    public function index()
    {
        $gradings = Grading::withCount('results')
            // highest ceiling first, because the bands are read top down when a
            // mark is being worked out by hand
            ->orderByDesc('score')
            ->orderBy('name')
            ->get();

        return view('admin.grading.index', ['gradings' => $gradings]);
    }

    public function createPage()
    {
        return view('admin.grading.create');
    }

    public function create(Request $request)
    {
        Grading::create($this->validated($request));

        return redirect()
            ->route('grading.index')
            ->with('success', 'Added a grade band.');
    }

    public function edit($id)
    {
        return view('admin.grading.edit', ['grading' => Grading::withCount('results')->findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $grading = Grading::findOrFail($id);

        $grading->fill($this->validated($request, $grading))->save();

        return redirect()
            ->route('grading.index')
            ->with('success', 'Updated the ' . $grading->name . ' band.');
    }

    /**
     * Delete a band, but only while nothing is filed under it.
     *
     * The column on grading_results is set null on delete, so letting this through
     * would quietly un-grade every mark in the band and leave a list of numbers
     * with no bands against them - nothing the admin did on this page asks for.
     * Refusing, and saying which band is in the way, is the recoverable answer.
     */
    public function delete($id)
    {
        $grading = Grading::withCount('results')->findOrFail($id);

        if ($grading->results_count > 0) {
            return redirect()
                ->route('grading.index')
                ->with('error', 'The ' . $grading->name . ' band is used by '
                    . $grading->results_count . ' mark(s). Move those to another band first.');
        }

        $name = $grading->name;
        $grading->delete();

        return redirect()
            ->route('grading.index')
            ->with('success', 'Deleted the ' . $name . ' band.');
    }

    /**
     * Validate the form.
     *
     * The name is unique because a result is graded by picking a band, and two
     * bands with the same name make that pick ambiguous. On an edit the band's own
     * name is allowed through, or saving an unchanged form would fail on itself.
     */
    private function validated(Request $request, ?Grading $grading = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('gradings', 'name')->ignore($grading?->id),
            ],
            'score' => ['required', 'numeric', 'min:0', 'max:9999'],
        ], [
            'name.required' => 'Give the band a name, e.g. A or Distinction.',
            'name.max' => 'The name may not be longer than 60 characters.',
            'name.unique' => 'A band with that name already exists.',
            'score.required' => 'Enter the top mark of the band, e.g. 100 for A.',
            'score.numeric' => 'The top mark must be a number.',
            'score.min' => 'The top mark cannot be below 0.',
            'score.max' => 'The top mark looks too large - check the number.',
        ]);

        return array_intersect_key($validated, array_flip(['name', 'score']));
    }
}