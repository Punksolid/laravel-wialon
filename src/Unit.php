<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/09/18
 * Time: 07:00 PM
 */

namespace Punksolid\Wialon;

use Illuminate\Support\Collection;

/**
 *
 * Class Unit
 * @package Punksolid\Wialon
 * @property int $id unit ID
 * @property int $mu  measure units: 0 - si, 1 - us, 2 - imperial, 3 - metric with gallons
 * @property string $nm name
 * @property int $cls superclass ID: "avl_unit"
 * @property int $uacl current user access level for unit
 *
 * @property double $pos->y LATITUDE
 * @property double $posi->x LONGITUDE
 * @property string $name Alias for $nm
 */
class Unit extends Item
{
    public $id = '';
    public $nm = '';
    public $mu = '';
    public $cls = '';
    public $uacl = '';
//    public $pos = [];


//    private $name = '';

    public function __construct($unit)
    {
        if (!is_null($unit)) {
            foreach ($unit as $property => $value) {
                $this->{$property} = $value;
            }
        }

        if (isset($this->pos)) {
            $this->lat = optional($this->pos)->y;
            $this->lon = optional($this->pos)->x;
        }
    }

    public static function findMany(array $ids):Collection
    {
        $units = collect();
        foreach ($ids as $id){
            $units->push(self::find($id));
        }

        return $units;
    }

    /**
     * Alias for nm
     * @return string
     */
    public function getName()
    {
        return $this->name = $this->nm;
    }

    /**
     * $itemId long unit ID
     * $id   long command ID
     * $callMode text   action: create, update, delete
     *
     * -----Parameters required only for create and update:------
     * $n   text command name
     * $c   text type (see below)
     * $l   text link type (see below)
     * $p   text parameters
     * $a   text access level: rights that user must have to execute current command (see Access flags: General and Access flags: Units and unit groups)
     */
    public static function make( $name)
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $response = json_decode($api_wialon->core_create_unit([
            'creatorId' => $api_wialon->user->id,
            'name' => $name,
            'hwTypeId' => 96266,
            'dataFlags' => 1
        ]));

        $unit = new static($response->item);

        $api_wialon->afterCall();

        return $unit;
    }

    public  function destroy():bool
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();
        $response = json_decode($api_wialon->item_delete_item([
            'itemId' => $this->id,
        ]));
        $verification_query = self::find($this->id);
        if (is_null($verification_query)){
            $destroyed = true;
        } else {
            $destroyed = false;
        }
        $api_wialon->afterCall();

        return $destroyed;
    }

    /**
     * List all UNITS
     * @return Collection
     * @throws WialonErrorException
     */
    public static function all(): Collection
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = [
            'spec' => [
                'itemsType' => 'avl_unit',
                'propName' => '',
                'propValueMask' => '*',
                'sortType' => 'sys_name',
                'propType' => ''
            ],
            'force' => 1,
            'flags' => 5129,
            'from' => 0,
            'to' => 0
        ];

        $response = json_decode($api_wialon->core_search_items($params));
        $units = collect($response->items)->transform(function ($unit) {
            return new static($unit);
        });

        $api_wialon->afterCall();
        return $units;
    }

}