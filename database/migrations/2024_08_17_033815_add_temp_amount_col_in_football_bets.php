<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempAmountColInFootballBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('football_bets', function (Blueprint $table) {
            $table->float('temp_amount')->default(0)->after('net_amount');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('football_bets', function (Blueprint $table) {
            $table->dropColumn('temp_amount');
        });

    }
}
