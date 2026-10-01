<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One exam sitting per course + subject.
 *
 * The time columns are plain time-of-day values: `exam_date` carries the day,
 * `start_time` / `end_time` carry the window inside that day. Keeping the day
 * separate means a sitting is readable and sortable without a timestamp parse.
 *
 * `is_published` is a varchar flag rather than a boolean so the column can grow
 * into a richer state later (draft / published / closed) without a migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('exam_questions')) {
            return;
        }

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->time('start_time');
            $table->time('end_time');
            $table->date('exam_date');

            // draft / published / closed
            $table->string('is_published', 20)->default('draft');

            $table->timestamps();

            // a student looks up "my exams" by course + subject, often
            // narrowed to a day, so index that lookup shape
            $table->index(['course_id', 'subject_id', 'exam_date'], 'exam_questions_course_subject_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
