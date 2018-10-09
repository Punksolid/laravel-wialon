<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 5/10/18
 * Time: 04:45 AM
 */

namespace Punksolid\Wialon;

/**
 * Class Resource
 * @package Punksolid\Wialon
 *
 * @property text $nm  name
 * @property uint $cls  superclass ID: "avl_resource"
 * @property uint $id  resource ID
 * @property uint $mu     measure units: 0 - si, 1 - us, 2 - imperial, 3 - metric with gallons //maybe 'si' stands for International System of Units
 * @property uint $uacl  current user access level for resource
 */
class Resource
{

    public $nm = "";
    public $cls = "";
    public $id = "";
    public $mu = 0;
    public $uacl = "";

    public function __construct($resource)
    {
        if (!is_null($resource)) {
            foreach ($resource as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }

    public static function findByName($name): ?self
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = array(
            'spec' => array(
                'itemsType' => 'avl_resource',
                'propName' => 'sys_name',
                'propValueMask' => $name,
                'sortType' => 'sys_name',
                'propType' => 'property'
            ),
            'force' => 1,
            'flags' => '5129',
            'from' => 0,
            'to' => 0);

        $response = json_decode($api_wialon->core_search_items($params));

        if (isset($response->error)) {
            return null;
        }
        if (isset($response->items[0])){
            $unit = new static($response->items[0]);
        }

        $api_wialon->afterCall();

        if (isset($unit)){

            return $unit;
        }
        return null;
    }
}