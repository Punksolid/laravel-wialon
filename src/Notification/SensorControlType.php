<?php

namespace Punksolid\Wialon\Notification;


class SensorControlType implements ControlTypeInterface
{
    public $sensor_type = ""; // when empty, means "any"
    public $sensor_name_mask = '*';
    public $lower_bound = -1; //value_from
    public $upper_bound = 1;   // value_to
    public $trigger_when = null; // in range, out of range
    public $prev_msg_diff = 0; //@Todo what does it means
    public $merge = 1; //@Todo what does it means
    public $type = 0; // "trigger when" for 0 = "in range" 1 = "out of range"


    /**
     * default representation as it is in web platform
     * {
     * "t": "sensor_value",
     * "p": {
     * "sensor_type": "",
     * "sensor_name_mask": "*",
     * "lower_bound": -1,
     * "upper_bound": 1,
     * "prev_msg_diff": 0,
     * "merge": 1,
     * "type": 0
     * }
     */
    public function __construct()
    {
    }

    public function getTrg(): string
    {
        $trg = json_encode(
            [
                "trg" => [
                    "t" => "sensor_value",
                    "p" => [
                        "sensor_type" => $this->sensor_type,
                        "sensor_name_mask" => $this->sensor_name_mask,
                        "lower_bound" => $this->lower_bound,
                        "upper_bound" => $this->upper_bound,
                        "prev_msg_diff" => $this->prev_msg_diff,
                        "merge" => $this->merge,
                        "type" => $this->type,
                    ],
                ],

            ]
        );
        $result = substr(
            $trg,
            1,
            -1
        ); // $trg returns a complete json document, so we need to remove the first and last brase to parse it in the notifications make

        return $result.',';
    }

    public function getArrayAttributes(): array
    {
        return [
            "t" => "sensor_value",
            "p" => [
                "sensor_type" => $this->sensor_type,
                "sensor_name_mask" => $this->sensor_name_mask,
                "lower_bound" => $this->lower_bound,
                "upper_bound" => $this->upper_bound,
                "prev_msg_diff" => $this->prev_msg_diff,
                "merge" => $this->merge,
                "type" => $this->type,
            ]
        ];
    }
}
