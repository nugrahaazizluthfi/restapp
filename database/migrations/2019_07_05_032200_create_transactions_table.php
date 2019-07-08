<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_number')->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('type', ['in','out'])->default('in')->comment('type transaction');
            $table->integer('amount')->default(0);
            $table->enum('status', ['pending','completed','cancel'])->default('completed');
            $table->string('description')->nullable();
            $table->integer('sender_id')->nullable();
            $table->integer('receive_id')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
