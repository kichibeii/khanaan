<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_billings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->tinyInteger('is_main')->default(0);
            $table->string('name');
            $table->bigInteger('province_id')->unsigned()->index();
            $table->bigInteger('city_id')->unsigned()->index();
            $table->bigInteger('subdistrict_id')->unsigned()->index();
            $table->string('address');
            $table->string('postcode', 6);
            $table->string('handphone', 20);
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
        Schema::dropIfExists('user_billings');
    }
}
