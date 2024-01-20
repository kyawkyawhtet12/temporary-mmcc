<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAmountColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('amount')->default(0)->change();
        });

        Schema::table('user_payment_reports', function (Blueprint $table) {
            $table->bigInteger('deposit')->default(0)->change();
            $table->bigInteger('withdraw')->default(0)->change();
            $table->bigInteger('net')->default(0)->change();
        });

        Schema::table('agent_payment_reports', function (Blueprint $table) {
            $table->bigInteger('deposit')->default(0)->change();
            $table->bigInteger('withdraw')->default(0)->change();
            $table->bigInteger('net')->default(0)->change();
        });

        Schema::table('agent_payment_all_reports', function (Blueprint $table) {
            $table->bigInteger('deposit')->default(0)->change();
            $table->bigInteger('withdraw')->default(0)->change();
            $table->bigInteger('net')->default(0)->change();
        });

        Schema::table('football_bets', function (Blueprint $table) {
            $table->bigInteger('amount')->default(0)->change();
            $table->bigInteger('net_amount')->default(0)->change();
        });

        Schema::table('football_maung_transactions', function (Blueprint $table) {
            $table->bigInteger('amount')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
