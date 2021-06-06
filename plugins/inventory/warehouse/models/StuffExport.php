<?php namespace Inventory\Warehouse\Models;

use Model;
use Inventory\Warehouse\Models\Stuff;
use ApplicationException;
/**
 * StuffExport Model
 */
class StuffExport extends \Backend\Models\ExportModel
{
    public $table = 'inventory_warehouse_stuffs';

    public $belongsTo = [
        'warehouse' => \Inventory\Warehouse\Models\Warehouse::class,
        'user'      => \Rainlab\User\Models\User::class,
    ];

    protected $visible = ['warehouse.name'];

    protected $appends = [
        'warehouse_name',
        'user_name',
    ];

    public function exportData($columns, $sessionKey = null)
    {
        $subscribers = self::all();
        $subscribers->each(function($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $subscribers->toArray();
    }

    public function getWarehouseNameAttribute() {
        if (!$this->warehouse) {
            return '';
        }
        else {
            return $this->warehouse->name;
        }
    }

    public function getUserNameAttribute() {
        if (!$this->user) {
            return '';
        }
        else {
            return $this->user->name;
        }
    }
}
