<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('trans_prepare_id');
            $table->bigInteger('click_trans_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('merchant_trans_id')->nullable();
            $table->bigInteger('amount');
            $table->text('merchant_prepare_id')->nullable();
            $table->integer('action')->nullable();
            $table->integer('error')->nullable();
            $table->string('error_note')->nullable();
            $table->text('sign_string')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
