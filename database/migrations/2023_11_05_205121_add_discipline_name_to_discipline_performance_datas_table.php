<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisciplineNameToDisciplinePerformanceDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_performance_datas', function (Blueprint $table) {
            $table->string('discipline_name');
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
            $table->dropColumn('discipline_name');
        });
    }
}
