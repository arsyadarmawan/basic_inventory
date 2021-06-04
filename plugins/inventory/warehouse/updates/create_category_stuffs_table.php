<?php namespace Inventory\Warehouse\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCategoryStuffsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_warehouse_category_stuffs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('stuff_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_warehouse_category_stuffs');
    }
}
