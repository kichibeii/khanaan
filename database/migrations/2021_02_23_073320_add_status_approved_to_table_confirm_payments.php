<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusApprovedToTableConfirmPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('confirm_payments', function (Blueprint $table) {
            $table->tinyInteger('status_approved')->default(0)->after('bank_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('confirm_payments', function (Blueprint $table) {
            $table->dropColumn('status_approved');
        });
    }
}
