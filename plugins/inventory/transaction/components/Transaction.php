<?php namespace Inventory\Transaction\Components;

use Inventory\Transaction\Models\{Supply, Outcome};
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
        if ((int) array_get($data,'sum') < 0) {
            \Flash::error('Data less than 0, please check again');
            return redirect()->refresh();   
        }
        $item = new Supply;
        $item->entry_date = Carbon::parse(array_get($data,'entry_date'))->format('Y-m-d');
        $item->stuff_id = array_get($data,'stuff_id');
        $item->sum = (int) array_get($data,'sum');
        $item->user_id = array_get($data,'supplier_id');
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

    public function listDataOutcome()
    {
        $items = Outcome::orderBy('id','DESC')->paginate(10);
        return $items;
    }

    public function onDeleteSupply()
    {
        $data = post();
        $item = Supply::find((int) array_get($data,'supply_id'));
        $item->delete();
        \Flash::error('Log already updated');
        return \Redirect::refresh();
    }

    public function onDeleteOutcome()
    {
        $data = post();
        $item = Outcome::find((int) array_get($data,'outcome_id'));
        $item->delete();
        \Flash::error('Log already updated');
        return \Redirect::refresh();
    }

    public function onCreateOutcome()
    {
        $data = post();
        if ((int) array_get($data,'sum') < 0) {
            \Flash::error('Data less than 0, please check again');
            return redirect()->refresh();   
        }
        $item = new Outcome;
        $item->out_date = Carbon::parse(array_get($data,'entry_date'))->format('Y-m-d');
        $item->stuff_id = array_get($data,'stuff_id');
        $item->sum = (int) array_get($data,'sum');
        $item->user_id = array_get($data,'supplier_id');
        
        $item->stuff->total -= (int) array_get($data,'sum');
        if ($item->stuff->total < 0 ) {
            \Flash::error('total stuff less than 0');
            return \Redirect::refresh();
        }
        else {
            $item->save();
            $item->stuff->save();
        }
        \Flash::success('Supply stuff already created');
        return \Redirect::to('outcoming/stuff');
    }
}
