<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use App\Models\Subject;
use App\Models\SubjectDetail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * One file per row, so a subject can hold any number of materials.
     *
     * `file_link` holds a path on the `public` disk rather than a URL, which is
     * the contract the create_materials_table migration set: a file is uploaded
     * once here and served straight from storage, so replacing it never needs a
     * third party to keep working.
     *
     * There is deliberately no uniqueness rule on course + subject any more.
     * Nothing stops an admin from adding a second book, a second recording or a
     * second archive to a subject, which is the whole point of the one-file-per-
     * row shape; see the allow_materials migration that dropped the old index.
     */
    public function index(Request $request)
    {
        $courses = Course::orderBy('name')->get();

        $selectedCourseId = $request->integer('course_id') ?: null;
        $selectedSubjectId = $request->integer('subject_id') ?: null;

        $materials = Material::with(['course', 'subject'])
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->when($selectedSubjectId, fn ($q) => $q->where('subject_id', $selectedSubjectId))
            // course, then subject, then newest first within a subject: the same
            // order the student pages list the files in
            ->orderBy('course_id')
            ->orderBy('subject_id')
            ->orderByDesc('id')
            ->get();

        return view('admin.material.index', [
            'courses' => $courses,
            'materials' => $materials,
            'subjectsByCourse' => $this->subjectsByCourse(),
            'selectedCourseId' => $selectedCourseId,
            'selectedSubjectId' => $selectedSubjectId,
        ]);
    }

    public function createPage(Request $request)
    {
        return view('admin.material.create', [
            'courses' => Course::orderBy('name')->get(),
            'subjectsByCourse' => $this->subjectsByCourse(),
            // reached from a course card on the list, so the course is already known
            'preselectedCourseId' => $request->integer('course_id') ?: null,
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->validated($request);

        $data['file_link'] = $this->storeFile($request->file('file'));

        Material::create($data);

        return redirect()
            ->route('material.index')
            ->with('success', 'Added ' . $this->describe($data));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);

        // The material's own subject is merged back into the course's list so an
        // unlinked row can still be opened and corrected rather than 404ing on a
        // subject the select no longer offers.
        $subjectsByCourse = $this->subjectsByCourse();
        $subjectsByCourse[$material->course_id][] = [
            'id' => $material->subject_id,
            'name' => $material->subject->name ?? ('Subject #' . $material->subject_id),
        ];

        return view('admin.material.edit', [
            'material' => $material,
            'courses' => Course::orderBy('name')->get(),
            'subjectsByCourse' => $subjectsByCourse,
        ]);
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $data = $this->validated($request, $material);

        // the upload is optional here, so a row can be re-pointed at another
        // subject, kind or title without touching its file; a fresh upload
        // replaces the old one and takes the old file with it
        if ($request->hasFile('file')) {
            $this->deleteFile($material->file_link);
            $data['file_link'] = $this->storeFile($request->file('file'));
        }

        $material->fill($data)->save();

        return redirect()
            ->route('material.index')
            ->with('success', 'Updated ' . $this->describe($material->fresh()->toArray()));
    }

    public function delete($id)
    {
        $material = Material::findOrFail($id);
        $name = $material->displayTitle();

        // the row is keyed by the file it points at, so the file goes with it
        $this->deleteFile($material->file_link);

        $material->delete();

        return redirect()
            ->route('material.index')
            ->with('success', 'Deleted ' . $material->typeLabel() . ' "' . $name . '".');
    }

    // ---- helpers ----------------------------------------------------------

    /**
     * Validate the form.
     *
     * The kind decides what the file may be, so the mime and size rules are
     * read off the submitted kind rather than fixed. An unknown kind simply gets
     * no file rules: the `type` rule fails on its own, so nothing is ever saved
     * from a submission whose kind was not recognised.
     *
     * The subject still has to belong to the course through subject_detail, and
     * nothing has to be unique - the same subject can appear here as often as it
     * has files.
     */
    private function validated(Request $request, ?Material $material = null): array
    {
        $courseId = (int) $request->input('course_id');
        $kind = Material::KINDS[(string) $request->input('type')] ?? null;

        $messages = [
            'course_id.required' => 'Choose a course.',
            'subject_id.required' => 'Choose a subject.',
            'subject_id.exists' => 'That subject no longer exists.',
            'type.required' => 'Choose what kind of file this is.',
            'type.in' => 'Choose a book, a lecture recording or an archive.',
            'title.max' => 'The title may not be longer than 120 characters.',
            'remark.max' => 'The remark may not be longer than 500 characters.',
        ];

        if ($material === null) {
            $messages['file.required'] = 'Choose a file to upload.';
        }

        if ($kind) {
            $messages['file.mimes'] = $kind['mimes_message'];
            $messages['file.max'] = $kind['max_message'];
        }

        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
                function ($attribute, $value, $fail) use ($courseId) {
                    $linked = SubjectDetail::where('course_id', $courseId)
                        ->where('subject_id', $value)
                        ->exists();

                    if (! $linked) {
                        $fail('That subject is not linked to the selected course. Link it under Course Subjects first.');
                    }
                },
            ],
            'type' => ['required', Rule::in(array_keys(Material::KINDS))],
            'title' => ['nullable', 'string', 'max:120'],
            'remark' => ['nullable', 'string', 'max:500'],
            'file' => array_values(array_filter([
                // create always needs the file, because a row is a file; edit
                // leaves it alone so the other fields can be corrected on their own
                $material === null ? 'required' : 'nullable',
                'file',
                $kind['mimes'] ?? null,
                $kind ? 'max:' . $kind['max'] : null,
            ])),
        ], $messages);

        // only the columns the form owns; the file itself is stored by the
        // caller through storeFile() and never written as an attribute
        return array_intersect_key($validated, array_flip(['course_id', 'subject_id', 'type', 'title', 'remark']));
    }

    /**
     * Store an upload on the public disk under a name the admin list can still
     * read: the uniqid prefix stops two "notes.pdf" uploads from overwriting each
     * other, while the slugged original keeps the row recognisable instead of
     * showing a bare hash.
     */
    private function storeFile(UploadedFile $file): string
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, Material::EXTENSIONS, true)) {
            $extension = (string) $file->guessExtension();
        }

        if (! in_array($extension, Material::EXTENSIONS, true)) {
            $extension = 'bin';
        }

        $base = Str::slug(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';

        return $file->storeAs('reference', uniqid() . '_' . $base . '.' . $extension, 'public');
    }

    private function deleteFile(?string $path): void
    {
        if (filled($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * `book "notes.pdf" for Algorithms.` - what a save did, said the same way
     * after an add and after an edit.
     *
     * @param  array<string, mixed>  $material
     */
    private function describe(array $material): string
    {
        $kind = Material::KINDS[$material['type'] ?? null]['label'] ?? 'File';

        $name = filled($material['title'] ?? null)
            ? '"' . $material['title'] . '"'
            : '"' . (Material::fileLabel($material['file_link'] ?? null) ?? 'file') . '"';

        return $kind . ' ' . $name . ' for ' . $this->subjectName((int) ($material['subject_id'] ?? 0)) . '.';
    }

    private function subjectName(int $subjectId): string
    {
        return Subject::find($subjectId)?->name ?? ('Subject #' . $subjectId);
    }

    /**
     * Subjects grouped by course, through subject_detail, in the shape the form's
     * subject select filters with.
     *
     * @return array<int, array<int, array{id: int, name: string}>>
     */
    private function subjectsByCourse(): array
    {
        return SubjectDetail::query()
            ->join('subjects', 'subjects.id', '=', 'subject_detail.subject_id')
            ->orderBy('subjects.name')
            ->get(['subject_detail.course_id', 'subjects.id', 'subjects.name'])
            ->groupBy('course_id')
            ->map(fn ($rows) => $rows
                ->map(fn ($row) => ['id' => (int) $row->id, 'name' => $row->name])
                ->values()
                ->all())
            ->all();
    }
}