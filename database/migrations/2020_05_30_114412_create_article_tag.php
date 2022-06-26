<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_tags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('article_id')->unsigned()->index();
            $table->bigInteger('tag_id')->unsigned()->index();

            $table->foreign('article_id')
                ->references('id')->on('articles')
                ->onDelete('cascade');

            $table->foreign('tag_id')
                ->references('id')->on('tags')
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
        Schema::dropIfExists('article_tags');
    }
}
