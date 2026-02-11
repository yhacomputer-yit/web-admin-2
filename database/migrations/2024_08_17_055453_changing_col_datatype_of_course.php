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
        Schema::table('courses', function (Blueprint $table) {
            $table->longText('about')->nullable()->change();
            $table->longText('links')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('about')->change();
            $table->dropColumn('links')->change();
        });
        
    }
};
