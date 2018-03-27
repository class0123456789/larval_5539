<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPermissionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_user', function (Blueprint $table) {
            $table->integer('permission_id');
            $table->integer('user_id');
            $table->primary(['permission_id', 'user_id']);
           // primary key (studentno,courseid) ); primary key (studentno,courseid) ); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permission_user');
    }
}
