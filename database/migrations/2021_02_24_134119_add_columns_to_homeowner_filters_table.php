<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToHomeownerFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     //for check if home owner select a vendor with specia offer or currently not working
    public function up()
    {
        Schema::table('homeowner_filters', function (Blueprint $table) {
            $table->integer('vendor_offer')->unsigned();
            $table->integer('vendor_working')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homeowner_filters', function (Blueprint $table) {
            //
        });
    }
}
