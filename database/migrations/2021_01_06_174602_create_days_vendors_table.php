<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days_vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("day_id")->unsigned();
            $table->Integer("vendor_id")->unsigned();

            $table->foreign("day_id")->references("id")->on("days")->onDelete('cascade');
            $table->foreign("vendor_id")->references("id")->on("users")->onDelete('cascade');

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
        Schema::dropIfExists('days_vendors');
    }
}
