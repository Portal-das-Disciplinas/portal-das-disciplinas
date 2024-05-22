<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsDisciplineIdProfMethodologyIdInDisciplineProfessorMethodologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discipline_professor_methodology', function (Blueprint $table) {
            $table->dropForeign('discipline_professor_methodology_discipline_id_foreign');
            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade');
            $table->dropForeign('discipline_professor_methodology_prof_methodology_id_foreign');
            $table->foreign('prof_methodology_id')->references('id')->on('professor_methodologies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discipline_professor_methodology', function (Blueprint $table) {
            $table->dropForeign('discipline_professor_methodology_discipline_id_foreign');
            $table->foreign('discipline_id')->references('id')->on('disciplines');
            $table->dropForeign('discipline_professor_methodology_prof_methodology_id_foreign');
            $table->foreign('prof_methodology_id')->references('id')->on('professor_methodologies');
        });
    }
}
