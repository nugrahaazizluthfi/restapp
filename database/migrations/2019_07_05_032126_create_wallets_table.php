<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('balance')->default(0);
            $table->dateTime('last_transaction')->nullable();
            $table->integer('user_id');
        });
        
        Schema::create('wallets_track', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['in','out'])->default('in')->comment('type transaction');
            $table->integer('balance_now')->default(0);
            $table->integer('balance_prev')->default(0);
            $table->integer('amount')->default(0);
            $table->text('desc')->nullable();
            $table->dateTime('recorded_at')->nullable();
            $table->integer('wallet_id');
            $table->string('ref')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('wallets_track');
    }
}
