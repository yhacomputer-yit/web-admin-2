<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The exam paper itself, as a path on the private disk.
 *
 * The column already exists on the databases this feature was built against, so
 * this migration is a no-op there: it exists so a fresh `migrate` produces the
 * same table the running site has, rather than failing later on a missing column.
 *
 * The path is deliberately not a public URL. A paper stored on the public disk
 * has a URL anyone can guess, which would make the time gate on the sitting
 * advisory instead of enforced.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('exam_questions') || Schema::hasColumn('exam_questions', 'question_file')) {
            return;
        }

        Schema::table('exam_questions', function (Blueprint $table) {
            $table->string('question_file', 300)->nullable()->after('is_published');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('exam_questions') || ! Schema::hasColumn('exam_questions', 'question_file')) {
            return;
        }

        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropColumn('question_file');
        });
    }
};
