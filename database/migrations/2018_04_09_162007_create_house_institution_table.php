<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseInstitutionTable extends Migration
{
    /**机构设备配置表---这是一张中间表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_institution', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number_id',20)->comment('设备序列号');
            $table->integer('institution_id')->comment('所在机构号');
            $table->string('ipaddr',15)->nullable()->comment('设备IP');//机器ip
            $table->tinyInteger('networkid')->default(0)->comment('所属网络类型');//机器ip
            $table->string('device_save_addr',10)->nullable()->comment('使用(安、存)地址');//设备 使用(安装、存放)地址
            $table->text('device_software_config')->nullable()->comment('程序安装及软件配置情况');//软件配置情况price
            $table->tinyInteger('equipment_use_id')->comment('业务类型id');//软件配置情况price
            $table->date('work_date')->nullable()->comment('启用日期');//设备启用(开通)日期
            $table->string('group_num')->default('0-0')->comment('自助设备所属柜组(设备号-虚拟柜员号)');//所属柜组自助设备必填  或者 设备号-虚拟柜员号
            //布局类型
            $table->integer('layout_num')->default(0)->comment('自助设备布局类型');// 0 自定 1穿墙式 2 离行式
            $table->integer('employee_id')->comment('设备管理员id');// 0 自定 1穿墙式 2 离行式


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
        Schema::dropIfExists('house_institution');
    }
}
