<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverwatchPlayersCompetitiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overwatch_players_competitive', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id')->unique();
            $table->unsignedInteger('player_rank');
            $table->unsignedInteger('player_level');
            $table->unsignedInteger('player_prestige');
            $table->string('player_tier')->nullable();
            $table->string('player_avatar')->nullable();
            $table->unsignedInteger('player_gold_medals')->default(0);
            $table->unsignedInteger('player_silver_medals')->default(0);
            $table->unsignedInteger('player_bronze_medals')->default(0);
            $table->unsignedInteger('total');
            $table->unsignedInteger('wins');
            $table->unsignedInteger('loses');
            $table->unsignedInteger('ties');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overwatch_players_competitive');
    }
}
