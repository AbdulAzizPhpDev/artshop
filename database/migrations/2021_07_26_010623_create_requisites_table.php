<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisites', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->integer('stir');
            $table->string('ownership');
            $table->text('activity');
            $table->string('official_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('meddle_name');
            $table->text('bank_account');
            $table->string('bank_info');
            $table->string('bank_name');
            $table->text('bank_account2')->nullable();
            $table->string('bank_info2')->nullable();
            $table->string('bank_name2')->nullable();
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
        Schema::dropIfExists('requisites');
    }
}
