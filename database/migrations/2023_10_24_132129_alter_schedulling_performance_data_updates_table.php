<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchedullingPerformanceDataUpdatesTable extends Migration
{
    /**
     * Roda as migrations
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduling_performance_data_updates',function(Blueprint $table){

            $table->dateTime('executed_at')->nullable(true)->change();

        });
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduling_performance_data_updates',function(Blueprint $table){

            $table->date('executed_at')->nullable(true)->change();

        });
    }
}
