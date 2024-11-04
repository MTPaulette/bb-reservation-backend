<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_time_slots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('reservation_id')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations');

            $table->unsignedBigInteger('timeSlot_id')->onDelete('cascade');
            $table->foreign('timeSlot_id')->references('id')->on('time_slots');

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
        Schema::dropIfExists('reservation_time_slots');
    }
};
