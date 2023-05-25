<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDisciplines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->dropForeign(['professor_id']); // Remove a restrição de chave estrangeira existente
            $table->foreign('professor_id')
                ->references('id')
                ->on('professors')
                ->onDelete('set null'); // Adiciona a nova restrição de chave estrangeira
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->dropForeign(['professor_id']);
            $table->foreign('professor_id')
                ->references('id')
                ->on('professors')
                ->onDelete('cascade');
        });
    }
}
