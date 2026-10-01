<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Prepares the existing tables for the course/section linking, class
 * completion and attendance features.
 *
 * 1. student_enrollments.status / complete_date already exist but every legacy
 *    row has a NULL status, and status is nullable. Backfill and lock it down.
 * 2. The old `student_enrollment_unique` hard index (student, course, section)
 *    makes re-enrolling a student impossible even after a class is completed.
 *    The requirement is only "no duplicate *active* enrollment", so the hard
 *    index is replaced by a plain index and the active-only rule is enforced in
 *    the form request instead.
 * 3. course_sections had no uniqueness, so the same link could be inserted
 *    twice. Duplicates are collapsed before the unique index is added.
 * 4. attendances had no uniqueness, so re-marking the same student/course/
 *    subject/section/day would silently create duplicate rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ---- 1. backfill legacy enrollment status -------------------------
        DB::table('student_enrollments')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update(['status' => 'active']);

        // a completed/dropped row must always carry a complete_date
        DB::table('student_enrollments')
            ->whereIn('status', ['completed', 'dropped'])
            ->whereNull('complete_date')
            ->update(['complete_date' => now()]);

        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->change();
        });

        // ---- 2. hard unique -> plain index -------------------------------
        // Order matters: MySQL backs the student_id foreign key with the
        // leading column of an existing index, and student_enrollment_unique
        // happens to be the only one starting with student_id. The replacement
        // must therefore exist before the unique can be dropped, otherwise the
        // FK loses its supporting index and the drop is refused.
        $indexes = $this->indexNames('student_enrollments');

        if (! in_array('student_enrollments_active_lookup', $indexes, true)) {
            Schema::table('student_enrollments', function (Blueprint $table) {
                $table->index(['student_id', 'course_id', 'section_id', 'status'], 'student_enrollments_active_lookup');
            });
        }

        if (in_array('student_enrollment_unique', $this->indexNames('student_enrollments'), true)) {
            Schema::table('student_enrollments', function (Blueprint $table) {
                $table->dropUnique('student_enrollment_unique');
            });
        }

        // ---- 3. course_sections uniqueness -------------------------------
        // collapse duplicate links, keeping the oldest row of each pair
        $dupes = DB::table('course_sections')
            ->select('course_id', 'section_id', DB::raw('MIN(id) AS keep_id'), DB::raw('COUNT(*) AS n'))
            ->groupBy('course_id', 'section_id')
            ->having('n', '>', 1)
            ->get();

        foreach ($dupes as $dupe) {
            DB::table('course_sections')
                ->where('course_id', $dupe->course_id)
                ->where('section_id', $dupe->section_id)
                ->where('id', '!=', $dupe->keep_id)
                ->delete();
        }

        $csIndexes = $this->indexNames('course_sections');
        if (!in_array('course_sections_course_id_section_id_unique', $csIndexes, true)) {
            Schema::table('course_sections', function (Blueprint $table) {
                $table->unique(['course_id', 'section_id'], 'course_sections_course_id_section_id_unique');
            });
        }

        // ---- 4. attendances uniqueness -----------------------------------
        $attIndexes = $this->indexNames('attendances');
        if (!in_array('attendances_student_course_subject_section_date_unique', $attIndexes, true)) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->unique(
                    ['student_id', 'course_id', 'subject_id', 'section_id', 'date'],
                    'attendances_student_course_subject_section_date_unique'
                );
            });
        }

        // ---- 5. read indexes ---------------------------------------------
        // The unique index above leads with student_id, so it cannot serve the
        // two date-range reads this feature adds: the admin history page scans
        // every student's records for a date window, and the student portal
        // calendar scans one student's records for a month. Both want date as
        // the leading column.
        if (!in_array('attendances_date_status_lookup', $this->indexNames('attendances'), true)) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index(['date', 'status'], 'attendances_date_status_lookup');
            });
        }

        if (!in_array('attendances_student_date_lookup', $this->indexNames('attendances'), true)) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index(['student_id', 'date'], 'attendances_student_date_lookup');
            });
        }
    }

    public function down(): void
    {
        // Collapse rows that would violate the restored hard unique index.
        $dupeEnrollments = DB::table('student_enrollments')
            ->select('student_id', 'course_id', 'section_id', DB::raw('MIN(id) AS keep_id'), DB::raw('COUNT(*) AS n'))
            ->groupBy('student_id', 'course_id', 'section_id')
            ->having('n', '>', 1)
            ->get();

        foreach ($dupeEnrollments as $dupe) {
            DB::table('student_enrollments')
                ->where('student_id', $dupe->student_id)
                ->where('course_id', $dupe->course_id)
                ->where('id', '!=', $dupe->keep_id)
                ->when(
                    $dupe->section_id === null,
                    fn ($q) => $q->whereNull('section_id'),
                    fn ($q) => $q->where('section_id', $dupe->section_id)
                )
                ->delete();
        }

        // Restore the unique index before dropping the plain one, for the same
        // foreign-key reason described in up().
        if (! in_array('student_enrollment_unique', $this->indexNames('student_enrollments'), true)) {
            Schema::table('student_enrollments', function (Blueprint $table) {
                $table->unique(['student_id', 'course_id', 'section_id'], 'student_enrollment_unique');
            });
        }

        // the student_id lookups go first, so the FK keeps a supporting index
        // while the unique that also leads with student_id is still in place
        foreach ([
            ['attendances', 'attendances_student_date_lookup'],
            ['attendances', 'attendances_date_status_lookup'],
            ['attendances', 'attendances_student_course_subject_section_date_unique'],
            ['course_sections', 'course_sections_course_id_section_id_unique'],
            ['student_enrollments', 'student_enrollments_active_lookup'],
        ] as [$table, $index]) {
            if (in_array($index, $this->indexNames($table), true)) {
                Schema::table($table, fn (Blueprint $t) => $t->dropIndex($index));
            }
        }

        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->string('status', 20)->nullable()->default(null)->change();
        });
    }

    /**
     * @return array<int, string>
     */
    private function indexNames(string $table): array
    {
        return collect(DB::select("SHOW INDEX FROM `$table`"))
            ->pluck('Key_name')
            ->unique()
            ->values()
            ->all();
    }
};
