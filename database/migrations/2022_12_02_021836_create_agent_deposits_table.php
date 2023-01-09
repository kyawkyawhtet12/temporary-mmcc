<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->unsignedBigInteger('payment_provider_id');
            $table->foreign('payment_provider_id')->references('id')->on('payment_providers')->onDelete('cascade');
            $table->string('account');
            $table->string('amount')->default('0');
            $table->text('remark')->nullable();
            $table->text('transaction')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('agent_deposits');
    }
}
