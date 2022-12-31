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
            $table->string('email');
            $table->string('mobile');
            $table->string('specialization');
            $table->string('password');
            $table->unsignedBigInteger('service_provider_category_id')->nullable();
            $table->string('address')->nullable();
            $table->longText('opening_hours')->nullable();
            $table->binary('image')->nullable();
            $table->longText('image_path')->nullable();
            $table->bigInteger('staff_count')->default(0);
            $table->bigInteger('email_count')->default(0);
            $table->bigInteger('email_sent_count')->default(0);
            $table->dateTime('last_login')->nullable();
            $table->dateTime('onboarding_date')->nullable();
            $table->string('verification_token')->nullable();
            $table->boolean('token_used')->default(false);
            $table->tinyInteger('status')->default(0);
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
