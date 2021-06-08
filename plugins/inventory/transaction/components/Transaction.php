<?php namespace Inventory\Transaction\Components;

use Inventory\Transaction\Models\Supply;
use Cms\Classes\ComponentBase;
use Carbon\Carbon;

class Transaction extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'transaction Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onCreateSupply()
    {
        $data = post();
        $item = new Supply;
        $item->entry_date = Carbon::parse(array_get($data,'entry_date'))->format('Y-m-d');
        $item->stuff_id = array_get($data,'stuff_id');
        $item->sum = (int) array_get($data,'sum');
        $item->save();

        $item->stuff->total += (int) array_get($data,'sum');
        $item->stuff->save();

        \Flash::success('Supply stuff already created');
        return \Redirect::to('/supply/stuff');
    }

    public function listDataSupply()
    {
        $items = Supply::orderBy('id','DESC')->paginate(10);
        return $items;
    }
}
