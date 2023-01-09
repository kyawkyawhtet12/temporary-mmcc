<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballMaungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_maungs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maung_group_id');
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('fee_id');
            $table->string('type');
            $table->boolean('status')->default(0);
            $table->boolean('refund')->default(0);
            $table->boolean('upteam');
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
        Schema::dropIfExists('football_maungs');
    }
}
