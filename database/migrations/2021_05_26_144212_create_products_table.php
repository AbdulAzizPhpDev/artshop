<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_archive')->default(true);
            $table->boolean('is_cash')->default(true);
            $table->bigInteger('merchant_id');
            $table->bigInteger('quantity')->default(1);
            $table->bigInteger('price')->default(100);
            $table->integer('catalog_id');
            $table->integer('min')->default(1);
            $table->string('selling_type')->nullable();
            $table->string('name_uz', 255);
            $table->string('name_ru', 255);
            $table->string('made_in', 255)->nullable();
            $table->text('description_uz')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('image');
            $table->text('maker_name')->nullable();
            $table->text('maker_phone')->nullable();

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
        Schema::dropIfExists('products');
    }
}
