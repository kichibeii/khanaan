<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('color_id')->unsigned()->index();
            $table->smallInteger('sort_order');

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
        Schema::dropIfExists('product_colors');
    }
}
