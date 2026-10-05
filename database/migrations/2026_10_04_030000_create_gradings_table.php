<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The grade bands a mark is bucketed into: a name and the ceiling of the band.
 *
 * `score` is the top of the band rather than a mark of its own, so A carries 100
 * and D carries 50, and a student's result is graded by looking up which band a
 * mark falls under. That is why the name is unique: two bands called the same
 * thing would make that lookup ambiguous.
 *
 * The column is a decimal because a ceiling is a limit, not a whole number of
 * marks, and the results table stores marks on the same scale.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('gradings')) {
            return;
        }

        Schema::create('gradings', function (Blueprint $table) {
            $table->id();

            $table->string('name', 60);
            $table->decimal('score', 6, 2);

            $table->timestamps();

            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gradings');
    }
};