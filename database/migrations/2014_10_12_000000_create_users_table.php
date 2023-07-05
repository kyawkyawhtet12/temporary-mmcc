<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('user_id')->unique();
            $table->string('phone')->nullable();
            $table->string('referral_code')->nullable();
            $table->integer('amount')->default('0');
            $table->boolean('status')->default('0');
            $table->string('password');
            $table->string('initial_password');
            $table->date('last_active')->nullable();
            $table->boolean('clear_bindings')->default(0);
            $table->string('image')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
