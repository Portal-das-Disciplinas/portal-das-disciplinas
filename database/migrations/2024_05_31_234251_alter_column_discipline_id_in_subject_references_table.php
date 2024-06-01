<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnDisciplineIdInSubjectReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_references', function (Blueprint $table) {
            $table->dropForeign(['discipline_id']);
            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_references', function (Blueprint $table) {
            $table->dropForeign('discipline_id');
            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade');
        });
    }
}
