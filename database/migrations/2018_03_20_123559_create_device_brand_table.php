<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceBrandTable extends Migration
{
    /**设备品牌表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_brand', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('品牌名');
            $table->string('contact')->comment('联系人');
            $table->string('phone')->comment('联系电话');
            $table->string('address')->comment('联系地址');
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
        Schema::dropIfExists('device_brand');
    }
}
