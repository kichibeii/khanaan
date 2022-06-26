<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_additionals', function (Blueprint $table) {
            $table->id();
            $table->dateTime('additional_date');
            $table->bigInteger('uploaded_by')->unsigned()->index();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');

            $table->foreign('uploaded_by')
                ->references('id')->on('users')
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
        Schema::dropIfExists('product_additionals');
    }
}
