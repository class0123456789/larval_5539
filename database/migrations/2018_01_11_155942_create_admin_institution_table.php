<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_institutions', function (Blueprint $table) {
            $table->increments('id')->comment('机构ID');
            $table->string('name')->default('')->comment('机构名称');
            $table->integer('parent_id')->default(0)->comment('父机构ID');
            $table->integer('kind_id')->default(0)->comment('机构类型ID');

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
        Schema::dropIfExists('admin_institutions');
    }
}
