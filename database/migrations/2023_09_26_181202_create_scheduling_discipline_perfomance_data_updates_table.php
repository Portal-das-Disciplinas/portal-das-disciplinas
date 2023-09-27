<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulingDisciplinePerfomanceDataUpdatesTable extends Migration
{
    /**
     * Roda as migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduling_performance_data_updates', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('PENDING');
            $table->date('executed_at')->nullable(true);
            $table->integer('update_time')->default(0);
            $table->integer('num_new_datas')->default(0);
            $table->integer('year');
            $table->integer('period');
            $table->text('error_description')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverte migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduling_performance_data_updates');
    }
}
