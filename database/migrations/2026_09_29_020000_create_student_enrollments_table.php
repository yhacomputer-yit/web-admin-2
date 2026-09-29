<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('student_enrollments')) {
            return;
        }

        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->date('enroll_date');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');

            $table->unique(['student_id', 'course_id', 'section_id'], 'student_enrollment_unique');
            $table->index(['course_id', 'enroll_date']);
        });

        // backfill: move the legacy course/section from students into enrollments
        DB::table('students')
            ->whereNotNull('course_id')
            ->orderBy('id')
            ->chunk(200, function ($students) {
                foreach ($students as $student) {
                    $exists = DB::table('student_enrollments')
                        ->where('student_id', $student->id)
                        ->where('course_id', $student->course_id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('student_enrollments')->insert([
                        'student_id' => $student->id,
                        'course_id' => $student->course_id,
                        'section_id' => $student->section_id,
                        'enroll_date' => $student->enroll_date ?: now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
