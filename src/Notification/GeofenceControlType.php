<?php


namespace Punksolid\Wialon\Notification;


use Illuminate\Support\Collection;
use Punksolid\Wialon\Geofence;
use Punksolid\Wialon\Notification\ControlTypeInterface;

class GeofenceControlType implements ControlTypeInterface
{
    public $geofences;
    public $geozones_ids;

    public $type = 0; // enter, 1 = leaving

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
     * @return Collection
     */
    public function addGeozoneId($geozone_id)
    {
        return $this->geofences->push(['id' => $geozone_id]);
    }

    public function setType(int $type): void
    {
        $this->type = $type;
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
                    "type": '.$this->type.',
                    "min_speed": 0,
                    "max_speed": 0,
                    "lo": "OR"
                }
	    },';
    }

    public function getGeozonesIds():string
    {
        return $this->geofences->pluck('id')->implode(',');
    }

    public function getArrayAttributes(): array
    {
        // TODO: Implement getArrayAttributes() method.
        return [
            "t" => "geozone",
            "p" => [
                "sensor_type" => "",
                "sensor_name_mask" => "",
                "lower_bound" => 0,
                "upper_bound" => 0,
                "merge" => 0,
                "geozone_ids" => $this->getGeozonesIds(),
                "geozone_id" => $this->geofences->first()['id'],
                "type" => $this->type,
                "min_speed" => 0,
                "max_speed" => 0,
                "lo" => "OR",
            ],
        ];
    }
}