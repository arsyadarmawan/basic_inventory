<?php namespace Inventory\Warehouse\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddFromToSupplyTable extends Migration
{
    public function up()
    {
        Schema::table('inventory_transaction_supplies', function(Blueprint $table) {
            $table->string('from')->after('sum')->nullable();

        });

        Schema::table('inventory_transaction_outcomes', function(Blueprint $table) {
            $table->string('from')->after('sum')->nullable();

        });
    }

    public function down()
    {
        Schema::table('inventory_transaction_supplies', function(Blueprint $table) {
           	$table->dropColumn('from');
        });

        Schema::table('inventory_transaction_outcomes', function(Blueprint $table) {
            $table->string('from')->after('sum')->nullable();

        });
    }
}
