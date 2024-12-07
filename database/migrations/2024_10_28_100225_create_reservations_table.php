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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ressource_id');
            $table->foreign('ressource_id')->references('id')->on('ressources');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users');

            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->time('start_hour');
            $table->time('end_hour');

            $table->unsignedInteger('initial_amount');
            $table->unsignedInteger('amount_due');
            $table->enum('state', ['pending', 'partially paid', 'confirmed', 'totally paid', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->text('reason_for_cancellation')->nullable();

            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->foreign('cancelled_by')->references('id')->on('users');
            $table->timestamp('cancelled_at')->nullable();
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
        Schema::dropIfExists('reservations');
    }
};



            /*
            $table->boolean('is_gift')->default(0);
            $table->unsignedBigInteger('receiver_user_id')->nullable();
            $table->foreign('receiver_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('giver_user_id')->nullable();
            $table->foreign('giver_user_id')->references('id')->on('users');
            $table->text('reason_for_gift')->nullable();
            */
