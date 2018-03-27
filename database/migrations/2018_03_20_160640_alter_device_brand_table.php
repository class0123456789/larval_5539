<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeviceBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_brand', function (Blueprint $table) {
            $table->string('contact')->nullable()->change(); //可以为空
            $table->string('phone')->nullable()->change(); //可以为空
            $table->string('address')->nullable()->change(); //可以为空
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_brand', function (Blueprint $table) {
            //
        });
    }
}
