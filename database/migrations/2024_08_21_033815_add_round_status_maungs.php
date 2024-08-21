<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoundStatusMaungs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('football_bets', function (Blueprint $table) {
            $table->string('round')->default(0)->after('status');
            $table->boolean('is_done')->default(0)->after('status');
        });

        Schema::table('football_maung_groups', function (Blueprint $table) {
            $table->string('round')->default(0)->after('count');
            $table->boolean('status')->default(0)->after('count');
            $table->boolean('is_done')->default(0)->after('count');
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
            $table->dropColumn('round');
            $table->dropColumn('is_done');
        });

        Schema::table('football_maung_groups', function (Blueprint $table) {
            $table->dropColumn('round');
            $table->dropColumn('status');
            $table->dropColumn('is_done');
        });

    }
}
