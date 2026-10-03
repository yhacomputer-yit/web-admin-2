<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the free-text `remark` note to a reference row.
 *
 * The three link columns cannot say why a subject ships a lecture recording
 * instead of a book, or that a zip is still being prepared, so the admin form
 * needs somewhere to keep that context next to the files it describes.
 * Nullable because most rows need no note at all.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('reference', 'remark')) {
            return;
        }

        Schema::table('reference', function (Blueprint $table) {
            $table->string('remark', 500)->nullable()->after('zip_link');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('reference', 'remark')) {
            return;
        }

        Schema::table('reference', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
