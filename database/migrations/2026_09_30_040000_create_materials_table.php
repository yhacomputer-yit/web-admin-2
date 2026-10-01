<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-subject learning materials, one row per course + subject.
 *
 * Shaped like `subject_detail` because it answers the same question for the
 * same pair: given a course and a subject, which files go with it. Each link is
 * a path on the `public` disk, not a blob, so a book or a recording is served
 * straight from storage rather than pulled through PHP.
 *
 * All three links are nullable because a subject rarely has all three kinds of
 * material on day one, and a half-filled row is easier to complete later than a
 * placeholder row is to migrate.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('materials')) {
            return;
        }

        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            // PDF of the book / handout
            $table->string('book_link')->nullable();
            // uploaded lecture recording
            $table->string('video_link')->nullable();
            // practice files, source archives, assignment starters
            $table->string('zip_link')->nullable();

            $table->timestamps();

            // one materials row per course + subject, mirroring
            // subject_detail_course_id_subject_id_unique
            $table->unique(['course_id', 'subject_id'], 'materials_course_id_subject_id_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
