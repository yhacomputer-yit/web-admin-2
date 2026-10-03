<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-subject learning reference, one row per course + subject, in the
 * `reference` table.
 *
 * Shaped like `subject_detail` because it answers the same question for the
 * same pair: given a course and a subject, which files go with it. Each link is
 * a path on the `public` disk, not a blob, so a book or a recording is served
 * straight from storage rather than pulled through PHP.
 *
 * All three links are nullable because a subject rarely has all three kinds of
 * material on day one, and a half-filled row is easier to complete later than a
 * placeholder row is to migrate.
 *
 * The one-row-per-pair shape turned out to be the ceiling on how much material a
 * subject can hold: 2026_10_03_020000_allow_multiple_files_per_subject lifts it
 * by giving every file a row of its own, and drops this index.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reference')) {
            return;
        }

        Schema::create('reference', function (Blueprint $table) {
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

            // one reference row per course + subject, mirroring
            // subject_detail_course_id_subject_id_unique; later replaced by the
            // plain index on the same pair, since a subject may hold many files
            $table->unique(['course_id', 'subject_id'], 'reference_course_id_subject_id_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference');
    }
};
