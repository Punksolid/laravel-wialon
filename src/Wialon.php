<?php namespace Punksolid\Wialon;

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


    /// METHODS

    /** constructor */
    function __construct($scheme = 'http', $host = 'hst-api.wialon.com', $port = '', $sid = '', $extra_params = array(), $token = null)
    {

//        $this->token = \Config::get("services",$token);
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
        $this->default_params = array_replace($this->default_params, $extra_params);
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

    /** Login
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
        $json_result = json_decode($result, true);
        if (isset($json_result['eid'])) {
            $this->sid = $json_result['eid'];
        }
        return $result;
    }

    /** Logout
     * return - server responce
     */
    public function logout()
    {
        $result = $this->core_logout();
        $json_result = json_decode($result, true);
        if ($json_result && $json_result['error'] == 0)
            $this->sid = '';
        return $result;
    }

    public function searchItems($properties = [])
    {
        $this->beforeCall();
        $items = $this->core_search_items(json_encode($properties) . "&sid=" . $this->sid);

        $this->afterCall();

//        return $items;
        return json_decode($items);
    }

    /**
     * Returns Collection of Units
     * @return Collection
     */
    public function listUnits(): Collection
    {
        $this->beforeCall();
        $properties = [
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
        $units = $this->core_search_items($properties);
        $response = json_decode($units);
        $units = collect($response->items)->transform(function ($unit) {
            return new Unit($unit);
        });
        return $units;

    }

    public function listNotifications():Collection
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
         $resources = collect($response->items)->transform(function($resource){
            return $resource->zl = collect($resource->zl);
        });
         $notifications = collect();

         foreach ($resources as $resource){
             $notifications = $notifications->push($resource);
         }

         return $notifications->flatten();

//        dd($resources->last()->zl->last());
//        return collect($notifications->items);
//            ->transform(function ($notification) {
//                return new Notification($notification);
//            });

    }

    public function beforeCall()
    {
        $this->login($this->token);
    }

    public function afterCall()
    {
        $this->logout();
    }

    /** Unknonwn methods hadler */
    public function __call($name, $args)
    {
        return $this->call($name, count($args) === 0 ? '{}' : $args[0]);
    }


}
