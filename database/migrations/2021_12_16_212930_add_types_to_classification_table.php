<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypesToClassificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classifications', function (Blueprint $table) {
            $table->string('type_a')->nullable();
            $table->string('type_b')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classifications', function (Blueprint $table) {
            $table->dropColumn('type_a');
            $table->dropColumn('type_b');
        });
    }
}
