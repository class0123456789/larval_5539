<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**设备仓库
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_warehouses', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('device_class_id');//设备分类id
            $table->integer('device_model_id')->comment('设备型号id');//设备型号id
            $table->integer('device_supplier_id')->comment('设备供应商id'); //设备供应商id
            $table->integer('device_maintenaceprovier_id')->comment('设备维保商id');//设备维保厂商id
            $table->string('device_financialapproval_id')->nullable()->comment('设备审批文号id'); //设备审批文号id
            $table->string('barcode',20)->comment('设备条形码'); //设备条形码
            $table->string('serial_number',20)->comment('设备序列号'); //设备序列号date purchased
            $table->string('device_ipaddr',15)->nullable()->comment('设备IP');//机器ip
            $table->string('device_macaddr',18)->nullable()->comment('设备MAC');//机器mac地址
            $table->double('device_price')->default(0)->comment('设备价格');//机器mac地址
            $table->string('device_user',10)->nullable()->comment('设备使用者');//设备使用者
            $table->string('device_registrar',10)->nullable()->comment('设备登记人');//设备使用者
            $table->string('device_trustee',10)->nullable()->comment('设备保管人');//设备使用者
            $table->string('device_save_addr',10)->nullable()->comment('使用(安、存)地址');//设备 使用(安装、存放)地址
            $table->text('device_software_config')->nullable()->comment('软件配置情况');//软件配置情况price

            $table->tinyInteger('equipment_use_id')->default(0)->comment('设备用途id');//软件配置情况price

            $table->date('purchased_date')->nullable()->comment('购买日期');//设备购买日期
            //$table->date('created_at');
            $table->date('over_date')->nullable()->comment('维保到期日');//维保到期日
            $table->tinyInteger('expiry_date')->default(0)->comment('维保期限');//维保期限device status 年
            $table->tinyInteger('device_work_state')->default(0)->comment('当前设备工作状态');//当前设备工作状态  待修理  0正常 1待修 2淘汰 3报废 （//在设备登记时建立 设备状态登记表）
            $table->tinyInteger('device_save_state')->default(0)->comment('当前设备保存状态');//当前设备保存状态 0入库、1 调拨出 至某机构 2 从某机构 调拨入  3 借用、机构确认调入(增加信息) 3 清理  4出库(移出本系统)//(设备出入登记表)
            //$table->integer('equipment_archive_id');//设备档案id   ->记录设备修理 保养 情况
            $table->tinyInteger('house_id')->comment('设备所在仓库编号');//仓库编号  由管理员 的机构号 来确定    //网点上交时会再次用到
            $table->integer('institution_id')->default(0)->comment('设备型号');//所在机构id  登记入库时此 机构号为0
            $table->text('comment')->nullable()->comment('备注');//其它需要记录的


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
        Schema::dropIfExists('device_warehouses');
    }
}
