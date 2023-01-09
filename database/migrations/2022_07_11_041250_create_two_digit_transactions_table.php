<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoDigitTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_digit_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('two_digit_id');
            $table->foreign('two_digit_id')->references('id')->on('two_digits')->onDelete('cascade');
            $table->bigInteger('amount')->default('0');
            // $table->string('lottery_time');
            $table->unsignedBigInteger('lottery_time_id');
            $table->foreign('lottery_time_id')->references('id')->on('lottery_times')->onDelete('cascade');
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
        Schema::dropIfExists('two_digit_transactions');
    }
}
