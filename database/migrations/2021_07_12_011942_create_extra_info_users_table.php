<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraInfoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_info_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->text('description')->nullable();
            $table->text('email')->nullable();
            $table->text('office_number')->nullable();
            $table->text('image_passport')->nullable();
            $table->text('image_order')->nullable();
            $table->text('image_logo')->nullable();
            $table->text('image_license')->nullable();
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
        Schema::dropIfExists('extra_info_users');
    }
}
