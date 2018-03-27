<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceFinancialApprovalTable extends Migration
{
    /**财务审批文件表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_financial_approval', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_no')->comment('文件审批字号');
            $table->string('file_url')->comment('pdf审批文件链接');
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
        Schema::dropIfExists('device_financial_approval');
    }
}
