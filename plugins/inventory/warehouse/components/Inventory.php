<?php namespace Inventory\Warehouse\Components;

use Cms\Classes\ComponentBase;
use Inventory\Warehouse\Exports\StuffLogExport;
use Inventory\Warehouse\Models\{Stuff, Warehouse, Log, Category, Contact, Denom};

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

    public function onCreateReport()
    {
        $data = post();
        $report = new Contact;
        $report->fillable($data);
        \Flash::success('Report has been created');
        return \Redirect::refresh();
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
        $list = Stuff::orderBy('id','DESC')->paginate(10);
        return $list;
    }

    public function listCountStuff()
    {
        $user = \Auth::getUser();
        $data = Stuff::all()->count();
        return $data;
    }
    
    public function allWarehouse()
    {
        $data = Warehouse::where('is_active',true)->get();
        return $data;
    }

    public function onCreateDenom()
    {
        $data = post();
        $item = new Denom;
        $item->name        = array_get($data,'name');
        $item->description = array_get($data,'description');

        $item->save();
        \Flash::success('Denom already saved');
        return \Redirect::to('denoms');
    }

    public function onDeleteStuff()
    {
        $data = post();
        $item = Stuff::find((int)array_get($data,'stuff_id'));
        $item->delete();
        \Flash::error('Stuff Deleted');
        return \Redirect::to('stuff');
    }

    public function onDeleteDenom()
    {
        $data = post();
        $denom = Denom::find((int) array_get($data,'denom_id'));
        $denom->delete();

        \Flash::error('Category Deleted');
        return \Redirect::refresh();
    }


    public function onDeleteCategory()
    {
        $data = post();
        $category = Category::find((int) array_get($data,'category_id'));
        $category->delete();

        \Flash::error('Denom Deleted');
        return \Redirect::refresh();
    }

    public function onCreateCategory()
    {
        $data = post();
        $item = new Category;
        $item->name        = array_get($data,'name');
        $item->description = array_get($data,'description');

        $item->save();
        \Flash::success('category already saved');
        return \Redirect::to('categories');
    }

    public function onUpdateStuff()
    {
        $data = post();
        if (!$user = \Auth::getUser()) {
            throw new \ApplicationException("User not Found");
        }
        $stuff = Stuff::find((int) $this->property('stuffId'));
        if ($stuff->warehouse) {
            if ($stuff->warehouse->id != null) {
                    $stuff->warehouse->capacity += 1;
                    $stuff->warehouse->save();
            }
        }
        $stuff->fillable($data);
        $stuff->denom_id = array_get($data,'denom_id');
        $stuff->characteristic = array_get($data,'characteristic');
        $stuff->warehouse_id =  array_get($data,'warehouse_id');
        $stuff->category_id = array_get($data,'category_id');
        $stuff->save();
        
        \Flash::success('Stuff already updated');
        return \Redirect::to('stuff');

    }

    public function onCreateStuff()
    {
        $data = post();
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
        $stuff->fill($data);
        $stuff->save(null, post('_session_key'));

        $logs = array_merge($data,['user_id' => $user->id, 'stuff_id' => $stuff->id]);
        $log = new Log;
        $log->fill($logs);
        $log->entry_date = now()->format('Y-m-d');
        $log->save();


        \Flash::success('Data has been created');
        return \Redirect::to('stuff');
    }   
}
