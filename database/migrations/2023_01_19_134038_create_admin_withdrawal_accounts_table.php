<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminWithdrawalAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_withdrawal_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('owner')->nullable();
            $table->string('account')->nullable();
            $table->text('image')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('admin_withdrawal_accounts');
    }
}
