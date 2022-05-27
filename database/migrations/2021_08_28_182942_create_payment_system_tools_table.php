<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSystemToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_system_tools', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active');
            $table->boolean('is_cash')->default(true);
            $table->bigInteger('seller_id');
            $table->string('system')->nullable();
            $table->text('merchant_id')->nullable();
            $table->text('password')->nullable();
            $table->text('login')->nullable();
            $table->text('key')->nullable();
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
        Schema::dropIfExists('payment_system_tools');
    }
}
