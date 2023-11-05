<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataResearchedAtToSemesterPerformanceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('semester_performance_data', function (Blueprint $table) {
            $table->dateTime('data_researched_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('semester_performance_data', function (Blueprint $table) {
            $table->dropColumn('data_researched_at');
        });
    }
}
