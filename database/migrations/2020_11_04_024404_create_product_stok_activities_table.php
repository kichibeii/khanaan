<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStokActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stok_activities', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('color_id')->nullable()->unsigned()->index();
            $table->bigInteger('size_id')->unsigned()->index();
            $table->integer('qty')->nullable();
            $table->integer('jenis')->comment('1=stok awal', '2=penambahan', '3=penjualan', '4=cancel order by sistem', '5 = cancel order manual', '6=penambahan edit', '7=so');
            $table->bigInteger('id_terkait')->nullable();

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
        Schema::dropIfExists('product_stok_activities');
    }
}
