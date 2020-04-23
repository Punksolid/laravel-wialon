<?php

namespace Punksolid\Wialon;

/* Classes for working with Wialon RemoteApi using PHP
*
* License:
* The MIT License (MIT)
*
* Copyright:
* 2002-2015 Gurtam, http://gurtam.com
*/

use Dotenv\Dotenv;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;


/**
 * Class Wialon api wrapper
 * @package Punksolid\Wialon
 */
class Wialon
{
    /// PROPERTIES
    private $sid = null;
    private $token = null;
    private $base_api_url = '';
    private $default_params = array();
    public $user;

    public $response = "";
    /// METHODS

    /** constructor */
    function __construct($scheme = 'http', $host = 'hst-api.wialon.com', $port = '', $sid = '', $extra_params = array(), $token = null)
    {

        $this->token = config('services.wialon.token', $token);
        $this->sid = $sid;
        $this->default_params = array_replace(array(), (array)$extra_params);
        $this->base_api_url = sprintf('%s://%s%s/wialon/ajax.html?', $scheme, $host, mb_strlen($port) > 0 ? ':' . $port : '');
    }

    /** sid setter */
    function set_sid($sid)
    {
        $this->sid = $sid;
    }

    /** sid getter */
    function get_sid()
    {
        return $this->sid;
    }

    /** update extra parameters */
    public function update_extra_params($params)
    {
        $this->default_params = array_replace($this->default_params, $params);
    }

    /** RemoteAPI request performer
     * action - RemoteAPI command name
     * args - JSON string with request parameters
     */
    public function call($action, $args)
    {
        $url = $this->base_api_url;
        if (stripos($action, 'unit_group') === 0) {
            $svc = $action;
            $svc[mb_strlen('unit_group')] = '/';
        } else {
            $svc = preg_replace('\'_\'', '/', $action, 1);
        }
        $params = array(
            'svc' => $svc,
            'params' => $args,
            'sid' => $this->sid
        );
        $all_params = array_replace($this->default_params, $params);
        $str = '';
        foreach ($all_params as $k => $v) {
            if (mb_strlen($str) > 0)
                $str .= '&';
            $str .= $k . '=' . urlencode(is_object($v) || is_array($v) ? json_encode($v) : $v);
        }
        /* cUrl magic */
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $str
        );
        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        if ($result === FALSE)
            $result = '{"error":-1,"message":' . curl_error($ch) . '}';

        curl_close($ch);
        return $result;
    }

    /** Login with token
     * user - wialon username
     * password - password
     * return - server response
     */
    public function login($token)
    {
        $data = array(
            'token' => urlencode($token),
        );
        $result = $this->token_login(json_encode($data));
        $json_result = json_decode($result);

        if (isset($json_result->eid)) {
            $this->user = $json_result->user;
            $this->sid = $json_result->eid;
        }
        return $result;
    }

    /** Logout
     * return - server responce
     */
    public function logout()
    {
        $result = $this->core_logout();
        $json_result = json_decode($result);
        if ($json_result && $json_result->error == 0)
            $this->sid = '';
        return $result;
    }

    public function searchItems($properties = [])
    {
        $this->beforeCall();
        $items = $this->core_search_items(json_encode($properties) . "&sid=" . $this->sid);

        $this->afterCall();

        return json_decode($items);
    }

    /**
     * @return Collection
     * @deprecated
     */
    public function listNotifications(): Collection
    {
        $this->beforeCall();
        $properties = [
            'spec' => [
                'itemsType' => 'avl_resource',
                'propName' => 'notifications',
                'propValueMask' => '*',
                'sortType' => 'sys_name',
                'propType' => ''
            ],
            'force' => 1,
            'flags' => 5129,
            'from' => 0,
            'to' => 10
        ];
        $response = json_decode($this->core_search_items($properties));

        $resources = collect($response->items)->transform(function ($resource) {
            return $resource->zl = collect($resource->zl);
        });
        $notifications = collect();

        foreach ($resources as $resource) {
            $notifications = $notifications->push($resource);
        }

        return $notifications->flatten();


    }

    public function createGeofence($resource_id, $name, $latitude, $longitude, $radius, $type)
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
//            throw ValidationException::withMessages([
//                "inconsistency" => [
//                    "faltan mas datos"
//                ]
//            ]);
        }
        $this->beforeCall();

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

        $this->response = json_decode(
            $this->resource_update_zone(json_encode($params))
        );
        $this->afterCall();

        $geofence = new Geofence($this->response[1]);


        return $geofence;
    }

    /**
     * @param $name
     * @return Resource
     * @throws WialonErrorException
     * @deprecated use Resource::make instead
     */
    public function createResource($name)
    {
        $this->beforeCall();

        $params = array(
            "creatorId" => $this->user->id, //obligatorio
            "name" => $name,
            "dataFlags" => 0x1, //dataFlag geofences
            // "dataFlags"=>1048576, //dataFlag geofenceGroups
            // "dataFlags"=>4611686018427387903, //  set all possible flags to resource
            "skipCreatorCheck" => 1
        );
        $this->response = json_decode($this->core_create_resource($params));
        $this->afterCall();

        $resource = new Resource($this->response->item);
        return $resource;

    }

    public function checkEvents()
    {
        $this->beforeCall();

        $params = [
            "sid" => $this->sid
        ];
        $handle = curl_init();

        $defaults = array(
            CURLOPT_URL => 'https://hst-api.wialon.com/avl_evts',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
        );


        curl_setopt_array($handle, $defaults);
        $this->afterCall();
        $this->response = curl_exec($handle);
        return json_decode($this->response);
//        return $this->response;
    }

    /**
     * Read more from https://sdk.wialon.com/wiki/en/sidebar/remoteapi/apiref/requests/address
     * @param string $lon
     * @param string $lat
     * @return mixed
     * @throws WialonErrorException
     */
    public function getAddress($lon='',$lat='')
    {
        $this->beforeCall();
        $str = 'coords=[{"lon":'.$lon.',"lat":'.$lat.'}]&flags=1255211008&uid='.$this->user->id;

        $handle = curl_init();
        $defaults = array(
            CURLOPT_URL => "https://geocode-maps.wialon.com/hst-api.wialon.com/gis_geocode?".$str,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($handle, $defaults);
        $this->afterCall();
        $this->response = curl_exec($handle);
        return json_decode($this->response);
    }

    /**
     * @deprecated  use Unit::make instead
     * @param $name
     * @return Unit
     */
    public function createUnit($name): Unit
    {

        $unit = Unit::make($name);

        return $unit;
    }

    /**
     * @param Unit $unit
     * @return bool
     * @deprecated use Unit->destroy() instead
     */
    public function destroyUnit(Unit $unit): bool
    {

        return $unit->destroy();

    }

    public function beforeCall()
    {
        $this->login($this->token);
    }

    public function afterCall()
    {
        $this->logout();

        if (isset($this->response['error'])) {

            throw new WialonErrorException($this->response['error']);

        }


    }

    /** Unknonwn methods hadler */
    public function __call($name, $args)
    {

        if (count($args) === 0) {
            $response = $this->call($name, '{}');

        } else {
            $response = $this->call($name, $args[0]);
        }
        $decoded = json_decode($response);

        if (isset($decoded->error) &&
            $decoded->error != 0 &&
            $decoded->error != 1 &&
            $decoded->error != 7){ // When an element dont exists return 7
            throw new \Exception(WialonError::error($decoded->error));
        }

        return $response;


    }


}
