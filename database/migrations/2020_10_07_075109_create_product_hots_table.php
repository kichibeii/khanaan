<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductHotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_hots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->smallInteger('priority');

            $table->foreign('product_id')
                ->references('id')->on('products')
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
        Schema::dropIfExists('product_hots');
    }
}
