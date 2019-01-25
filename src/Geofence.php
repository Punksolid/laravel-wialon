<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 5/10/18
 * Time: 06:29 AM
 */

namespace Punksolid\Wialon;

use Illuminate\Validation\ValidationException;
use Punksolid\Wialon\WialonErrorException;

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

    public static function make($resource_id, $name, $latitude, $longitude, $radius, $type): ?self
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
                    "Data needed"
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

        $params = '{"n":"'.$name.'",
        "d":'.$resource_id.',
        "t":3,
        "f":0,
        "w":500,
        "c":2566914041,
        "p":[{
            "x":'.$minimum_inputs["latitude"].',
            "y":'.$minimum_inputs["longitude"].',
            "r":'.$radius.
        '}],
        "itemId":'.$minimum_inputs["resource_id"].',
        "id":0,
        "callMode":"create"}';
        $response = json_decode(
            $wialon_api->resource_update_zone($params)
        );
        $wialon_api->afterCall();
        try {
            if (!isset($response->error)) {
                return new self($response[1]);
            }
        } catch (\Exception $e) {
            \Log::info("Geofence failed");
            \Log::info(WialonError::error($response->error));

        }


    }

    /**
     * Gives Geofence by exact name
     * @param $name
     * @return null|Geofence
     * @throws \Punksolid\Wialon\WialonErrorException
     */
    public static function findByName($name): ?self
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = array(
            'spec' => array(
                'itemsType' => 'avl_resource',
                'propName' => 'zones_library',
                'propValueMask' => $name,
                'sortType' => 'zones_library',
                'propType' => 'propitemname'
            ),
            'force' => 1,
            'flags' => '4097',
            'from' => 0,
            'to' => 0);

        $response = json_decode($api_wialon->core_search_items($params));
        if (isset($response->error)) {
            throw new WialonErrorException($response->error);
        }


        if (isset($response->items[0])) {
            $resource = new Resource($response->items[0]);
        }
        $api_wialon->afterCall();

        if (isset($resource)) {
            $geofences = collect($resource->zl);

            return new static($geofences->whereIn("n", $name)->first());
        }
        return null;
    }

    public static function findById(int $geofence_id, $resource_id):?parent
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();
//@todo could not use the find method because it is incompatible with Ite::find method,
//       maybe an implements its better approach
        
//        $response = json_decode($api_wialon->core_search_item([
//            'id' => $geofence_id,
//            'flags' => '0x1C'
//       ]));
        $response = json_decode($api_wialon->resource_get_zone_data([
            'itemId' => $resource_id,
            'col' => [$geofence_id],
            'flags' => '0x10'
        ]));

        if (isset($response->error)){
            return null;
        }

        $unit = new static($response[0]);

        $api_wialon->afterCall();

        return $unit;
    }

}