<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 80);
            $table->string('slug')->unique();
            $table->string('color_hex', 6)->unique();
            $table->tinyInteger('sort_order')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_groups');
    }
}
