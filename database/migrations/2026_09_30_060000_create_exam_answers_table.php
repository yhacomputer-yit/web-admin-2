<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One submitted answer script per student per exam sitting.
 *
 * `answer_file` is a path on the `public` disk, not a blob, so a submitted
 * script is served straight from storage instead of being pulled through PHP.
 * It stays nullable so a row can be reserved for a student who has not turned
 * in yet; the unique index below then guarantees at most one attempt per
 * student per course + subject.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('exam_answers')) {
            return;
        }

        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            // path on the `public` disk
            $table->string('answer_file')->nullable();
            $table->dateTime('submitted_date')->nullable();

            $table->timestamps();

            $table->unique(['course_id', 'subject_id', 'student_id'], 'exam_answers_course_subject_student_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
