<?php namespace Inventory\Warehouse\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Stuffs Back-end Controller
 */
class Stuffs extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ImportExportController'
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Inventory.Warehouse', 'warehouse', 'stuffs');
    }

}
