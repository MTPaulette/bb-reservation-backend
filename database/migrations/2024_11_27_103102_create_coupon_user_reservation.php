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
        Schema::create('coupon_user_reservation', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('coupon_user_id')->onDelete('cascade');
            $table->foreign('coupon_user_id')->references('id')->on('coupon_users');

            $table->unsignedBigInteger('reservation_id')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_user_reservation');
    }
};