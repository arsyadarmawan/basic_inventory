<?php namespace Inventory\Transaction\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSuppliesTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_transaction_supplies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('stuff_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamp('entry_date')->nullable();
            $table->integer('sum')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transaction_supplies');
    }
}
