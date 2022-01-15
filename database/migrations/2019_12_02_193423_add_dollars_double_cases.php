<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDollarsDoubleCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn('old_price_en');
            $table->dropColumn('price_en');
        });
        Schema::table('cases', function (Blueprint $table) {
            $table->double('old_price_en', 8,2)->default(0.00)->after('old_price');
            $table->double('price_en', 8,2)->default(0.00)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
