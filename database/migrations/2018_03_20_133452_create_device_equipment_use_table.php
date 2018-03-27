<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceEquipmentUseTable extends Migration
{
    /**设备用途表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_equipment_use', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->comment('设备用途说明');
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
        Schema::dropIfExists('device_equipment_use');
    }
}
