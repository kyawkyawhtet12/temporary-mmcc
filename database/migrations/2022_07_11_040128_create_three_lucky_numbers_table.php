<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreeLuckyNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('three_lucky_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('three_digit_id')->nullable();
            $table->date('date')->nullable();
            $table->string('votes')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected']);
            $table->string('round')->nullable();
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
        Schema::dropIfExists('three_lucky_numbers');
    }
}
