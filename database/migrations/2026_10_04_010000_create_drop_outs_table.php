<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Students who left a course before it finished.
 *
 * A row is the record of the event rather than a flag on the student: the same
 * student can drop out of one class and still be attending another, and the date
 * is the part an admin needs to read back later. Nothing is unique on the student
 * for that reason - dropping out, coming back and dropping out again is a real
 * sequence and all three rows are worth keeping.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('drop_outs')) {
            return;
        }

        Schema::create('drop_outs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('drop_out_date');
            $table->string('remark', 500)->nullable();

            $table->timestamps();

            $table->index('drop_out_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drop_outs');
    }
};