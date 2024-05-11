<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnDescriptionInProfessorMethodologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professor_methodologies', function (Blueprint $table) {
            $table->renameColumn('description','methodology_use_description');
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
            $table->renameColumn('methodology_use_description','description');
        });
    }
}
