<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreeLuckyDrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('three_lucky_draws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('three_digit_id');
            $table->bigInteger('amount')->default('0');
            $table->string('round')->nullable();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('three_digit_id')->references('id')->on('three_digits')->onDelete('cascade');

            $table->string('za');

            $table->unsignedBigInteger('betting_record_id');
            $table->foreign('betting_record_id')->references('id')->on('betting_records')->onDelete('cascade');

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
        Schema::dropIfExists('three_lucky_draws');
    }
}
