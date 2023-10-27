<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDisciplinePerformanceDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_performance_datas',function(Blueprint $table){
            $table->dropForeign('discipline_performance_datas_scheduling_update_id_foreign');
            $table->dropColumn('scheduling_update_id');
            $table->unsignedBigInteger('semester_performance_id');
            $table->foreign('semester_performance_id')->references('id')->on('semester_performance_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discipline_performance_datas',function(Blueprint $table){
            $table->unsignedBigInteger('scheduling_update_id');
            $table->foreign('scheduling_update_id')->references('id')->on('scheduling_performance_data_updates');
            $table->dropForeign('discipline_performance_datas_semester_performance_id_foreign');
            $table->dropColumn('semester_performance_id');
        });
    }
}
