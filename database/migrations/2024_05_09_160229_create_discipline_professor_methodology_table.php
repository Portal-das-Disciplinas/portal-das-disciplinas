<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplineProfessorMethodologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_professor_methodology', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained();
            $table->unsignedBigInteger('prof_methodology_id');
            $table->foreign('prof_methodology_id')->references('id')->on('professor_methodologies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discipline_professor_methodology');
    }
}
