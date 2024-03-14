<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSumUnitGradesToDisciplinePerformanceDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_performance_datas', function (Blueprint $table) {
            $table->float('sum_unit1_grades')->default(0.0);
            $table->float('sum_unit2_grades')->default(0.0);
            $table->float('sum_unit3_grades')->default(0.0);
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
            $table->dropColumn('sum_unit1_grades');
            $table->dropColumn('sum_unit2_grades');
            $table->dropColumn('sum_unit3_grades');
        });
    }
}
