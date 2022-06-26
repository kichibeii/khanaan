<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductColorSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_color_sizes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('color_id')->unsigned()->index();
            $table->bigInteger('size_id')->unsigned()->index();

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')->on('colors')
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
        Schema::dropIfExists('product_color_sizes');
    }
}
