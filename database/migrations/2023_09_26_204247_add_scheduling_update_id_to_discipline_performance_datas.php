<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchedulingUpdateIdToDisciplinePerformanceDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_performance_datas', function (Blueprint $table) {
            $table->unsignedBigInteger('scheduling_update_id');
            $table->foreign('scheduling_update_id')->references('id')->on('scheduling_performance_data_updates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discipline_performance_datas', function (Blueprint $table) {
            $table->dropForeign(['scheduling_update_id']);
            $table->dropColumn('scheduling_update_id');
        });
    }
}
