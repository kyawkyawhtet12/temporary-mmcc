<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('amount')->default('0');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_provider_id');
            $table->string('phone');
            $table->string('transation_no')->nullable();
            $table->string('transation_ss')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_provider_id')->references('id')->on('payment_providers')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
}
