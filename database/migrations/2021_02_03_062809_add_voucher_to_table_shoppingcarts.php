<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoucherToTableShoppingcarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shoppingcart', function (Blueprint $table) {
            $table->bigInteger('voucher_id')->unsigned()->nullable()->index();
            $table->double('voucher_nominal')->nullable();

            $table->foreign('voucher_id')
                ->references('id')->on('vouchers')
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
        Schema::table('shoppingcart', function (Blueprint $table) {
            $table->dropColumn('voucher_id');
            $table->dropColumn('voucher_nominal');
        });
    }
}
