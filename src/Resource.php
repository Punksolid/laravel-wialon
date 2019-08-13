<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 5/10/18
 * Time: 04:45 AM
 */

namespace Punksolid\Wialon;

use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock\StandardTagFactory;

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
class Resource extends Item
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

    public static function make($name)
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = [
            "creatorId" => $api_wialon->user->id, //obligatorio
            "name" => $name,
            "dataFlags" => 0x1, //dataFlag geofences
            // "dataFlags"=>1048576, //dataFlag geofenceGroups
            // "dataFlags"=>4611686018427387903, //  set all possible flags to resource
            "skipCreatorCheck" => 1
        ];
        $response = json_decode($api_wialon->core_create_resource($params));
        $api_wialon->afterCall();
        $resource = new Resource($response->item);

        $api_wialon->afterCall();

        return $resource;

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
        if (isset($response->items[0])) {
            $unit = new static($response->items[0]);
        }

        $api_wialon->afterCall();

        if (isset($unit)) {

            return $unit;
        }
        return null;
    }

    /**
     * Destroy anything, maybe refactor to item
     * @return bool
     * @throws WialonErrorException
     */
    public function destroy(): bool
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = [
            "itemId" => $this->id,
        ];

        $response = json_decode($api_wialon->item_delete_item($params));

        $api_wialon->afterCall();
        return (bool)!empty($response);
    }

    /**
     * List all resources
     * @return Collection
     * @throws WialonErrorException
     */
    public static function all(): Collection
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = [
            'spec' =>
                [
                    0 =>
                        [
                            'type' => 'type',
                            'data' => 'avl_resource',
                            'flags' => 1,
                            'mode' => 0,
                        ],
                ],
        ];

        $response = json_decode($api_wialon->core_update_data_flags($params));

        $resources = collect();

        foreach ($response as $resource) {
            $resources->push(new static($resource->d));
        }

        $api_wialon->afterCall();
        return $resources;
    }

    /**
     * Example of usage Resource::firstOrCreate(['name' => 'my_resource'])
     *
     * @param array $attributes
     * @return Resource
     */
    public static function firstOrCreate(array $attributes = []): self
    {
        $resource = Resource::findByName($attributes['name']);
        if (!$resource) {
            return self::make($attributes['name']);
        }

        return $resource;

    }
}