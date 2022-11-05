<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRedesToProfessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->longText('rede_social1')->nullable();
            $table->longText('link_rsocial1')->nullable();
            $table->longText('rede_social2')->nullable();
            $table->longText('link_rsocial2')->nullable();
            $table->longText('rede_social3')->nullable();
            $table->longText('link_rsocial3')->nullable();
            $table->longText('rede_social4')->nullable();
            $table->longText('link_rsocial4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->dropColumn('rede_social1');
            $table->dropColumn('link_rsocial1');
            $table->dropColumn('rede_social2');
            $table->dropColumn('link_rsocial2');
            $table->dropColumn('rede_social3');
            $table->dropColumn('link_rsocial3');
            $table->dropColumn('rede_social4');
            $table->dropColumn('link_rsocial4');
        });
    }
}
