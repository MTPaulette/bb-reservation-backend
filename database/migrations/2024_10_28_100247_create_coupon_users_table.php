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
        Schema::create('coupon_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('coupon_id')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons');

            $table->unsignedBigInteger('user_id')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments');

            $table->dateTime('used_on');
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
        Schema::dropIfExists('coupon_users');
    }
};
