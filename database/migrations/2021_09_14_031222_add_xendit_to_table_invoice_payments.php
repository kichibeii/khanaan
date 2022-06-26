<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddXenditToTableInvoicePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('merchant_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('description')->nullable();
            $table->string('payment_channel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('payment_method');
            $table->dropColumn('merchant_name');
            $table->dropColumn('bank_code');
            $table->dropColumn('description');
            $table->dropColumn('payment_channel');
        });
    }
}
