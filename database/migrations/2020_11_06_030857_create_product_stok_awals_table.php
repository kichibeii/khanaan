<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStokAwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stok_awals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('color_id')->nullable()->unsigned()->index();
            $table->bigInteger('size_id')->unsigned()->index();
            $table->integer('qty')->nullable();

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
        Schema::dropIfExists('product_stok_awals');
    }
}
