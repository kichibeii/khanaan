<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropdownItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropdown_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dropdown_id')->unsigned()->index();
            $table->string('title');
            $table->smallInteger('sort_order');
            $table->tinyInteger('status');

            $table->foreign('dropdown_id')
                ->references('id')->on('dropdowns')
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
        Schema::dropIfExists('dropdown_items');
    }
}
