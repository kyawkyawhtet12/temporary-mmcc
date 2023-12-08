<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballMatchStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_match_statuses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('match_id');
            $table->foreign('match_id')->references('id')->on('football_matches')->onDelete('cascade');

            $table->boolean('body_close')->default(0);
            $table->boolean('maung_close')->default(0);
            $table->boolean('all_close')->default(0);

            $table->boolean('body_refund')->default(0);
            $table->boolean('maung_refund')->default(0);
            $table->boolean('all_refund')->default(0);

            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

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
        Schema::dropIfExists('football_match_statuses');
    }
}
