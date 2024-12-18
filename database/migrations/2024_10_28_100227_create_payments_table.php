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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
            $table->text('note_en')->nullable();
            $table->text('note_fr')->nullable();
            $table->enum('payment_method', ['Bank', 'MTN Money', 'Cash', 'Orange Money']);
            $table->string('payment_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('bill_number')->nullable();

            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations');

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
        Schema::dropIfExists('payments');
    }
};
