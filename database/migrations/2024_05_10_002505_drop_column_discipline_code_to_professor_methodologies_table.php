<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnDisciplineCodeToProfessorMethodologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professor_methodologies', function (Blueprint $table) {
            $table->dropColumn('discipline_code');
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
            $table->string('discipline_code',64);
        });
    }
}
