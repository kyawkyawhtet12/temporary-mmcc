<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoLuckyNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_lucky_numbers', function (Blueprint $table) {
            $table->id();
            // $table->string('lottery_time');
            $table->unsignedBigInteger('lottery_time_id');
            $table->foreign('lottery_time_id')->references('id')->on('lottery_times')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('two_digit_id');
            $table->foreign('two_digit_id')->references('id')->on('two_digits')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Approved', 'Rejected']);
            $table->boolean('type')->default(0);
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
        Schema::dropIfExists('two_lucky_numbers');
    }
}
