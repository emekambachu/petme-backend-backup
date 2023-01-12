<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallet_transactions', static function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_wallet_id');
            $table->integer('debit');
            $table->integer('credit');
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
        Schema::dropIfExists('user_wallet_transactions');
    }
}
