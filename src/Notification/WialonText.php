<?php

namespace Punksolid\Wialon\Notification;

class WialonText
{
    /**
     * @var array
     */
    private $parameters = [
        'unit' => '%UNIT%',
        'timestamp' => '%CURR_TIME%',
        'location' => '%LOCATION%',
        'last_location' => '%LAST_LOCATION%',
        'locator_link' => '%LOCATOR_LINK(60,T)%',
        'smallest_geofence_inside' => '%ZONE_MIN%',
        'all_geofences_inside' => '%ZONES_ALL%',
        'UNIT_GROUP' => '%UNIT_GROUP%',
        'SPEED' => '%SPEED%',
        'POS_TIME' => '%POS_TIME%',
        'MSG_TIME' => '%MSG_TIME%',
        'DRIVER' => '%DRIVER%',
        'DRIVER_PHONE' => '%DRIVER_PHONE%',
        'TRAILER' => '%TRAILER%',
        'SENSOR' => '%SENSOR(*)%',
        'ENGINE_HOURS' => '%ENGINE_HOURS%',
        'MILEAGE' => '%MILEAGE%',
        'LAT' => '%LAT%',
        'LON' => '%LON%',
        'LATD' => '%LATD%',
        'LOND' => '%LOND%',
        'GOOGLE_LINK' => '%GOOGLE_LINK%',
        'CUSTOM_FIELD' => '%CUSTOM_FIELD(*)%',
        'UNIT_ID' => '%UNIT_ID%',
        'MSG_TIME_INT' => '%MSG_TIME_INT%',
        'NOTIFICATION' => '%NOTIFICATION%',
    ];

    public function __construct($personalized_params = null, $include_defaults = true)
    {
        if (!$include_defaults) {
            $this->parameters = [];
        }
        if ($personalized_params) {
            if (is_array($personalized_params)){
                $this->parameters = array_merge($this->parameters, $personalized_params);
            }
            if (is_string($personalized_params)) {
                $this->parameters = array_merge($this->parameters,['' => $personalized_params]);
            }
        }
    }

    /**
     * Agrega al query elementos.
     *
     * @param $key
     * @param $value
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Retorna el Query elaborado.
     *
     * @param string $string
     * @return string
     */
    public function getText(string $string = null): string
    {
        if ($string){
            return $string;
        }

        return urldecode(http_build_query($this->parameters));
    }
}
