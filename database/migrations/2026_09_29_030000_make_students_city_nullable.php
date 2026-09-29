<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The legacy "city" column was left as NOT NULL when the students table was
     * renamed. New student records do not use it, so make it optional.
     */
    public function up(): void
    {
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'city')) {
            DB::statement('ALTER TABLE `students` MODIFY `city` VARCHAR(191) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'city')) {
            DB::statement("UPDATE `students` SET `city` = '' WHERE `city` IS NULL");
            DB::statement('ALTER TABLE `students` MODIFY `city` VARCHAR(191) NOT NULL');
        }
    }
};
