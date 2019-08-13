<?php


namespace Punksolid\Wialon\Notification;


use Punksolid\Wialon\Notification\ControlTypeInterface;

class SpeedControlType implements ControlTypeInterface
{
    /**
     * @var int
     */
    private $min_speed;
    /**
     * @var int
     */
    private $max_speed;

    /**
     * SpeedControlType constructor.
     *
     * @param int $min_speed
     * @param int $max_speed
     */
    public function __construct(int $min_speed, int $max_speed)
    {
        $this->min_speed = $min_speed;
        $this->max_speed = $max_speed;
    }

    public function getTrg(): string
    {
        return " \"trg\": {
                            \"t\": \"speed\",
                            \"p\": {
                                \"sensor_type\": \"\",
                                \"sensor_name_mask\": \"\",
                                \"lower_bound\": \"0\",
                                \"upper_bound\": \"0\",
                                \"merge\": \"0\",
                                \"min_speed\": \"{$this->min_speed}\",
                                \"max_speed\": \"{$this->max_speed}\"
                            }
                        },";


    }

    public function getArrayAttributes(): array
    {
        return [
            "t" => "speed",
            "p" => [
                "sensor_type" => "",
                "sensor_name_mask" => "",
                "lower_bound" => "0",
                "upper_bound" => "0",
                "merge" => "0",
                "min_speed" => $this->min_speed,
                "max_speed" => $this->max_speed,
            ],
        ];
    }
}