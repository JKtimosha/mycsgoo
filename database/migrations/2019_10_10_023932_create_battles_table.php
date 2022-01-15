<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBattlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_win_id')->nullable();
            $table->bigInteger('price');
            $table->bigInteger('win_price')->nullable();
            $table->bigInteger('players');
            $table->bigInteger('rounds');
            $table->json('rounds_history')->nullable();
            $table->string('type');
            $table->json('cases');
            $table->json('users');
            $table->json('win_items')->nullable();
            $table->bigInteger('status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_win_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battles');
    }
}
