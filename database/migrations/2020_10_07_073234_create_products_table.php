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
            $table->string('code', 15)->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->bigInteger('brand_id')->unsigned()->index();
            $table->text('description');
            $table->double('price');
            $table->string('image');
            $table->string('image_second');
            $table->integer('qty');
            $table->tinyInteger('status');
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
