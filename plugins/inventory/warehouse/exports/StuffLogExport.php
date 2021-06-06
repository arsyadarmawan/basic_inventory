<?php namespace Inventory\Warehouse\Exports;

use Auth;
use Drupadi\Corporate\Models\Booking;
use Inventory\Warehouse\Models\Log;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

use Rainlab\User\Models\User;
use Carbon\Carbon;

class StuffLogExport implements FromCollection, WithHeadings
{
    protected $userId;
    protected $data = [];

    public function __construct($id) {
        $this->userId = $id;
    }

    public function headings(): array
    {

        return [
            'ID',
            'Stuff Name',
            'Stuff Code',
			'Warehouse Name',
            'Entry Date',
            'Out Date',
        ];
    }

    public function collection()
    {
        $logs = Log::where('user_id',$this->useId)->get();
        $logs->each(function($item){
            $this->data[] = [
                $item->id,
                $item->stuff->name,
                $item->stuff->code,
                $item->warehouse->name,
                $item->entry_date,
                $item->out_date
            ];
        });

        return collect($this->data);
    }

}
