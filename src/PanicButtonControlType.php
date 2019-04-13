<?php


namespace Punksolid\Wialon;




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
}