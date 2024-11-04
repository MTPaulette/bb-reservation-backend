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
        Schema::create('agency_opening_days', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('agency_id')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies');

            $table->unsignedBigInteger('openingDay_id')->onDelete('cascade');
            $table->foreign('openingDay_id')->references('id')->on('opening_days');

            $table->dateTime('from');
            $table->dateTime('to');
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
        Schema::dropIfExists('agency_opening_days');
    }
};
