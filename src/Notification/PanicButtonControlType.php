<?php


namespace Punksolid\Wialon\Notification;



class PanicButtonControlType implements ControlTypeInterface
{

    /**
     * PanicButtonControlType constructor.
     */
    public function __construct()
    {
    }

    public function getTrg(): string
    {
        return <<<TAG
                        "trg": {
                            "t": "alarm",
                            "p": {
                            }
                        },
TAG;
    }

    public function getArrayAttributes(): array
    {
        return [
          "t" => "alarm",
          "p" => function(){}
        ];
    }
}