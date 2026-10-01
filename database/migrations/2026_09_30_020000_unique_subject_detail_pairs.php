<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The course <-> subject pivot had no uniqueness, so the same subject could be
 * attached to a course twice. The attendance subject dropdown reads from this
 * table, so duplicates would surface as repeated options.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('subject_detail')) {
            return;
        }

        $duplicates = DB::table('subject_detail')
            ->select('course_id', 'subject_id', DB::raw('MIN(id) AS keep_id'), DB::raw('COUNT(*) AS n'))
            ->groupBy('course_id', 'subject_id')
            ->having('n', '>', 1)
            ->get();

        foreach ($duplicates as $dupe) {
            DB::table('subject_detail')
                ->where('course_id', $dupe->course_id)
                ->where('subject_id', $dupe->subject_id)
                ->where('id', '!=', $dupe->keep_id)
                ->delete();
        }

        $indexes = collect(DB::select('SHOW INDEX FROM `subject_detail`'))->pluck('Key_name')->unique()->all();

        if (! in_array('subject_detail_course_id_subject_id_unique', $indexes, true)) {
            Schema::table('subject_detail', function (Blueprint $table) {
                $table->unique(['course_id', 'subject_id'], 'subject_detail_course_id_subject_id_unique');
            });
        }
    }

    public function down(): void
    {
        $indexes = collect(DB::select('SHOW INDEX FROM `subject_detail`'))->pluck('Key_name')->unique()->all();

        if (in_array('subject_detail_course_id_subject_id_unique', $indexes, true)) {
            Schema::table('subject_detail', function (Blueprint $table) {
                $table->dropUnique('subject_detail_course_id_subject_id_unique');
            });
        }
    }
};
