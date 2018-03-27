<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddAdminEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_employees', function (Blueprint $table) {
            $table->integer('institution_id')->comment('机构id');
            //$table->addColumn('')
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_employees', function (Blueprint $table) {
            $table->dropColumn('institution_id');
        });
    }
}
