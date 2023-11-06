<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Essa migration atera o tipo da coluna details de string para text
 */
class AlterCollaboratorProductionsTable extends Migration
{
    /**
     *Roda as migrations migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaborator_productions',function(Blueprint $table){
            $table->text('details')->change();
        });
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('collaborator_productions',function(Blueprint $table){
            $table->string('details')->change();
        });*/
    }
}
