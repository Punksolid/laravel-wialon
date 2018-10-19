<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 16/10/18
 * Time: 04:39 AM
 */

namespace Punksolid\Wialon;


class Account extends Item
{

    public static function details():?self
    {
      $wialon_api = new Wialon();
      $wialon_api->beforeCall();

      $params = [
        "type" => 1 //1 minimal info, 2 detailed
      ];

      $response = $wialon_api->core_get_account_data($params);

      return new self(json_decode($response));

    }
}