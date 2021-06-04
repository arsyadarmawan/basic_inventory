<?php namespace Inventory\Warehouse\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateStuffsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_warehouse_stuffs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('characteristic')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_warehouse_stuffs');
    }
}
