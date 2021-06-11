<?php namespace Inventory\Transaction\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateAddjustmentsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_transaction_addjustments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('stuff_id')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->integer('count')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transaction_addjustments');
    }
}
