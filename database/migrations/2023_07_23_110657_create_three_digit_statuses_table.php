<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreeDigitStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('three_digit_statuses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');

            $table->unsignedBigInteger('three_digit_id');
            $table->foreign('three_digit_id')->references('id')->on('three_digits')->onDelete('cascade');

            $table->boolean('status')->default('0');
            $table->integer('amount')->default('0');
            $table->date('date')->nullable();
            $table->string('round')->nullable();

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
        Schema::dropIfExists('three_digit_statuses');
    }
}
