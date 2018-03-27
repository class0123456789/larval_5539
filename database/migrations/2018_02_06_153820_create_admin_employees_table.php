<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_employees', function (Blueprint $table) {
            $table->increments('id')->comment('员工ID');
            $table->string('name')->default('')->comment('姓名');
            $table->string('mobile')->nullable()->default('')->comment('手机号');
            $table->tinyInteger('sex')->default(0)->comment('手机号0女1男');
            $table->string('post')->nullable()->default('')->comment('岗位');
            //$table->integer('institution_id')->default(0)->comment('所属机构ID');
            //$table->integer('kind_id')->default(0)->comment('机构类型ID');
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
        Schema::dropIfExists('admin_employees');
    }
}
