<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoucherToTableInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->bigInteger('voucher_id')->unsigned()->nullable()->index()->after('courier_service_description');
            $table->string('voucher_code')->nullable()->after('voucher_id');
            $table->double('voucher_nominal')->nullable()->after('voucher_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('voucher_id');
            $table->dropColumn('voucher_code');
            $table->dropColumn('voucher_nominal');
        });
    }
}
