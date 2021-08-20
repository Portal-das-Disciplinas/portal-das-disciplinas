<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassDiscTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications_disciplines', function (Blueprint $table) {
            $table->unsignedBigInteger('classification_id');
            $table->foreign('classification_id')->references('id')
                ->on('classifications')
                ->onDelete('cascade');
            $table->unsignedBigInteger('discipline_id');
            $table->foreign('discipline_id')->references('id')
                ->on('disciplines')
                ->onDelete('cascade');
            $table->integer("value");
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
        Schema::dropIfExists('classifications_disciplines');
    }
}
