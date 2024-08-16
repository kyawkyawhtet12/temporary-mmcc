<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBettingIdToWinRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('win_records', function (Blueprint $table) {
            $table->integer('round')->nullable()->after('amount');
            $table->integer('betting_id')->nullable()->after('amount');
            $table->boolean('status')->default(0)->after('amount');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('win_records', function (Blueprint $table) {
            $table->dropColumn('round');
            $table->dropColumn('betting_id');
            $table->dropColumn('status');
        });

    }
}
