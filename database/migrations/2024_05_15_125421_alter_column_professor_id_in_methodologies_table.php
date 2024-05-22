<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnProfessorIdInMethodologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('methodologies', function (Blueprint $table) {
            $table->dropForeign('methodologies_professor_id_foreign');
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('methodologies', function (Blueprint $table) {
            $table->dropForeign('methodologies_professor_id_foreign');
            $table->foreign('professor_id')->references('id')->on('professors');
        });
    }
}
