<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirm_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_id')->unsigned()->index();
            $table->date('transfer_date');
            $table->double('amount');
            $table->bigInteger('bank_id')->unsigned()->index();
            $table->bigInteger('approved_by')->unsigned()->nullable()->index();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('confirm_payments');
    }
}
