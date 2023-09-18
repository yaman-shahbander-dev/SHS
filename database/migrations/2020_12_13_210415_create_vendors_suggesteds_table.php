<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsSuggestedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_suggesteds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->integer('user_id')->unsigned();
            //$table->integer('subscription_id')->unsigned()->after('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');

            $table->string('phone', 127);
            $table->double('balance')->unique();

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
        Schema::dropIfExists('vendors_suggesteds');
    }
}
