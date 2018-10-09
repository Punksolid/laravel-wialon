<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 5/10/18
 * Time: 06:29 AM
 */

namespace Punksolid\Wialon;

use Illuminate\Validation\ValidationException;

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

    public static function make($resource_id, $name, $latitude, $longitude, $radius, $type):self
    {

        $validation = \Validator::make([
            "resource_id" => $resource_id,
            "name" => $name,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "radius" => $radius,
            "type" => $type
        ], [
            "resource_id" => "required|integer",
            "name" => "required",
            "latitude" => "required",
            "longitude" => "required",
            "radius" => "required",
            "type" => "required"
        ]);
        if ($validation->fails()) {
            throw ValidationException::withMessages([
                "inconsistency" => [
                    "faltan mas datos"
                ]
            ]);
        }
        $wialon_api = new Wialon();
        $wialon_api->beforeCall();

        $minimum_inputs = [
            "resource_id" => $resource_id,
            "name" => $name,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "type" => $type // 3 circle
        ];
        $points = [
            [
                "x" => $minimum_inputs["latitude"],
                "y" => $minimum_inputs["longitude"],
                "r" => $radius
            ]
        ];
        $params = [
            "n" => $minimum_inputs["name"],             //esto es para  nombre geofence  Region-Session-Ruta-Linea
            "d" => "test" . $minimum_inputs["name"],             //esto es para Descripcion de geofence
            "t" => $minimum_inputs["type"],             //esto es para type: 1 - line, 2 - polygon, 3 - circle
            "w" => 51,             //esto es para ancho de linea
            "f" => 0x20,             //esto es para flagss
            "tc" => 2568583984,             //esto es para color(ARGB)
            "c" => 16733440,                //esto es para text color
            "ts" => 12,                //esto es para font size
            "min" => 0,               //esto es para desde el zoom
            "max" => 18,               //esto es para hasta el zoom
            "libId" => "",             //esto es para id of icon library , 0 - id for default icon library
            "path" => "",              //esto es para short path to default icon
            "p" => $points,
            "itemId" => $minimum_inputs["resource_id"],             //esto es para resource id
            "id" => 0,                //esto es para  geofence a modificar 0 si es nuevo
            "callMode" => "create",              //esto es para action: create, update, delete, reset_image
        ];

        $response = json_decode(
            $wialon_api->resource_update_zone(json_encode($params))
        );
        $wialon_api->afterCall();
        try {
            if (!isset($response->error)) {
                return new self($response[1]);
            }
        }catch (\Exception $e) {
            dd($response);
        }


        dd($response);
    }

    public static function find($unit_id):?self
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $response = json_decode($api_wialon->core_search_item([
            'id' => $unit_id,
            'flags' => '1'
        ]));
        if (isset($response->error)){
            return null;
        }

        $unit = new static($response->item);

        $api_wialon->afterCall();

        return $unit;
    }


}