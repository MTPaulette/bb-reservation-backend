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
        Schema::dropIfExists('agencyOpeningdays');
    }
};
