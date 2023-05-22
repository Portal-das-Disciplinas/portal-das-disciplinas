<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParticipantLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_links', function(Blueprint $table){
            $table->id();
            $table->foreignId('discipline_participant_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('url');
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
        Schema::dropIfExists('participant_links');
    }
}
