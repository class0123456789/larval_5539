<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenanceProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_provider', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('厂商名');
            $table->string('contact')->nullable()->comment('联系人');
            $table->string('phone')->nullable()->comment('联系电话');
            $table->string('address')->nullable()->comment('联系地址');
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
        Schema::dropIfExists('maintenance_provider');
    }
}
