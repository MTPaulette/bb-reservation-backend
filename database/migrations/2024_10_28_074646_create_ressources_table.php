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
        Schema::create('ressources', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('price_hour');
            $table->unsignedInteger('price_midday')->nullable();
            $table->unsignedInteger('price_day')->nullable();
            $table->unsignedInteger('price_week')->nullable();
            $table->unsignedInteger('price_month')->nullable();

            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agencies');

            $table->unsignedBigInteger('space_id');
            $table->foreign('space_id')->references('id')->on('spaces');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

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
        Schema::dropIfExists('ressources');
    }
};
