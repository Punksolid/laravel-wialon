<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 5/10/18
 * Time: 06:29 AM
 */

namespace Punksolid\Wialon;

/**
 * Class Geofence
 * @package Punksolid\Wialon
 *
 * @property text $n     name
 * @property text $d     description
 * @property long $id     ID
 * @property uint $f     flags
 * @property int $t     type: 1 - line, 2 - polygon, 3 - circle
 * @property ushort $e     check sum (CRC16)
 * @property object $b configuration for rendering geofences
 *
 */
class Geofence extends Item
{

    public $n;
    public $d;
    public $id;
    public $f;
    public $t;
    public $e;
    public $b;


}