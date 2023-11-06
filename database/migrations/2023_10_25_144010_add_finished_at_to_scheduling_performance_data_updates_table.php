<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishedAtToSchedulingPerformanceDataUpdatesTable extends Migration
{
    /**
     * Roda as migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduling_performance_data_updates', function (Blueprint $table) {
            $table->dateTime('finished_at')->nullable();
        });
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduling_performance_data_updates', function (Blueprint $table) {
            $table->dropColumn('finished_at');
        });
    }
}
