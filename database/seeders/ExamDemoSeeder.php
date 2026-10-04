<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * A few sittings so the student exam pages have something on them.
 *
 * The windows are worked out from `now()` instead of hard-coded dates, because
 * the submit button only exists while a sitting is running: a fixed date stops
 * being testable the day after it is seeded. Running this again moves the "open
 * now" sitting onto the current day, so the submit UI can be tried whenever.
 *
 * Sittings are made for the subjects of the courses students are already
 * enrolled in, and nothing here creates an enrollment. A sitting a student is
 * not enrolled for stays invisible to them, so inventing one would leave the
 * page empty anyway -- and inventing an enrollment would change data this
 * seeder has no business changing.
 *
 * @see \Database\Seeders\DatabaseSeeder
 */
class ExamDemoSeeder extends Seeder
{
    public function run(): void
    {
        $courseIds = DB::table('student_enrollments')
            ->distinct()
            ->pluck('course_id');

        if ($courseIds->isEmpty()) {
            $this->command?->warn('No enrollments at all, so nothing seeded. Enrol a student first.');

            return;
        }

        // a sitting is per course + subject, so the subjects the course actually
        // teaches are the only ones a student can be shown an exam for
        $pairs = DB::table('subject_detail as sd')
            ->join('subjects', 'subjects.id', '=', 'sd.subject_id')
            ->whereIn('sd.course_id', $courseIds)
            ->orderBy('sd.course_id')
            ->orderBy('sd.subject_id')
            ->get(['sd.course_id', 'sd.subject_id', 'subjects.name as subject_name']);

        $now = Carbon::now();
        $perCourse = [];

        foreach ($pairs as $pair) {
            // the first subject of each course is the one left open, so every
            // enrolled student has at least one sitting they can submit to
            $index = $perCourse[$pair->course_id] ?? 0;
            $perCourse[$pair->course_id] = $index + 1;

            [$day, $start, $end, $answered] = $this->sittingAt($index, $now);

            $sitting = ExamQuestion::updateOrCreate(
                [
                    'course_id' => $pair->course_id,
                    'subject_id' => $pair->subject_id,
                ],
                [
                    'exam_date' => $day->toDateString(),
                    'start_time' => $start,
                    'end_time' => $end,
                    'is_published' => ExamQuestion::PUBLISHED,
                    'question_file' => $this->questionPaper($pair->subject_name),
                ]
            );

            $this->command?->line(sprintf(
                '  #%d  %s  %s %s - %s  [%s]',
                $sitting->id,
                $pair->subject_name,
                $day->toDateString(),
                substr($start, 0, 5),
                substr($end, 0, 5),
                $sitting->scheduleStatus($now),
            ));

            if ($answered) {
                $this->answerFor($sitting, $pair->subject_name, $day, $now);
            }
        }

        $this->command?->info('Demo sittings seeded. Re-run this to keep the open one open.');
    }

    /**
     * One sitting's day, window, and whether a script is already on record.
     *
     * The four slots between them cover every state the exam list can render:
     * an open sitting with the submit button, a past one already answered, a
     * past one that was missed, and one still to come.
     *
     * @return array{0: Carbon, 1: string, 2: string, 3: bool}
     */
    private function sittingAt(int $index, Carbon $now): array
    {
        if ($index === 0) {
            // the window is pinned around now and clamped to the day, so the
            // sitting is still open at any hour it is seeded at
            $start = max($now->copy()->startOfDay(), $now->copy()->subMinutes(45));
            $end = min($now->copy()->endOfDay(), $now->copy()->addHours(3));

            return [$now->copy(), $start->format('H:i:s'), $end->format('H:i:s'), false];
        }

        return match ($index) {
            1 => [$now->copy()->subDays(7), '09:00:00', '11:00:00', true],
            2 => [$now->copy()->addDays(3), '09:00:00', '12:00:00', false],
            default => [$now->copy()->subDays(3), '13:00:00', '15:00:00', false],
        };
    }

    /**
     * Put a script on record for every student taking that sitting, so the
     * "Answered" state and its file link are visible without uploading one.
     *
     * The same file is shared between the students of one sitting: it is demo
     * data, and a row per student with a real path behind it is all the page
     * reads.
     */
    private function answerFor(ExamQuestion $sitting, string $subject, Carbon $day, Carbon $now): void
    {
        $studentIds = DB::table('student_enrollments')
            ->where('course_id', $sitting->course_id)
            ->distinct()
            ->pluck('student_id');

        if ($studentIds->isEmpty()) {
            return;
        }

        $path = 'exam/answers/demo-' . Str::slug($subject) . '-answer.pdf';

        Storage::disk('public')->put($path, $this->pdf(['Demo answer script - ' . $subject]));

        foreach ($studentIds as $studentId) {
            ExamAnswer::updateOrCreate(
                [
                    'course_id' => $sitting->course_id,
                    'subject_id' => $sitting->subject_id,
                    'student_id' => $studentId,
                ],
                [
                    'answer_file' => $path,
                    'submitted_date' => $day->copy()->setTime(10, 15),
                ]
            );
        }
    }

    /**
     * A paper for one sitting, on the private disk the paper endpoint reads.
     *
     * The private disk is the whole point of the choice: a demo paper on the
     * public disk would have a URL, and the URL would outlive the exam window the
     * feature is built to enforce. Nothing seeds a real question paper, so a
     * student on the seeded data sees a real PDF that renders and a real deadline
     * that closes over it.
     */
    private function questionPaper(string $subject): string
    {
        $path = ExamQuestion::PAPER_DIRECTORY . '/demo-' . Str::slug($subject) . '-paper.pdf';

        Storage::disk(ExamQuestion::PAPER_DISK)->put($path, $this->pdf([
            'Demo question paper - ' . $subject,
            '',
            '1. Explain one idea from this subject in your own words.',
            '2. Show the steps for a worked example of your choice.',
            '3. State what you would change, and why.',
        ]));

        return $path;
    }

    /**
     * A one page PDF, written by hand rather than pulled in as a dependency.
     *
     * The student only ever opens it or reads its name, so it has to be a real
     * PDF the browser will render, and nothing more than that. A new page starts
     * 72pt lower than the last, which is enough for a handful of lines.
     *
     * @param  array<int, string>  $lines
     */
    private function pdf(array $lines): string
    {
        $escaped = array_map(
            fn (string $line) => str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], $line),
            $lines
        );

        $body = '';
        foreach ($escaped as $index => $line) {
            $body .= "BT /F1 14 Tf 40 " . (720 - ($index * 28)) . " Td ({$line}) Tj ET\n";
        }

        $stream = rtrim($body);

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842]'
                . ' /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
            '<< /Length ' . strlen($stream) . " >>\nstream\n{$stream}\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $number => $objectBody) {
            $offsets[$number] = strlen($pdf);
            $pdf .= ($number + 1) . " 0 obj\n{$objectBody}\nendobj\n";
        }

        // the cross reference table is byte offsets, so it can only be written
        // once the objects it points at have been laid down
        $startxref = strlen($pdf);
        $pdf .= 'xref' . "\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= 'trailer' . "\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$startxref}\n%%EOF\n";

        return $pdf;
    }
}
