<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 28/09/18
 * Time: 02:54 AM
 */

namespace Punksolid\Wialon;

use Illuminate\Support\Collection;
use Punksolid\Wialon\Geofence as Geofence;

/**
 * Class Notification
 * @package Punksolid\Wialon
 *
 *
 *
 *
 * @property long $id  notification ID
 * @property text $n  name
 * @property text $txt  text of notification
 * @property uint $ta  activation time (UNIX format)
 * @property uint $td  deactivation time (UNIX format)
 * @property uint $ma  maximal alarms count (0 - unlimited)
 * @property uint $mmtd  maximal time interval between messages (seconds)
 * @property uint $cdt  timeout of alarm (seconds)
 * @property uint $mast  minimal duration of alert state (seconds)
 * @property uint $mpst  minimal duration of previous state (seconds)
 * @property uint $cp  period of control relative to current time (seconds)
 * @property uint $fl  notification flags (see below)
 * @property uint $tz  timezone
 * @property text $la  user language (two-lettered code)
 * @property uint $ac  alarms count
 * @property long $un array units/unit groups ids
 */
class Notification
{


    public $id;    /* notification ID */
    public $n;    /* name */
    public $txt;    /* text of notification */
    public $ta;    /* activation time (UNIX format) */
    public $td;    /* deactivation time (UNIX format) */
    public $ma;    /* maximal alarms count (0 - unlimited) */
    public $mmtd;    /* maximal time interval between messages (seconds) */
    public $cdt;    /* timeout of alarm (seconds) */
    public $mast;    /* minimal duration of alert state (seconds) */
    public $mpst;    /* minimal duration of previous state (seconds) */
    public $cp;    /* period of control relative to current time (seconds) */
    public $fl;    /* notification flags (see below) */
    public $tz;    /* timezone */
    public $la;    /* user language (two-lettered code) */
    public $ac;    /* alarms count */
    public $un;

    public function __construct($notification)
    {
        if (!is_null($notification)) {
            foreach ($notification as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }

    /**
     * @param Resource $resource
     * @param \Punksolid\Wialon\Geofence $geofence
     * @param $units
     * @param bool $control_type //true = entries to geofence false, exits geofence
     * @param string $name
     * @return null|Notification
     * @throws \Exception
     */
    public static function make(Resource $resource, Geofence $geofence, Collection $units, bool $control_type = true, string $name): ?self
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $units_arr = $units->pluck("id")->first();

        $time = time() - (60 * 10);
        $params = "{
                \"ma\": 0,
                \"fl\": 1,
                \"tz\": 10800,
                \"la\": \"en\",
                \"act\": [{
                    \"t\": \"message\",
                    \"p\": {}
                }],
                \"sch\": {
                    \"f1\": 0,
                    \"f2\": 0,
                    \"t1\": 0,
                    \"t2\": 0,
                    \"m\": 0,
                    \"y\": 0,
                    \"w\": 0
                },
                \"txt\": \"Test Notification Text\",
                \"mmtd\": 3600,
                \"cdt\": 10,
                \"mast\": 0,
                \"mpst\": 0,
                \"cp\": 3600,
                \"n\": \"$name\",
                \"un\": [\"$units_arr\"],
                \"ta\": $time,
                \"td\": 0,
                \"trg\": {
                    \"t\": \"geozone\",
                    \"p\": {
                        \"geozone_ids\": \"$geofence->id\",
                        \"type\": \"1\"
                    }
                },
                \"itemId\": 18145865,
                \"id\": 0,
                \"callMode\": \"create\"
            }";

        $response = json_decode($api_wialon->resource_update_notification($params));

        $unit = new static($response[1]);

        $api_wialon->afterCall();

        return $unit;
    }

    public static function all(): Collection
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = [
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
        $response = json_decode($api_wialon->core_search_items($params));

        $notifications = collect();
        foreach ($response->items as $resource) {
            if (isset($resource->unf)) {
                foreach ($resource->unf as $notification ) {
                    $notifications->push(new static($notification));
                }
            }
        }

        $api_wialon->afterCall();
        return $notifications;
    }
}

/** PLAYGROUND */
$EE = [
    'ma' => 0,
    'fl' => 1,
    'tz' => 10800,
    'la' => 'en',
    'act' =>
        [
            0 =>
                [
                    't' => 'message',
                    'p' =>
                        [
                        ],
                ],
        ],
    'sch' =>
        [
            'f1' => 0,
            'f2' => 0,
            't1' => 0,
            't2' => 0,
            'm' => 0,
            'y' => 0,
            'w' => 0,
        ],
    'txt' => 'Test Notification Text',
    'mmtd' => 3600,
    'cdt' => 10,
    'mast' => 0,
    'mpst' => 0,
    'cp' => 3600,
    'n' => 'mi nueva notificacion',
    'un' =>
        [
            0 => '734455',
        ],
    'ta' => 1539975248,
    'td' => 1540580048,
    'trg' =>
        [
            't' => 'geozone',
            'p' =>
                [
                    'geozone_ids' => '1',
                    'type' => '1',
                ],
        ],
    'itemId' => 18145865,
    'id' => 0,
    'callMode' => 'create',
];