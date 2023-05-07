<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Collaborators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborators', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('bond');
            $table->string('role');
            $table->string('email');
            $table->string('lattes')->nullable();
            $table->string('github')->nullable();
            $table->string('urlPhoto')->nullable();
            $table->boolean('isManager')->default(false);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('collaborators');
    }
}
