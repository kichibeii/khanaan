<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleDropdownItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_dropdown_item', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('article_id')->unsigned()->index();
            $table->bigInteger('dropdown_item_id')->unsigned()->index();

            $table->foreign('article_id')
                ->references('id')->on('articles')
                ->onDelete('cascade');

            $table->foreign('dropdown_item_id')
                ->references('id')->on('dropdown_items')
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
        Schema::dropIfExists('article_dropdown_item');
    }
}
