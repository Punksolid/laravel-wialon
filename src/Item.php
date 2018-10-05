<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 5/10/18
 * Time: 06:34 AM
 */

namespace Punksolid\Wialon;


class Item
{
    public function __construct($geofence)
    {
        if (!is_null($geofence)) {
            foreach ($geofence as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }
}