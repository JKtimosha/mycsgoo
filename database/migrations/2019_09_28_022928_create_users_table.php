<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('steamid64');
            $table->string('avatar');
            $table->double('balance', 8,2)->default(0);
            $table->string('trade_link')->nullable();
            $table->integer('is_admin')->default(0);
            $table->string('type')->default('default');
            $table->bigInteger('profit')->default(0);
            $table->bigInteger('chance_roulette')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
