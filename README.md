Laravel Wialon 
=========================

install with 
```$xslt
composer require punksolid/laravel-wialon
```

Use the Key you need, put it in your .env file. 
```$xslt
WIALON_SECRET=5dce19710a5e26ab8b7b8986cb3c49e58C291791B7F0A7AEB8AFBFCEED7DC03BC48FF5F8
```

Use
--------
List all units
```php 

        $units = \Punksolid\Wialon\Unit::all();
        
        dd($units);
        /**
            Illuminate\Support\Collection {#661
              #items: array:29 [
                0 => Punksolid\Wialon\Unit {#663
                  +id: 734477
                  +nm: "Audi RS8"
                  +mu: 0
                  +cls: 2
                  +uacl: 551920075299
                  +"pos": {#229
                    +"t": 1548292667
                    +"f": 1073741825
                    +"lc": 0
                    +"y": 52.33044
                    +"x": 9.78641
                    +"c": 73
                    +"z": 0
                    +"s": 1
                    +"sc": 0
                  }
                  +"lmsg": {#232
                    +"t": 1548292667
                    +"f": 1073741825
                    +"tp": "ud"
                    +"pos": {#233
                      +"y": 52.33044
                      +"x": 9.78641
                      +"c": 73
                      +"z": 0
                      +"s": 1
                      +"sc": 0
                    }
                    +"lc": 0
                    +"rt": 1548292668
                    +"p": {#234}
                  }
        */
```
Get all notifications
```php

        $notifications = Notification::all();
        dd($notifications);
        
        /*
         * Illuminate\Support\Collection {#3961
             #items: array:394 [
               0 => Punksolid\Wialon\Notification {#311
                 +id: 1
                 +n: "serobaronmibici"
                 +txt: "Test Notification Text"
                 +ta: 1539031912
                 +td: 1539636712
                 +ma: 0
                 +mmtd: null
                 +cdt: null
                 +mast: null
                 +mpst: null
                 +cp: null
                 +fl: 3
                 +tz: null
                 +la: null
                 +ac: 0
                 +un: array:2 [ …2]
                 +"act": array:1 [ …1]
                 +"trg": "geozone"
                 +"trg_p": {#233 …3}
                 +"crc": 285336170
                 +"ct": 1539031913
                 +"mt": 1539636712
                 +"nm": "serobaronmibici"
                 +"name": "serobaronmibici"
                 +"control_type": "geozone"
                 +"actions": array:1 [ …1]
                 +"text": "Test Notification Text"
                 +"resource": {#231 …9}
               }
         */

```
Find a notification by ID
To find a notification you need the id and the resource->id this method finds it using an underscore as a eparator
```php
        $found_notification = Notification::findByUniqueId("{$resource_id}_{$notification_id}");
```

Better documentation later, you could check the methods used in the testing classes available
Pull requests accepted
        