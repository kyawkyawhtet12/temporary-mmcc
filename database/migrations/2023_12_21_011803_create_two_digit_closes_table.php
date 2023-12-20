<?php

use App\Models\LotteryTime;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwoDigitClosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_digit_closes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignIdFor(LotteryTime::class)->nullable()->constrained();
            $table->date('date')->nullable();
            $table->boolean('status')->default(0);
            $table->foreignId('admin_id')->nullable()->constrained();
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
        Schema::dropIfExists('two_digit_closes');
    }
}
