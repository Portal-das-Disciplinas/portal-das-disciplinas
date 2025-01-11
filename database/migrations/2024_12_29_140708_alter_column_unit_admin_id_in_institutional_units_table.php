<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnUnitAdminIdInInstitutionalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutional_units', function (Blueprint $table) {
            $table->dropForeign(['unit_admin_id']);
            $table->foreign('unit_admin_id')
                ->references('id')
                ->on('unit_admins')->onDelete('set null');

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
            $table->foreign('unit_admin_id')
                ->references('id')
                ->on('unit_admins');
        });
    }
}
