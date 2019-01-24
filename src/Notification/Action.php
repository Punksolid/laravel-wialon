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
    public function __construct($type, $params = [])
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
                                \"url\": \"{$params['url']}\",
                                \"get\": \"0\"
                            }
                        }],";
                break;
        endswitch;
    }

    public function getAct()
    {
        return $this->act;
    }
}