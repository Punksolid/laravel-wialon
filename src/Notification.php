<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 28/09/18
 * Time: 02:54 AM
 */

namespace Punksolid\Wialon;

use Illuminate\Support\Collection;
use Punksolid\Wialon\Notification\Action;

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
 *
 * NON WIALON ALIASES
 * @property text $name NAME
 * @property text $control_type
 * @property array $actions
 * @property text $text
 * @property object $resource
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
    public $un;  /* array_ids of units */

    //internal
    public $units;

    private $control_type_list = [
        'geozone' => GeofenceControlType::class,
        ];

    public function __construct($notification = null)
    {
        if (!is_null($notification)) {
            foreach ($notification as $property => $value) {
                $this->{$property} = $value;
            }
            $this->name = $this->n;

        }
    }

    /**
     * @param Resource $resource resource
     * @param \Punksolid\Wialon\Geofence $geofence
     * @param $units
     * @param array $control_type //true = entries to geofence false, exits geofence
     * @param string $name
     * @return null|Notification
     * @throws \Exception
     */
    public static function make(Resource $resource,  Collection $units, ControlTypeInterface $control_type, string $name = '', Action $action = null, $params = null): ?self
    {

        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = (new Notification)->constructParamsForCreateOrUpdate($resource, $units, $control_type, $name, $action, $params);

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
            'to' => 0 //this is for pagination, in 0 get all elements
        ];
        $response = json_decode($api_wialon->core_search_items($params));

        $notifications = collect();
        foreach ($response->items as $resource) {
            if (isset($resource->unf)) {
                $resource_basic_data = $resource;
                foreach ($resource->unf as $notification ) {
                    // Name attributes normalization
                    $notification->name = $notification->nm = $notification->n;
                    $notification->control_type  = $notification->trg;
                    $notification->actions = $notification->act;
                    $notification->text = $notification->txt;
                    $notification->resource = $resource_basic_data;
                    unset($notification->resource->unf);
                    unset($notification->resource->zl);
                    $notifications->push(new static($notification));
                }
            }
        }

        $api_wialon->afterCall();
        return $notifications;
    }

    /**
     * @param Resource $resource
     * @param Collection $units
     * @param ControlTypeInterface $control_type
     * @param string $name
     * @param Action $action
     * @param $params
     * @return string
     */
    public function constructParamsForCreateOrUpdate(Resource $resource = null, Collection $units = null, ControlTypeInterface $control_type = null, string $name = null, Action $action = null, $params = null): string
    {
        $static = $update_flag = !(isset($this) && get_class($this) == __CLASS__);
        if ($update_flag){ // updating // TODO Not Working
            dd('aaaaaaa');
            $units_arr = $this->getUnits()->pluck('id')->first(); // @todo verify if its working with more than one

            $time = time() - (60 * 10);

            $trg = $control_type->getTrg();
            if (!is_null($action)) {
                $act = $action->getAct();
            } else {
                $act = '"act": [{
                    "t": "message",
                    "p": {}
                }],';
            }

            $message = isset($params["txt"]) ? $params["txt"] : '"Default Message name=%NOTIFICATION%"';

            $id = isset($this->id) ? $this->id : 0;

        } else { // Creating
            $units_arr = $units->pluck("id")->first(); // @todo verify if its working with more than one

            $time = time() - (60 * 10);

            $trg = $control_type->getTrg();
            if (!is_null($action)) {
                $act = $action->getAct();
            } else {
                $act = '"act": [{
                    "t": "message",
                    "p": {}
                }],';
            }

            $message = isset($params["txt"]) ? $params["txt"] : '"Default Message name=%NOTIFICATION%"';

            $id = isset($this->id) ? $this->id : 0;

        }


        /** @var Object $resource */
        $params = '{
                "ma": 0,
                "fl": 1,
                "tz": 10800,
                "la": "en",
                ' . $act . '
                "sch": {
                    "f1": 0,
                    "f2": 0,
                    "t1": 0,
                    "t2": 0,
                    "m": 0,
                    "y": 0,
                    "w": 0
                },
                "txt": ' . $message . ',
                "mmtd": 3600,
                "cdt": 10,
                "mast": 0,
                "mpst": 0,
                "cp": 3600,
                "n": "' . $name . '",
                "un": ["' . $units_arr . '"],
                "ta": ' . $time . ',
                "td": 0,
                ' . $trg . '
                "itemId": ' . $resource->id . ',
                "id": ' . $id . ',
                "callMode": "create"
            }';
        return $params;
    }

    public  function destroy():bool
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $response = json_decode($api_wialon->resource_update_notification([
            'itemId' => $this->resource->id,
            'id' => $this->id,
            'callMode' => 'delete'
        ]));

        $api_wialon->afterCall();
        if (isset($response[0]) && $response[0] == $this->id){
            return true;
        }

        return false;
    }

    /**
     * @param $unique_id Unique id is in format resourceId_localID
     * @return Notification|null
     * @throws WialonErrorException
     */
    public static function findByUniqueId($unique_id)
    {
        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $explode = explode('_', $unique_id);
        $resource_id = $explode[0];
        $notification_id = $explode[1];
        $response = json_decode($api_wialon->resource_get_notification_data([
            'itemId' => $resource_id,
            'col' => [$notification_id],
            'flags' => '0x0' //0x0 , 0x1, 0x2
        ]));


        if (isset($response->error)){
            return null;
        }

        $unit = new static($response[0]);

        $api_wialon->afterCall();

        return $unit;
    }

    public function update()
    {

        $api_wialon = new Wialon();
        $api_wialon->beforeCall();

        $params = self::constructParamsForCreateOrUpdate();

        $response = json_decode($api_wialon->resource_update_notification(json_encode($this)));

        $notification = new static($response[1]);

        $api_wialon->afterCall();

        return $notification;
    }

    /**
     * Get the related units objects based on the array of units ids
     * @return Collection
     */
    public function getUnits(): ?Collection
    {
        if (is_array($this->un)){
            $this->units = Unit::findMany($this->un);
        }

        return $this->units;
    }

    /**
     * Gets PHP ControlType Object
     * @return ControlTypeInterface
     */
    public function getControlType(): ControlTypeInterface
    {
        return new $this->control_type_list[$this->trg]();
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public function updateText($text)
    {

    }
}
