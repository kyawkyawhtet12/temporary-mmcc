<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToBettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betting_records', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('two_lucky_draws', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('three_lucky_draws', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('football_bets', function (Blueprint $table) {
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('betting_records', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('two_lucky_draws', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('three_lucky_draws', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('football_bets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

    }
}
