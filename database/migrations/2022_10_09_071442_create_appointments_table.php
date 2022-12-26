<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_provider_id');
            $table->unsignedBigInteger('service_provider_category_id')->nullable();
            $table->unsignedBigInteger('appointment_type_id')->nullable();
            $table->string('location')->nullable();
            $table->longText('note');
            $table->integer('total_cost');
            $table->dateTime('appointment_time');
            $table->boolean('status')->default(0);
            $table->boolean('user_approved')->default(0);
            $table->boolean('service_provider_approved')->default(0);
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
        Schema::dropIfExists('appointments');
    }
}
