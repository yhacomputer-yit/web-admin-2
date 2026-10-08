<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;
use App\Support\StoredFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class CertificateController extends Controller
{
    /**
     * Certificates issued to students, and which of them have been collected.
     *
     * Issued-but-not-collected is the interesting half of this list: a
     * certificate that was printed and never handed over is something the office
     * has to chase, and filtering to `received` is what turns the page into that
     * list. The student's own status is carried on each row because a certificate
     * for someone who has since left the school is the case worth looking at.
     */
    public function index(Request $request)
    {
        $courseId = $request->integer('course_id') ?: null;
        $status = (string) $request->input('status', '');
        $term = trim((string) $request->input('q', ''));

        // anything that is not one of the two states means "no filter", so a
        // hand-edited query string cannot silently blank the list
        $status = array_key_exists($status, Certificate::STATUSES) ? $status : null;

        $certificates = Certificate::with('student')
            ->when($status, fn ($q) => $q->where('remark', $status))
            ->when($courseId, fn ($q) => $q->whereHas('student.enrollments', fn ($sub) => $sub->where('course_id', $courseId)))
            ->when($term !== '', fn ($q) => $q->whereHas('student', function ($sub) use ($term) {
                $like = "%{$term}%";
                $sub->where('name', 'like', $like)
                    ->orWhere('username', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('nrc', 'like', $like);

                if (ctype_digit($term)) {
                    $sub->orWhere('id', (int) $term);
                }
            }))
            ->orderByDesc('complete_date')
            ->orderByDesc('id')
            ->get();

        return view('admin.certificate.index', [
            'certificates' => $certificates,
            'courses' => Course::orderBy('name')->get(),
            'statuses' => Certificate::STATUSES,
            'selectedCourseId' => $courseId,
            'selectedStatus' => $status,
            'term' => $term,
        ]);
    }

    public function createPage()
    {
        return view('admin.certificate.create');
    }

    public function create(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('certificate_file')) {
            $data['certificate_file'] = $this->storeFile($request->file('certificate_file'));
        }

        Certificate::create($data);

        return redirect()
            ->route('certificate.index')
            ->with('success', 'Recorded a certificate.');
    }

    public function edit($id)
    {
        $certificate = Certificate::with('student')->findOrFail($id);

        return view('admin.certificate.edit', [
            'certificate' => $certificate,
            'selectedStudent' => $certificate->student,
        ]);
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $data = $this->validated($request);

        if ($request->hasFile('certificate_file')) {
            $this->deleteFile($certificate->certificate_file);
            $data['certificate_file'] = $this->storeFile($request->file('certificate_file'));
        }

        $certificate->fill($data)->save();

        return redirect()
            ->route('certificate.index')
            ->with('success', 'Updated the certificate for ' . ($certificate->student?->name ?? 'this student') . '.');
    }

    public function delete($id)
    {
        $certificate = Certificate::with('student')->findOrFail($id);

        $name = $certificate->student?->name ?? 'this student';
        $state = $certificate->statusLabel();

        $this->deleteFile($certificate->certificate_file);
        $certificate->delete();

        return redirect()
            ->route('certificate.index')
            ->with('success', 'Deleted the ' . strtolower($state) . ' certificate for ' . $name . '.');
    }

    // ---- helpers ----------------------------------------------------------

    private function storeFile(UploadedFile $file): string
    {
        return StoredFile::store($file, 'certificate', ['jpeg', 'jpg']);
    }

    private function deleteFile(?string $path): void
    {
        StoredFile::delete($path);
    }

    /**
     * Validate the form.
     *
     * `remark` carries the handover state, so it is checked against the two words
     * the office uses and the absent value is filled in as "not received": a row
     * always has to say which side of the handover it is on, otherwise the
     * not-collected filter would quietly miss it.
     *
     * An empty `complete_date` is filled from the student's own completed
     * enrollment rather than stored as null, because that is the date the
     * certificate is for. It is left alone when the student has not finished
     * anything yet, which keeps an unknown date visibly unknown.
     */
    private function validated(Request $request): array
    {
        $studentId = (int) $request->input('student_id');

        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'complete_date' => ['nullable', 'date'],
            'remark' => ['nullable', 'string', 'max:255'],
            'certificate_file' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg', 'max:2048'],
        ], [
            'student_id.required' => 'Choose the student the certificate is for.',
            'student_id.exists' => 'That student no longer exists.',
            'complete_date.date' => 'Enter the completion date as a real date.',
            'certificate_file.image' => 'The certificate file must be an image.',
            'certificate_file.mimes' => 'The certificate file must be a JPEG or JPG.',
            'certificate_file.max' => 'The certificate file may not be larger than 2 MB.',
        ]);

        $data = array_intersect_key($validated, array_flip(['student_id', 'complete_date', 'remark', 'certificate_file']));

        $data['remark'] = ($validated['remark'] ?? null) ?: Certificate::NOT_RECEIVED;

        if (blank($data['complete_date'] ?? null)) {
            $student = Student::find($studentId);

            if ($student) {
                $data['complete_date'] = Certificate::completionDateFor($student);
            }
        }

        return $data;
    }
}