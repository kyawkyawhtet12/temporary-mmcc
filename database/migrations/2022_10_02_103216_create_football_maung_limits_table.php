<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballMaungLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_maung_limits', function (Blueprint $table) {
            $table->id();
            $table->integer('min_teams');
            $table->integer('max_teams');
            $table->integer('min_amount');
            $table->integer('max_amount');
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
        Schema::dropIfExists('football_maung_limits');
    }
}
