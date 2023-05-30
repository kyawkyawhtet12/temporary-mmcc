<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_matches', function (Blueprint $table) {
            $table->id();
            $table->integer('round')->nullable();
            $table->dateTime('date_time');
            $table->unsignedBigInteger('league_id');
            $table->boolean('other')->default(0);
            $table->unsignedBigInteger('home_id');
            $table->integer('home_no')->nullable();
            $table->unsignedBigInteger('away_id');
            $table->integer('away_no')->nullable();
            $table->string('score')->nullable();
            $table->string('temp_score')->nullable();
            $table->boolean('status')->default('0');
            $table->boolean('type')->default(1);
            $table->boolean('calculate')->default(0);
            $table->timestamps();
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('home_id')->references('id')->on('clubs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('away_id')->references('id')->on('clubs')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('football_matches');
    }
}
