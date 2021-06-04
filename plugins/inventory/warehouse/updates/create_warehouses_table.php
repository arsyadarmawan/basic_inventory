<?php namespace Inventory\Warehouse\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateWarehousesTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_warehouse_warehouses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->boolean('is_active')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->text('facility')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_warehouse_warehouses');
    }
}
