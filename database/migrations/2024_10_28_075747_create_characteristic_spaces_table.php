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
        Schema::create('characteristicSpaces', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('characteristic_id')->onDelete('cascade');
            $table->foreign('characteristic_id')->references('id')->on('characteristics');

            $table->unsignedBigInteger('space_id')->onDelete('cascade');
            $table->foreign('space_id')->references('id')->on('spaces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characteristicSpaces');
    }
};
