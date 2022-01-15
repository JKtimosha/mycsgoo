<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain')->nullable();
            $table->string('sitename')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('vk_group')->nullable();
            $table->string('support_email')->nullable();
            $table->string('site_name_protocol')->nullable();
            $table->string('game_id')->nullable();
            $table->string('bot_steamid')->nullable();
            $table->string('bot_key')->nullable();
            $table->string('bot_username')->nullable();
            $table->string('bot_password')->nullable();
            $table->string('bot_shared_secret')->nullable();
            $table->string('bot_identity_secret')->nullable();
            $table->string('payment')->nullable();
            $table->string('freekassa_id')->nullable();
            $table->string('freekassa_secret1')->nullable();
            $table->string('freekassa_secret2')->nullable();
            $table->string('unitpay_public')->nullable();
            $table->string('unitpay_secret')->nullable();
            $table->integer('bonus_free')->nullable();
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
        Schema::dropIfExists('configs');
    }
}
