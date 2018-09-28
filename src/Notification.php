<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 28/09/18
 * Time: 02:54 AM
 */

namespace Punksolid\Wialon;

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
}


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