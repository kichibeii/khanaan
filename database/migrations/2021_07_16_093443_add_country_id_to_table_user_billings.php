<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdToTableUserBillings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_billings', function (Blueprint $table) {
            $table->integer('country_id')->nullable()->after('name');
            $table->string('country_name')->nullable()->after('country_id');
            $table->string('province_name')->nullable()->after('province_id');
            $table->string('city_name')->nullable()->after('city_id');
            $table->string('subdistrict_name')->nullable()->after('subdistrict_id');
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
            $table->dropColumn('country_id');
            $table->dropColumn('country_name');
            $table->dropColumn('province_name');
            $table->dropColumn('city_name');
            $table->dropColumn('subdistrict_name');
        });
    }
}
