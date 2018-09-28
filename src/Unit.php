<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/09/18
 * Time: 07:00 PM
 */

namespace Punksolid\Wialon;

/**
 *
 * Class Unit
 * @package Punksolid\Wialon
 *
 * @property int $mu  measure units: 0 - si, 1 - us, 2 - imperial, 3 - metric with gallons
 * @property string $nm name
 * @property int $cls superclass ID: "avl_unit"
 * @property int $id unit ID
 * @property int $uacl current user access level for unit
 */
class Unit
{
    public $mu = '';
    public $nm = '';
    public $cls = '';
    public $id = '';
    public $uacl = '';

    public function __construct($unit)
    {
        if (!is_null($unit)) {
            foreach ($unit as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }
}