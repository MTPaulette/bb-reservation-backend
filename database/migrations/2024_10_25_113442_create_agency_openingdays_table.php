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
        Schema::create('agencyOpeningdays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('agency_id')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies');

            $table->unsignedBigInteger('openingday_id')->onDelete('cascade');
            $table->foreign('openingday_id')->references('id')->on('openingdays');

            // $table->time('from', 2)->default('8:00');
            // $table->time('to', 2)->default('20:00');
            $table->enum('from', [
                '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
                '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'
            ])->default('08:00');

            $table->enum('to', [
                '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
                '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'
            ])->default('18:00');
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
        Schema::dropIfExists('agencyOpeningdays');
    }
};
