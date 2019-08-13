<?php
/**
 * Created by PhpStorm.
 * User: ze
 * Date: 1/21/19
 * Time: 12:14 AM
 */

namespace Punksolid\Wialon;


use http\Exception;
use Punksolid\Wialon\Notification\ControlTypeInterface;

/**
 * @deprecated
 * Class ControlType
 * @package Punksolid\Wialon
 */
class ControlType implements ControlTypeInterface
{
    public function __construct($type, $params = [])
    {

        $control_types_admited = [
            'speed',
            'panic_button',
//            'parameter_in_a_message',
//            'connection_loss',
//            'sms',
//            'address',
//            'fuel_filling',
//            'driver',
//            'passenger_alarm',
            'geofence',
//            'digital_input',
//            'sensor_value',
//            'idling',
//            'interposition_of_units',
//            'excess_of_messages',
//            'fuel_theft',
//            'passenger_activity',
//            'maintenance'
        ];
        if (!in_array($type, $control_types_admited)) {
            abort(500);
        }

        $this->type = $type;
        //@todo add validation of params
        switch ($this->type):
            case 'speed':
                $this->trg = " \"trg\": {
                            \"t\": \"speed\",
                            \"p\": {
                                \"sensor_type\": \"\",
                                \"sensor_name_mask\": \"\",
                                \"lower_bound\": \"0\",
                                \"upper_bound\": \"0\",
                                \"merge\": \"0\",
                                \"min_speed\": \"{$params['min_speed']}\",
                                \"max_speed\": \"{$params['max_speed']}\"
                            }
                        },";
                break;
            case 'geofence':
                if ($params instanceof Geofence){
                    $geofence = $params;
                    $this->trg = "  \"trg\": {
                            \"t\": \"geozone\",
                            \"p\": {
                                \"geozone_ids\": \"$geofence->id\",
                                \"type\": \"1\"
                            }
                        },";
                }else {
                    //@todo add params for geofence
                    throw new \Exception("Needs a geofence object");
                }

                break;
            case 'panic_button':
                $this->trg = "  \"trg\": {
                            \"t\": \"alarm\",
                            \"p\": {
                            }
                        },";
                break;
        endswitch;
    }

    public function getTrg() : string
    {
        return $this->trg;
    }


}