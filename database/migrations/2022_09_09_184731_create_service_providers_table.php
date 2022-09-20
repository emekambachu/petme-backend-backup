<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_providers', static function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->string('email');
            $table->string('address')->nullable();
            $table->text('services');
            $table->string('operating_hours')->nullable();
            $table->binary('photo')->nullable();
            $table->bigInteger('staff_count')->default(0);
            $table->bigInteger('email_count')->default(0);
            $table->bigInteger('email_sent_count')->default(0);
            $table->dateTime('last_login')->nullable();
            $table->dateTime('onboarding_date')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('service_providers');
    }
}
