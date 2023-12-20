<?php

use App\Models\TwoDigitClose;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwoDigitLimitNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_digit_limit_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TwoDigitClose::class)->nullable()->constrained();
            $table->string('number')->nullable();
            $table->integer('amount')->default('0');
            $table->boolean('status')->default('0');
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
        Schema::dropIfExists('two_digit_limit_numbers');
    }
}
