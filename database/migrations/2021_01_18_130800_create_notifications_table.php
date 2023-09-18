<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->BigInteger('country')->unsigned()->nullable();
            $table->BigInteger('city')->unsigned()->nullable();
            $table->integer('category')->unsigned()->nullable();
            $table->integer('subcategory')->unsigned()->nullable();
            $table->string('title');
            $table->string('body');
            $table->foreign('country')->references('id')->on('countries');
            $table->foreign('city')->references('id')->on('cities');
            $table->foreign('category')->references('id')->on('categories');
            $table->foreign('subcategory')->references('id')->on('sub_categories');
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
        Schema::dropIfExists('notifications');
    }
}
