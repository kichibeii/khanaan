<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->tinyInteger('tracking');
            $table->date('activity_date');

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
        Schema::dropIfExists('invoice_trackings');
    }
}
