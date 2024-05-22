<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnProfessorIdInProfessorMethodologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professor_methodologies', function (Blueprint $table) {
            $table->dropForeign('professor_methodologies_professor_id_foreign');
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professor_methodologies', function (Blueprint $table) {
            $table->dropForeign('professor_methodologies_professor_id_foreign');
            $table->foreign('professor_id')->references('id')->on('professors');
        });
    }
}
