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


//        $params = [
//            "id" => 0, /* notification ID 0 when creating */
//            "itemId" => $resource->id,
//            "ma" => 0, /* maximal alarms count (0 - unlimited) */
//            "fl" => 1, /* notification flags (see below) */
//            "tz" => 10800, /* timezone */
//            "la" => "es",
//            "act" => [ /* actions */
//                [
//                    "t" => "message",
//                    "p" => []
//                ]
//            ],
//            "sch" => [ /* time limitation */
//                "f1" => 0,
//                "f2" => 0,
//                "t1" => 0,
//                "t2" => 0,
//                "m" => 0,
//                "y" => 0,
//                "w" => 0
//            ],
//            "txt" => "Test Notification Texte",
//            "mmtd" => 3600, /* maximal time interval between messages (seconds) */
//            "cdt" => 10, /* timeout of alarm (seconds) */
//            "mast" => 0, /* minimal duration of alert state (seconds) */
//            "mpst" => 0,
//            "cp" => 3600, /* period of control relative to current time (seconds) */
//            "n" => $name,
//            "un" => ["$units_arr"],
//            "ta" =>  time() - (60 * 10), /* activation time (UNIX format) */
//            "td" =>  0, /* deactivation time (UNIX format) */
//            "trg" => [  /* control */
//                "t" => "geozone",
//                "p" => [
//                    "geozone_ids" => $geofence->id,
//                    "type" => "0"
//                ]
//            ],
//            "callMode" => "create"
//        ];

//        $params = [
//            'ma' => 0,
//            'fl' => 1,
//            'tz' => 10800,
//            'la' => 'en',
//            'act' =>
//                [
//                    0 =>
//                        [
//                            't' => 'message',
//                            'p' => '{}',
//                        ],
//                ],
//            'sch' =>
//                [
//                    'f1' => 0,
//                    'f2' => 0,
//                    't1' => 0,
//                    't2' => 0,
//                    'm' => 0,
//                    'y' => 0,
//                    'w' => 0,
//                ],
//            'txt' => 'Test Notification Text',
//            'mmtd' => 3600,
//            'cdt' => 10,
//            'mast' => 0,
//            'mpst' => 0,
//            'cp' => 3600,
//            'n' => 'mi nueva notificacion4',
//            'un' =>
//                [
//                    0 => '734455',
//                ],
//            'ta' => time() - (60 * 10),
//            'td' => 0,
//            'trg' =>
//                [
//                    't' => 'geozone',
//                    'p' =>
//                        [
//                            'geozone_ids' => '1',
//                            'type' => '1',
//                        ],
//                ],
//            'itemId' => 18145865,
//            'id' => 0,
//            'callMode' => 'create',
//        ];

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

//},
//"ctrl_sch":{    /* maximal alarms count intervals shedule */
//    "f1":<uint >,	/* beginning of interval 1 (minutes from midnight) */
//			"f2":<uint >,	/* beginning of interval 2 (minutes from midnight) */
//			"t1":<uint >,	/* ending of interval 1 (minutes from midnight) */
//			"t2":<uint >,	/* ending of interval 2 (minutes from midnight) */
//		 	"m":<uint >,	/* days of month mask [1: 2^0, 31: 2^30] */
//		 	"y":<uint >,	/* months mask [Jan: 2^0, Dec: 2^11] */
//		 	"w":<uint >	/* days of week mask [Mon: 2^0, Sun: 2^6] */
//		},
//		"un":[<long>],	/* array units/unit groups ID's */
//		"act":[			/* actions */
//			{
//                "t":<text >,		/* action type (see below) */
//				"p":{            /* parameters */
//                "blink": <text >,	/* mini-map blinking when triggered */
//					"color": <text >,	/* online notification color */
//					"url": <text >,		/* url of sound */
//					...
//				},
//				...
//			}
//		],
//		"trg":{            /* control */
//    "t":<text >,		/* control type (see below) */
//			"p":{            /* parameters */
//        <
//        text >:<text >,		/* parameter name: value */
//				...
//			}
//		},
//		"ct":<uint >,        /* creation time */
//		"mt":<uint >         /* last modification time */


//{
//    "id":<long>,	/* notification ID */
//		"n":<text>,	/* name */
//		"txt":<text>,	/* text of notification */
//		"ta":<uint>,	/* activation time (UNIX format) */
//		"td":<uint>,	/* deactivation time (UNIX format) */
//		"ma":<uint>,	/* maximal alarms count (0 - unlimited) */
//		"mmtd":<uint>,	/* maximal time interval between messages (seconds) */
//		"cdt":<uint>,	/* timeout of alarm (seconds) */
//		"mast":<uint>,	/* minimal duration of alert state (seconds) */
//		"mpst":<uint>,	/* minimal duration of previous state (seconds) */
//		"cp":<uint>,	/* period of control relative to current time (seconds) */
//		"fl":<uint>,	/* notification flags (see below) */
//		"tz":<uint>,	/* timezone */
//		"la":<text>,	/* user language (two-lettered code) */
//		"ac":<uint>,	/* alarms count */
//		"sch":{		/* time limitation */
//    "f1":<uint>,	/* beginning of interval 1 (minutes from midnight) */
//			"f2":<uint>,	/* beginning of interval 2 (minutes from midnight) */
//			"t1":<uint>,	/* ending of interval 1 (minutes from midnight) */
//			"t2":<uint>,	/* ending of interval 2 (minutes from midnight) */
//			"m":<uint>,	/* days of month mask [1: 2^0, 31: 2^30] */
//			"y":<uint>,	/* months mask [Jan: 2^0, Dec: 2^11] */
//			"w":<uint>	/* days of week mask [Mon: 2^0, Sun: 2^6] */
//		},
//		"ctrl_sch":{	/* maximal alarms count intervals shedule */
//    "f1":<uint>,	/* beginning of interval 1 (minutes from midnight) */
//			"f2":<uint>,	/* beginning of interval 2 (minutes from midnight) */
//			"t1":<uint>,	/* ending of interval 1 (minutes from midnight) */
//			"t2":<uint>,	/* ending of interval 2 (minutes from midnight) */
//		 	"m":<uint>,	/* days of month mask [1: 2^0, 31: 2^30] */
//		 	"y":<uint>,	/* months mask [Jan: 2^0, Dec: 2^11] */
//		 	"w":<uint>	/* days of week mask [Mon: 2^0, Sun: 2^6] */
//		},
//		"un":[<long>],	/* array units/unit groups ID's */
//		"act":[			/* actions */
//			{
//                "t":<text>,		/* action type (see below) */
//				"p":{			/* parameters */
//                "blink": <text>,	/* mini-map blinking when triggered */
//					"color": <text>,	/* online notification color */
//					"url": <text>,		/* url of sound */
//					...
//				},
//				...
//			}
//		],
//		"trg":{			/* control */
//    "t":<text>,		/* control type (see below) */
//			"p":{			/* parameters */
//        <text>:<text>,		/* parameter name: value */
//				...
//			}
//		},
//		"ct":<uint>,        /* creation time */
//		"mt":<uint>         /* last modification time */