<?php namespace Inventory\Warehouse\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLogsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_warehouse_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('stuff_id')->nullable();
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->timestamp('entry_date')->nullable();
            $table->timestamp('out_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_warehouse_logs');
    }
}
