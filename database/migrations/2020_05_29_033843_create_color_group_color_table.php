<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorGroupColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_group_color', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('color_group_id')->unsigned()->index();
            $table->bigInteger('color_id')->unsigned()->index();

            $table->foreign('color_group_id')
                ->references('id')->on('color_groups')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')->on('colors')
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
        Schema::dropIfExists('color_group_color');
    }
}
