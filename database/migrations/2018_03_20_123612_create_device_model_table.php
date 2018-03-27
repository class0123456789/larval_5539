<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceModelTable extends Migration
{
    /**设备型号表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_model', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->comment('设备型号');
            $table->integer('brand_id')->comment('品牌id');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_model');
    }
}
