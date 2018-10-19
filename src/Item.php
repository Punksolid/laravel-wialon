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
    public function __construct($data)
    {
        if (!is_null($data)) {
            foreach ($data as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }
}