<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBalanceIdToDelegates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delegates', function (Blueprint $table) {
            $table->bigInteger('balance_id')->unsigned()->nullable()->unique();
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delegates', function (Blueprint $table) {
            //
        });
    }
}
