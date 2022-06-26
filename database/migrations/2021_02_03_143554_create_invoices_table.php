<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number', 10)->unique();
            $table->date('invoice_date');
            $table->dateTime('invoice_due_date');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('destination_origin_id')->unsigned()->index();
            $table->bigInteger('destination_city_id')->unsigned()->index();
            $table->bigInteger('destination_destination_id')->unsigned()->index();
            $table->string('destination_destination_type', 20)->default('subdistrict');
            $table->string('destination_etd', 30)->nullable();

            $table->string('courier', 30);
            $table->string('courier_name', 50);
            $table->string('courier_service', 50)->nullable();
            $table->string('courier_service_description', 100)->nullable();
            
            $table->double('total_weight');
            $table->double('total_order');
            $table->double('total_shipping_charge')->nullable();
            $table->double('unique_code');
            $table->double('grand_total');
            $table->tinyInteger('status_payment')->comment('0=belum dibayar, 1=sudah dibayar');
            $table->tinyInteger('status_invoice')->comment('0=expired, 1=active');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('invoices');
    }
}
