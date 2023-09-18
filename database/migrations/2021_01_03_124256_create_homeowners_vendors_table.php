<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeownersVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeowners_vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("homeowner_id")->unsigned();
            $table->foreign("homeowner_id")->references("id")->on("users")->onDelete('cascade');
            $table->integer("vendor_id")->unsigned();
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
        Schema::dropIfExists('homeowners_vendors');
    }
}
