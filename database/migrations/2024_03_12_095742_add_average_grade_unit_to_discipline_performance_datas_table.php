<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAverageGradeUnitToDisciplinePerformanceDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_performance_datas', function (Blueprint $table) {
           $table->float('average_grade_unit1')->default(0.0);
           $table->float('average_grade_unit2')->default(0.0);
           $table->float('average_grade_unit3')->default(0.0);
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
            $table->dropColumn('average_grade_unit1');
            $table->dropColumn('average_grade_unit2');
            $table->dropColumn('average_grade_unit3');
        });
    }
}
