<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per student who has been issued a certificate.
 *
 * `complete_date` mirrors the finish date on the student's enrollment rather than
 * replacing it: the form prefills it from the enrollment so the two can be compared
 * when they disagree, and it stays editable because a certificate is sometimes
 * dated later than the class ended.
 *
 * `remark` holds the handover state rather than a note. A certificate is either
 * handed over or still waiting to be collected, and a row starts life as waiting:
 * recording the student here is the decision to issue one, not the record of the
 * handover. It is stored as the two words the office uses rather than a boolean so
 * a note can be added later without a second migration, and Certificate::STATUSES
 * keeps it to those two values.
 *
 * The default is written out as a literal rather than read off the model on
 * purpose: a migration has to describe the column as it was, so a later rename of
 * the constant cannot rewrite history for a fresh install.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('certificates')) {
            return;
        }

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('complete_date')->nullable();
            $table->string('remark', 255)->default('not received');

            $table->timestamps();

            $table->index('complete_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};