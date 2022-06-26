<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOthersToTableDropdownItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dropdown_items', function (Blueprint $table) {
            $table->string('slug')->after('title')->nullable();
            $table->string('image')->after('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dropdown_items', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('image');
        });
    }
}
