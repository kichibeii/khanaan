<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeNameToTableUserBillings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_billings', function (Blueprint $table) {
            $table->string('type_name')->nullable()->after('subdistrict_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_billings', function (Blueprint $table) {
            $table->dropColumn('type_name');
        });
    }
}
