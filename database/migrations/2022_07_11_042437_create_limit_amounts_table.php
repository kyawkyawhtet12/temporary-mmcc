<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLimitAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limit_amounts', function (Blueprint $table) {
            $table->id();
            $table->integer('two_min_amount');
            $table->integer('two_max_amount');
            $table->integer('three_min_amount');
            $table->integer('three_max_amount');
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
        Schema::dropIfExists('limit_amounts');
    }
}
