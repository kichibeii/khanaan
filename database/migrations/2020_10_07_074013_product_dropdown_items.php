<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductDropdownItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_dropdown_item', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('dropdown_item_id')->unsigned()->index();

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');

            $table->foreign('dropdown_item_id')
                ->references('id')->on('dropdown_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_dropdown_item');
    }
}
