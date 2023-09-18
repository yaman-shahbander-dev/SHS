<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("from_id")->unsigned();
            $table->integer("to_id")->unsigned();
            $table->decimal("amount", 10, 7);
            $table->foreign("from_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("to_id")->references("id")->on("users")->onDelete("cascade");
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
        Schema::dropIfExists('transfer_transactions');
    }
}
