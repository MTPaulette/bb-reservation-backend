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
            $table->string('prefix');

            $table->unsignedBigInteger('ressource_id');
            $table->foreign('ressource_id')->references('id')->on('ressources');

            $table->enum('state', ['sollicit', 'partially payed', 'confirmed', 'totally payed'])->default('sollicit');
            $table->dateTime('date');
            $table->unsignedInteger('amount_due');
            $table->text('note');
            $table->dateTime('is_gift');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('receiver_user_id');
            $table->foreign('receiver_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('giver_user_id');
            $table->foreign('giver_user_id')->references('id')->on('users');

            $table->text('reason_for_gift');
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
