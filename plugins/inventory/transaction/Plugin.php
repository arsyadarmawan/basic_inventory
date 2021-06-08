<?php namespace Inventory\Transaction;

use Backend;
use System\Classes\PluginBase;

/**
 * transaction Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'transaction',
            'description' => 'No description provided yet...',
            'author'      => 'inventory',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Inventory\Transaction\Components\Transaction' => 'transaction',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {

        return [
            'inventory.transaction.some_permission' => [
                'tab' => 'transaction',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'transaction' => [
                'label'       => 'transaction',
                'url'         => Backend::url('inventory/transaction/supplies'),
                'icon'        => 'icon-leaf',
                'permissions' => ['inventory.transaction.*'],
                'order'       => 500,
                'sideMenu' => [
                    'supplies' => [
                        'label'       => 'Supply',
                        'icon'        => 'icon-address-card',
                        'url'         => Backend::url('inventory/transaction/supplies'),
                        'permissions' => ['inventory.warehouse.*'],
                    ],
                ]
            ],
        ];
    }
}
