<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One mark: a student's score on one subject of one course, on one day.
 *
 * `grade_id` is nullable on purpose. The two halves of this row are filled in at
 * different times - the mark comes off the answer script, the grade is settled
 * later, sometimes after the term - so a result with no grade is a mark that has
 * not been graded yet rather than a broken row. Filling it in later is the update,
 * not a new row.
 *
 * The index follows the order the marks are read back in: a class, its subjects,
 * then the day.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('grading_results')) {
            return;
        }

        Schema::create('grading_results', function (Blueprint $table) {
            $table->id();

            // the band the mark falls in; null until it has been graded
            $table->foreignId('grade_id')->nullable()->constrained('gradings')->nullOnDelete();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->decimal('score', 6, 2);
            $table->date('date');

            $table->timestamps();

            $table->index(['course_id', 'subject_id', 'date'], 'grading_results_course_subject_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_results');
    }
};