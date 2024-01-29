<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFootballFeeResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('football_body_fee_results', function (Blueprint $table) {
            $table->boolean('goal_error')->default(0)->after('under');
            $table->boolean('body_error')->default(0)->after('under');
        });

        Schema::table('football_maung_fee_results', function (Blueprint $table) {
            $table->boolean('goal_error')->default(0)->after('under');
            $table->boolean('body_error')->default(0)->after('under');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('football_body_fee_results', function (Blueprint $table) {
            $table->dropColumn('goal_error');
            $table->dropColumn('body_error');
        });

        Schema::table('football_maung_fee_results', function (Blueprint $table) {
            $table->dropColumn('goal_error');
            $table->dropColumn('body_error');
        });
    }
}
