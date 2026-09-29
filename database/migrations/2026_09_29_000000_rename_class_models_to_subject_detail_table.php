<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('class_models') && !Schema::hasTable('subject_detail')) {
            Schema::rename('class_models', 'subject_detail');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subject_detail') && !Schema::hasTable('class_models')) {
            Schema::rename('subject_detail', 'class_models');
        }
    }
};
