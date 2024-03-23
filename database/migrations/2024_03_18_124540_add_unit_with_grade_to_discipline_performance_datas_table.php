<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitWithGradeToDisciplinePerformanceDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_performance_datas', function (Blueprint $table) {
            $table->boolean('unit1_with_grade')->default(true);
            $table->boolean('unit2_with_grade')->default(true);
            $table->boolean('unit3_with_grade')->default(true);
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
            $table->dropColumn('unit1_with_grade');
            $table->dropColumn('unit2_with_grade');
            $table->dropColumn('unit3_with_grade');
        });
    }
}
