<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_winners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('two_lucky_number_id');
            $table->unsignedBigInteger('two_lucky_draw_id');
            $table->foreign('two_lucky_number_id')->references('id')->on('two_lucky_numbers')->onDelete('cascade');
            $table->foreign('two_lucky_draw_id')->references('id')->on('two_lucky_draws')->onDelete('cascade');
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
        Schema::dropIfExists('two_winners');
    }
}
