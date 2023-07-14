<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDisciplineIdInDisciplineParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('discipline_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discipline_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('discipline_id')->nullable(false)->change();
        });
    }
}
