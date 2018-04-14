<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdminInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_institutions', function (Blueprint $table) {
            $table->string('xtjgdh' , 15)->nullable()->comment('柜面系统机构号');
            $table->string('rhjgbb' , 20)->nullable()->comment('人行机构机构编码');
            $table->string('address' ,80)->nullable()->comment('地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_institutions', function (Blueprint $table) {
            //
        });
    }
}
