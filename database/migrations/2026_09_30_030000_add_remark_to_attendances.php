<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the free-text `remark` note to an attendance record.
 *
 * The marking grid and the review page both need to explain a status (why a
 * student was marked late, that medical leave was approved, and so on), which
 * the status integer alone cannot carry. Nullable because most rows need none.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('attendances', 'remark')) {
            return;
        }

        Schema::table('attendances', function (Blueprint $table) {
            // 500 chars is far more than a one-line note and keeps a single
            // page load from carrying unbounded text
            $table->string('remark', 500)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('attendances', 'remark')) {
            return;
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
