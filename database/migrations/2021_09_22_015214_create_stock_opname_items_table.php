<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOpnameItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stock_opname_id')->unsigned()->index();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('color_id')->unsigned()->index();
            $table->bigInteger('size_id')->unsigned()->index();
            $table->integer('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_opname_items');
    }
}
