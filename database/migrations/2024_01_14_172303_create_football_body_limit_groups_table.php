<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballBodyLimitGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_body_limit_groups', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->float('min_amount')->default(0);
            $table->float('max_amount')->default(0);
            $table->boolean("status")->default(1);
            $table->timestamps();
        });

        Schema::table('football_matches', function (Blueprint $table) {
            $table->bigInteger('body_limit')->default(0)->after('calculate_maung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('football_body_limit_groups');
    }
}
