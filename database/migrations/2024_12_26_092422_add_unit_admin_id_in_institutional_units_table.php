<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitAdminIdInInstitutionalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutional_units', function (Blueprint $table) {
            $table->foreignId('unit_admin_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutional_units', function (Blueprint $table) {
            $table->dropForeign(['unit_admin_id']);
            $table->dropColumn('unit_admin_id');
        });
    }
}
