<?php namespace Inventory\Warehouse\Models;

use Model;
use Carbon\Carbon;
use Inventory\Warehouse\Models\Log;
/**
 * stuff Model
 */
class Stuff extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'inventory_warehouse_stuffs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'warehouse_id',
        'user_id'
    ];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'warehouse' => \Inventory\Warehouse\Models\Warehouse::class,
        'user'      => \Rainlab\User\Models\User::class,
    ];
    public $belongsToMany = [
        'categories' => [
            'Inventory\Warehouse\Models\Category',
            'table'     => 'inventory_warehouse_category_stuffs',
            'key'       => 'stuff_id',
            'otherKey'  => 'category_id',
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'media'  => 'System\Models\File'
    ];
    public $attachMany = [];

    protected function generateStuffCode($prefix = "BRG")
    {
        
        $stringify = [
            'prefix'      => $prefix,
            'warehouse_id'=> $this->warehouse ? $this->integerToRoman($this->warehouse->id) : 'XXX',
            'dd'          => Carbon::parse(now())->format('d'),
            'mm'          => Carbon::parse(now())->format('m'),
            'count'       => $this->integerToRoman($this->count() + 1)
        ];

        $this->code = strtoupper(implode('/', $stringify));
    }

    protected function integerToRoman($integer)
    {
        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';
        
        // Create a lookup array that contains all of the Roman numerals.
        $lookup = array(
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1);
        
        foreach($lookup as $roman => $value){
            $matches = intval($integer/$value);
            $result .= str_repeat($roman,$matches);
            $integer = $integer % $value;
        }
        
        return $result;
    }
    protected function beforeCreate()
    {
        $this->generateStuffCode();
        if ($this->warehouse) {
            // remove capacity
            $this->warehouse->capacity -= 1;
            $this->warehouse->save();
        }
    }
}
