<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_billings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->string('name');
            $table->bigInteger('province_id')->unsigned()->index();
            $table->bigInteger('city_id')->unsigned()->index();
            $table->bigInteger('subdistrict_id')->unsigned()->index();
            $table->string('address');
            $table->string('postcode', 6);
            $table->string('handphone', 20);

            $table->foreign('invoice_id')
                ->references('id')->on('invoices')
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
        Schema::dropIfExists('invoice_billings');
    }
}
