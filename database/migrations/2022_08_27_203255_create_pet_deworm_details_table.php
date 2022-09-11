<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetDewormDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pet_deworm_details', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('drug');
            $table->integer('administer_rate')->nullable();
            $table->string('frequency')->nullable();
            $table->integer('administration_duration')->nullable();
            $table->dateTime('last_session')->nullable();
            $table->dateTime('next_session')->nullable();
            $table->string('created_by')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('pet_deworm_details');
    }
}
