<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/09/18
 * Time: 04:16 PM
 */

namespace Punksolid\Wialon;

//{#14

//  +"prp": {#15
//    +"access_templates": "{"avl_unit":[],"avl_unit_group":[],"avl_resource":[],"avl_route":[],"user":[]}"
//    +"autocomplete": "{}"
//    +"cfmt": "1"
//    +"drvsvlist": "{"m":1,"e":{"17471233_1":1},"go":{}}"
//    +"dst": "-1"
//    +"fpnl": "monitoring"
//    +"geodata_source": "map_webgis"
//    +"hbacit": "{"hb_mi_apps":{},"hb_mi_tools":{},"hb_mi_search":{},"hb_mi_monitoring":{"l":1},"hb_mi_routes":{"l":1},"hb_mi_messages":{"l":1},"hb_mi_reports_ctl":{"l":1},"hb_mi_geozones":{"l":1},"hb_mi_drivers":{"l":1},"hb_mi_tags":{"l":1},"hb_mi_jobs":{},"hb_mi_notifications":{},"hb_mi_users":{},"hb_mi_devices":{}}"
//    +"hpnl": "hb_mi_monitoring"
//    +"inf_map": "1"
//    +"language": "es"
//    +"m_monu": "[17471245,17471271,17471332,17471392,17471421]"
//    +"minimap_zoom_level": "15"
//    +"mongr": "{}"
//    +"mont": "1"
//    +"monu": "["17471245","17471271","17471332","17471392","17471421"]"
//    +"monuei": "{}"
//    +"monuv": "["17471245","17471271"]"
//    +"mtg": "1"
//    +"mu_battery": "1"
//    +"mu_fast_report": "2"
//    +"mu_fast_report_tmpl": "0"
//    +"mu_fast_track_ival": "0"
//    +"mu_gprs_durr": "600"
//    +"mu_loc_mode": "0"
//    +"mu_location": "1"
//    +"mu_tbl_cols_sizes": "ldt:256,0.22,0.26,0.26,0.26;ld:192,0.28,0.36,0.36;lt:192,0.28,0.36,0.36;dt:192,0.28,0.36,0.36;l:586,0.18354,0.81646;t:128,0.45,0.55;d:128,0.45,0.55;f:426,1"
//    +"mu_tbl_sort": "1monitoring_units_battery"
//    +"muow": "["17471271"]"
//    +"notify_block_account": "0"
//    +"radd": "{"w":"6","c":0,"u":17471271,"a":1,"td":1,"s":"default","f":230}"
//    +"show_log": "1"
//    +"tz": "-100164208"
//    +"umap": "Google Streets"
//    +"ursstp": "0x1dfff"
//    +"us_addr_fmt": "1255211008_10_5"
//    +"us_addr_ordr": "1255211008"
//    +"uschs": "1"
//    +"usdrva": "1"
//    +"used_hw": "{"14342729":1}"
//    +"user_settings_hotkeys": "1"
//    +"user_unit_cmds": "{"0":{"n":"Básicas","items":[],"data":{}}}"
//    +"usuei": "0x1dfff"
//    +"vsplit": "590"
//    +"znsn": "1"
//    +"znsrv": "1"
//    +"znsvlist": "{"m":1,"e":{"17471233_1":3,"17471233_2":1},"go":{}}"
//  }

//  +"ftp": {#16
//    +"ch": 0
//    +"tp": 0
//    +"fl": 1
//  }

//}

/**
 * Class User
 * @package Punksolid\Wialon
 * @property int $id ID
 * @property string $nm NAME
 * @property string $cls ID of sperclass "user"
 * @property $fl
 * @property $hm
 * @property $uacl
 * @property $crt
 * @property $bact
 * @property $mu
 * @property $ct
 * @property $ld
 * @property $pfl
 * @property $ap
 * @property $mapps
 * @property $mappsmax
 *
 */
class User
{

    public $nm = '';
    public $cls = ''; //* ID of superclass "user" */
    public $id = ''; //id

    public $fl = '';/* user flags */
    public $hm = '';/* host mask */
    public $uacl = ''; /* user access to himself */
    public $crt = ''; /* creator ID */
    public $bact = ''; /* account ID */

    public $mu = '';
    public $ct = '';
    public $ld = '';
    public $pfl = '';
    public $ap = '';
    public $mapps = '';
    public $mappsmax = '';

    public function __construct($user = null)
    {
        if (!is_null($user)) {
            foreach ($user as $property => $value) {
                $this->{$property} = $value;
            }
        }

    }
}