<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreeDigitTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('three_digit_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('three_digit_id');
            $table->bigInteger('amount')->default('0');
            $table->foreign('three_digit_id')->references('id')->on('three_digits')->onDelete('cascade');
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
        Schema::dropIfExists('three_digit_transactions');
    }
}
