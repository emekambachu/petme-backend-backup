<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_transactions', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('appointment_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('service_provider_id')->index();
            $table->integer('amount');
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
        Schema::dropIfExists('appointment_transactions');
    }
}
