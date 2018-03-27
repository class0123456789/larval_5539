<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceClassTable extends Migration
{
    /**设备分类表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_class', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->comment('分类名称');
            $table->integer('parent_id')->comment('父级分类id');
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
        Schema::dropIfExists('device_class');
    }
}
