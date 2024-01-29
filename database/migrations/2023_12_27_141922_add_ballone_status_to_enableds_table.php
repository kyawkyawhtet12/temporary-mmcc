<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalloneStatusToEnabledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enableds', function (Blueprint $table) {
            $table->boolean('maung_status')->default(1)->after('three_status');
            $table->boolean('body_status')->default(1)->after('three_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enableds', function (Blueprint $table) {
            $table->dropColumn('maung_status');
            $table->dropColumn('body_status');
        });
    }
}
