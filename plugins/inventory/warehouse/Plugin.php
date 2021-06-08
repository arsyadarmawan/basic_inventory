<?php namespace Inventory\Warehouse;

use Backend;
use System\Classes\PluginBase;
use RainLab\User\Models\User;
/**
 * warehouse Plugin Information File
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
            'name'        => 'warehouse',
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
        User::extend(function ($model) {
            $model->hasMany['stuffs'] = [
                'Inventory\Warehouse\Models\Stuff',
                'key'   => 'user_id'
            ];

            $model->hasMany['stuffs'] = [
                'Inventory\Warehouse\Models\Log',
                'key'   => 'user_id'
            ];
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Inventory\Warehouse\Components\Inventory' => 'inventory',
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
        return []; // Remove this line to activate

        return [
            'inventory.warehouse.some_permission' => [
                'tab' => 'warehouse',
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
            'warehouse' => [
                'label'       => 'warehouse',
                'url'         => Backend::url('inventory/warehouse/warehouses'),
                'icon'        => 'icon-leaf',
                'permissions' => ['inventory.warehouse.*'],
                'order'       => 500,
                'sideMenu' => [
                    'warehouses' => [
                        'label'       => 'Warehouses',
                        'icon'        => 'icon-address-card',
                        'url'         => Backend::url('inventory/warehouse/warehouses'),
                        'permissions' => ['inventory.warehouse.*'],
                    ],
                    'stuffs' => [
                        'label'       => 'Stuffs',
                        'icon'        => 'icon-file-text-o',
                        'url'         => Backend::url('inventory/warehouse/stuffs'),
                        'permissions' => ['inventory.warehouse.*'],
                    ],
                    'categories' => [
                        'label'       => 'Categories',
                        'icon'        => 'icon-circle',
                        'url'         => Backend::url('inventory/warehouse/categories'),
                        'permissions' => ['inventory.warehouse.*'],
                    ],
                    'contacts' => [
                        'label'       => 'Reports',
                        'icon'        => 'icon-bar-chart',
                        'url'         => Backend::url('inventory/warehouse/contacts'),
                        'permissions' => ['inventory.warehouse.*'],
                    ],
                ]
            ],
        ];
    }
}
