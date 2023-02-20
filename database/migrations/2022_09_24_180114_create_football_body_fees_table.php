<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballBodyFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_body_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->string('body')->nullable();
            $table->string('goals')->nullable();
            $table->boolean('up_team')->default(1);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('by');
            $table->foreign('match_id')->references('id')->on('football_matches')->onDelete('cascade')->onUpdate('cascade');
           
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
        Schema::dropIfExists('football_body_fees');
    }
}
