<?php


namespace Punksolid\Wialon;


class GeofenceControlType implements ControlTypeInterface
{
    public $geofences;
    public $geozones_ids;

    public function __construct(Geofence $geofence = null)
    {
        $this->geofences = collect();

        if ($geofence) {
            $this->addGeozoneId("{$geofence->rid}_{$geofence->id}");
        }
    }

    /**
     * Expects the geozone id in the underscore format resourceid_localid
     *
     * @param $geozone_id
     * @return \Illuminate\Support\Collection
     */
    public function addGeozoneId($geozone_id)
    {
        return $this->geofences->push(['id' => $geozone_id]);
    }

    public function getGeozonesIds()
    {
        return $this->geofences->pluck('id')->implode(',');
    }

    public function getTrg(): string
    {
        return '
        "trg": {
                "t": "geozone",
                "p": {
                    "sensor_type": "",
                    "sensor_name_mask": "",
                    "lower_bound": 0,
                    "upper_bound": 0,
                    "merge": 0,
                    "geozone_ids": "'.$this->getGeozonesIds().'",
                    "geozone_id": "'.$this->geofences->first()['id'].'",
                    "type": 0,
                    "min_speed": 0,
                    "max_speed": 0,
                    "lo": "OR"
                }
	    },';
    }
}