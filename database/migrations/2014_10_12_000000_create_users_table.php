<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_first')->default(true);
            $table->boolean('gender')->default(true);
            $table->integer('role_id')->default(2);
            $table->date('birth_day')->nullable();
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('user_name')->nullable();
            $table->string('password');
            $table->text('avatar')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
