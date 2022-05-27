<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->boolean('address_type')->default(false);
            $table->integer('role_id');
            $table->integer('region_id');
            $table->integer('district_id')->nullable();
            $table->integer('postcode')->nullable();
            $table->text('street')->nullable();
            $table->text('house')->nullable();
            $table->text('entrance')->nullable();
            $table->text('floor')->nullable();
            $table->text('apartment')->nullable();
            $table->text('reference_point')->nullable();
            $table->text('x_coordinate')->nullable();
            $table->text('y_coordinate')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
