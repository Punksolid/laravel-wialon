<?php
/**
 * Created by PhpStorm.
 * User: ze
 * Date: 1/23/19
 * Time: 4:35 PM
 */

namespace Punksolid\Wialon\Notification;


class Action
{
    /**
     * @var array
     */
    private $params;
    /**
     * @var string
     */
    private $url;

    public function __construct($type = 'push_messages', $url = "https://localhost")
    {

        // #ref https://sdk.wialon.com/wiki/en/kit/remoteapi/apiref/resource/get_notification_data#action_types
        $actions_admitted = [
//            'email',
//            'sms',
//            'mseesage',
//            'mobile_apps',
            'push_messages',
//            'event',
//            'exec_cmd',
//            'user_access',
//            'counter',
//            'store_counter',
//            'status',
//            'group_manupulation',
//            'email_report',
//            'route_control',
//            'drivers_reset',
//            'trailers_reset'
        ];
        if (!in_array($type, $actions_admitted)) {
            abort(500);
        }

        $this->type = $type;
        //@todo add validation of params
        switch ($this->type):
            case 'push_messages':
                $this->act = " \"act\": [{
                            \"t\": \"push_messages\",
                            \"p\": {
                                \"url\": \"{$url}\",
                                \"get\": \"0\"
                            }
                        }],";
                break;
        endswitch;

        $this->url = $url;
    }

    public function getArrayAttributes()
    {
        // [{"t":"message","p":{}}]
        return [
            0 => (object)[
                "t" => "message",
                "p" => (object)[
                    "url" => $this->url,
                    "get" => 0
                ]
            ]

        ];
//        return [
//
//           "t" => "push_messages",
//            "p" => [
//                "url" => $this->url,
//                "get" => 0
//            ]
////           "p" => '{"url":"'.$this->url.'","get":0}'
//        ];
    }

    public static function getDefaults()
    {
        return [
            0 => [
                "t" => "message",
                "p" => function () {},
            ]
        ];
    }

    public function getAct()
    {
        return $this->act;
    }
}