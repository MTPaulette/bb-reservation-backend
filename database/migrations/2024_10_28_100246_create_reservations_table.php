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
            $table->string('prefix')->nullable();

            $table->unsignedBigInteger('ressource_id');
            $table->foreign('ressource_id')->references('id')->on('ressources');

            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('state_en', ['sollicit', 'partially payed', 'confirmed', 'totally payed'])->default('sollicit');
            $table->enum('state_fr', ['sollicité', 'partiellement payé', 'confirmé', 'totallement payé'])->default('sollicité');
            $table->unsignedInteger('amount_due');
            $table->text('note_en')->nullable();
            $table->text('note_fr')->nullable();
            $table->boolean('is_gift')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('receiver_user_id')->nullable();
            $table->foreign('receiver_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('giver_user_id')->nullable();
            $table->foreign('giver_user_id')->references('id')->on('users');

            $table->text('reason_for_gift')->nullable();
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
