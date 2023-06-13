<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnabledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enableds', function (Blueprint $table) {
            $table->id();
            $table->boolean('two_thai_status')->default(0);
            $table->boolean('two_dubai_status')->default(0);
            $table->boolean('three_status')->default(0);
            $table->boolean('close_all_bets')->default(0);
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
        Schema::dropIfExists('enableds');
    }
}
