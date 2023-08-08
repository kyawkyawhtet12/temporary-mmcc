<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBettingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betting_records', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->float('amount');
            $table->string('type');
            $table->string('count')->default(1);
            $table->string('result')->nullable();
            $table->string('win_amount')->nullable();

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
        Schema::dropIfExists('betting_records');
    }
}
