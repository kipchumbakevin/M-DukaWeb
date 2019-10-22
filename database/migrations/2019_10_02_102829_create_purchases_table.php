<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id');
            $table->unsignedInteger('purchase_image_id');
            $table->foreign('purchase_image_id')->references('id')->on('purchase_images');
            $table->integer('size_id')->nullable();
            $table->string('size')->nullable();
            $table->string('quantity');
            $table->string('buying_price');
            $table->string('selling_price');
            $table->string('total');
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
        Schema::dropIfExists('purchases');
    }
}
