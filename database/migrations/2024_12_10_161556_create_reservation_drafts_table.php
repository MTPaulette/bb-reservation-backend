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
        Schema::create('reservation_drafts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ressource_id');
            $table->unsignedBigInteger('client_id')->nullable();

            $table->date('start_date');
            $table->date('end_date');
            
            $table->enum('start_hour', [
                '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
                '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'
            ]);

            $table->enum('end_hour', [
                '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
                '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'
            ]);

            $table->unsignedInteger('initial_amount');
            $table->unsignedInteger('amount_due');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('reservation_drafts');
    }
};
