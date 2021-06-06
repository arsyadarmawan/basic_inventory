<?php namespace Inventory\Warehouse\Components;

use Cms\Classes\ComponentBase;
use Inventory\Warehouse\Exports\StuffLogExport;
use Inventory\Warehouse\Models\{Stuff, Warehouse, Log, Category};

class Inventory extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'inventory Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'stuffId' => [
                'title'             => 'Stuff ID',
                'type'              => 'integer',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Trip ID property can contain only numeric symbols'
            ]
        ];
    }

    public function init()
    {
        $stuff   = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'stuffFiles',
            [
                'fileTypes'             => ".jpg, .jpeg, .png, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx",
                'placeholderText'       => "Drag and drop file here or Browse File",
                'deferredBinding'       => true      
            ]
        );
        $stuff->bindModel('media', ($this->property('stuffId') ? Stuff::find((int)$this->property('stuffId')) : new Stuff));   
    }

    public function logs()
    {
        $user = \Auth::getUser();
        $data = Log::where('user_id',$user->id)->paginate(10);
        return $data;
    }

    public function onCheckoutStuff()
    {
        $user = \Auth::getUser();
        $item = Log::where('user_id',$user->id)->where('stuff_id',(int) $this->property('stuffId'))->orderBy('id','DESC')->first();
        $item->out_date = now()->format('Y-m-d');
        $item->save();

        $stuff = Stuff::find((int)$this->property('stuffId'));
        $warehouse = Warehouse::find($stuff->warehouse_id);
        $warehouse->capacity += 1;
        $warehouse->save();
        $stuff->warehouse_id = null;
        $stuff->save();

        \Flash::success('History successfuly updated');
        return \Redirect::to('stuff/detail/'.$stuff->id);
    }

    public function onExportLog()
    {   
        $user = \Auth::getUser();
        $items = Log::where('user_id',$user->id)->get();
        $filename = 'Export_Data_Stuffs';
        return (new StuffLogExport($user->id))->export($filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function getLogStuff()
    {
        $user = \Auth::getUser();
        $item = Log::where('user_id',$user->id)->where('stuff_id',$this->property('stuffId'))->orderBy('id','DESC')->first();
        return $item;
    }

    public function listOfStuff()
    {
        $user = \Auth::getUser();
        $list = Stuff::where('user_id',$user->id)->orderBy('id','DESC')->paginate(10);
        return $list;
    }

    public function listCountStuff()
    {
        $user = \Auth::getUser();
        $data = Stuff::where('user_id',$user->id)->orderBy('id','DESC')->count();
        return $data;
    }
    
    public function allWarehouse()
    {
        $data = Warehouse::where('is_active',true)->get();
        return $data;
    }

    public function onUpdateStuff()
    {
        $data = post();
        if (!$user = \Auth::getUser()) {
            throw new \ApplicationException("User not Found");
        }
        $stuff = Stuff::find((int) $this->property('stuffId'));
            // find log and update log
        $log = Log::where('stuff_id',$this->property('stuffId'))->where('user_id',$user->id)->first();
        if ($log && $log->out_date) {
            $log->out_date = now()->format('Y-m-d');
            $log->save();
        }

        // update stuff warehouse
        $stuff->warehouse_id = array_get($data,'warehouse_id');
        $stuff->save();


        // create new log
        $logs = array_merge($data,['user_id' => $user->id, 'stuff_id' => (int) $this->property('stuffId')]);
        $log = new Log;
        $log->fill($logs);
        $log->entry_date = now()->format('Y-m-d');
        $log->save();

        return \Redirect::to('stuff');

    }

    public function onCreateStuff()
    {
        $data = post();
        // dd($data);
        if (!$user = \Auth::getUser()) {
            throw new \ApplicationException("User not Found");
        }
        // validate
        $warehouse = Warehouse::find(array_get($data,'warehouse_id'));
        if ($warehouse->capacity <= 0 || !$warehouse->is_active) {
            \Flash::error('This warehouse doesnt have space anymore or doesnt exist');
            return \Redirect::to('stuff/create');
        }

        $stuff = new Stuff;
        $stuff->user_id = $user->id;
        $stuff->fill($data);
        $stuff->save(null, post('_session_key'));
        // if (array_get($data,'categories') != null) {
        //     foreach (array_get($data,'categories') as $item) {
        //         $category = Category::find((int) $item);
        //         $stuff->categories->attach($category);
        //     }
        //     $item->save();
        // }
        
        $logs = array_merge($data,['user_id' => $user->id, 'stuff_id' => $stuff->id]);
        $log = new Log;
        $log->fill($logs);
        $log->entry_date = now()->format('Y-m-d');
        $log->save();


        \Flash::success('Data has been created');
        return \Redirect::to('stuff');
    }   
}
