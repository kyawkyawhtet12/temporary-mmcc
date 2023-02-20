<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballMaungFeeResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_maung_fee_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_id');
            $table->foreign('fee_id')->references('id')->on('football_maung_fees')->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('home')->default(0);
            $table->integer('away')->default(0);
            $table->integer('over')->default(0);
            $table->integer('under')->default(0);
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
        Schema::dropIfExists('football_maung_fee_results');
    }
}
