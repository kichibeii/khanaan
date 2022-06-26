<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlideshowMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slideshow_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('slideshow_id')->unsigned()->index();
            $table->string('title');
            $table->string('value')->nullable();

            $table->foreign('slideshow_id')
                ->references('id')->on('slideshows')
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
        Schema::dropIfExists('slideshow_metas');
    }
}
