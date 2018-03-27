<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceEquipmentMaintenanceTable extends Migration
{
    /**设备维修记录表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_equipment_maintenance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_information_id')->comment('设备id');
            $table->text('content')->comment('维护情况');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_equipment_maintenance');
    }
}
