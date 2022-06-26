<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingToTableInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->tinyInteger('tracking')->default(1)->comment('1=invoice baru, 2=invoice di konfirmasi, 3=pembayaran di approve & masuk packaging, 4=barang sudah dikirim, 5=finish')->after('id');
            $table->string('nomor_resi', 30)->nullable()->after('courier_service_description');
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
            $table->dropColumn('tracking');
            $table->dropColumn('nomor_resi');
        });
    }
}
