<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToTableSlideshows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slideshows', function (Blueprint $table) {
            $table->tinyInteger('logo')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slideshows', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
}
