<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Which class a drop-out was from.
 *
 * A student attends several courses at once, so "this student dropped out" is not
 * a statement the portal can act on until the class is named. With this column a
 * drop-out can close the one enrollment it belongs to; without it the only
 * correct thing to do would be to close all of them, which is a different event
 * (leaving the school) wearing the same record.
 *
 * Nullable rather than NOT NULL because rows written before this column existed
 * name no course. Nothing about those rows is wrong - a drop-out is still a
 * drop-out - they simply cannot move an enrollment, so the forms require a
 * course and older rows stay as the paper record they are.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('drop_outs') && Schema::hasColumn('drop_outs', 'course_id')) {
            return;
        }

        Schema::table('drop_outs', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('student_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('drop_outs') || ! Schema::hasColumn('drop_outs', 'course_id')) {
            return;
        }

        Schema::table('drop_outs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
        });
    }
};