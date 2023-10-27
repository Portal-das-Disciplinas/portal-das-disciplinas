<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemesterPerformanceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semester_performance_data', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('period');
            $table->integer('data_amount')->default(0);
            $table->dateTime('last_data_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('semester_performance_data');
    }
}
