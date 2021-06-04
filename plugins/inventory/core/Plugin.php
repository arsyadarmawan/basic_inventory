<?php namespace Inventory\Core;

use Backend, Event;
use System\Classes\PluginBase;
use Inventory\Core\Classes\Subdomain;
/**
 * core Plugin Information File
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
            'name'        => 'core',
            'description' => 'No description provided yet...',
            'author'      => 'Inventory',
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
        \Illuminate\Pagination\AbstractPaginator::defaultView("pagination::bootstrap-4");
        $subdomainClass = new Subdomain();

        if (!empty($subdomainClass->subdomain) && $subdomainClass->subdomain != 'supplier') {
            return header("Location: " . \Config::get('app.url'));
        }

        Event::listen('cms.page.beforeDisplay', function($controller, $url, $page) use ($subdomainClass) {
            $controller->vars['app_url'] = \Config::get('app.url');
            $controller->vars['url_base'] = preg_replace('#^https?://#', '', \Config::get('app.url'));
            $controller->vars['subdomain'] = $subdomainClass->subdomain;
            // $controller->vars['company'] = $subdomainClass->company;
        });

        Event::listen('cms.theme.getActiveTheme', function () use ($subdomainClass) {
            if ($subdomainClass->subdomain == 'supplier') {
                return 'admin';
            } 
            else {
                return 'my-theme';
            }
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Inventory\Core\Components\MyComponent' => 'myComponent',
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
            'Inventory.core.some_permission' => [
                'tab' => 'core',
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
        return []; // Remove this line to activate

        return [
            'core' => [
                'label'       => 'core',
                'url'         => Backend::url('Inventory/core/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['Inventory.core.*'],
                'order'       => 500,
            ],
        ];
    }
}
