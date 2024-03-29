<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplinePerfomanceDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_performance_datas', function (Blueprint $table) {
            $table->id();
            $table->string('discipline_code');
            $table->float('sum_grades')->default(0);
            $table->float('average_grade')->default(0);
            $table->float('highest_grade')->default(0);
            $table->float('lowest_grade')->default(0);
            $table->json('professors');
            $table->integer('num_students')->default(0);
            $table->integer('num_approved_students')->default(0);
            $table->integer('num_failed_students')->default(0);
            $table->string('class_code');
            $table->string('schedule_description');
            $table->integer('year');
            $table->integer('period');
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
        Schema::dropIfExists('discipline_performance_datas');
    }
}
