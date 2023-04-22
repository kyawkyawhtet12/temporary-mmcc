<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_withdraws', function (Blueprint $table) {
            $table->id();
            $table->string('amount')->default('0');
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('payment_provider_id');
            $table->foreign('payment_provider_id')->references('id')->on('admin_payment_providers')->onDelete('cascade');
            $table->string('account');
            $table->text('remark')->nullable();
            $table->boolean('status')->default(0);
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
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
        Schema::dropIfExists('agent_withdraws');
    }
}
