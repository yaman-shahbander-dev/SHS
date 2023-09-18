<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeownerFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeowner_filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('homeOwner_id')->unsigned();
            $table->foreign('homeOwner_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('vendor_filter')->unsigned();
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
        Schema::dropIfExists('homeowner_filters');
    }
}
