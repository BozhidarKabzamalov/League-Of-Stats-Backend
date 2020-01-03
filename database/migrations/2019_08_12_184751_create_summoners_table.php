<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummonersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('summoner_name');
            $table->string('region');
            $table->string('puuid');
            $table->string('encrypted_account_id');
            $table->string('encrypted_summoner_id');
            $table->json('summoner_info');
            $table->json('summoner_league');
            $table->json('summoner_matches');
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
        Schema::dropIfExists('summoners');
    }
}
