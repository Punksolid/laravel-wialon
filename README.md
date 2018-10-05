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
```php
<?php
$wialon_api = new \Punksolid\Wialon\Wialon();

        $units = $wialon_api->listUnits();
        
        dd($units);
        /**
        *   #collection: Illuminate\Support\Collection {#612
        *      #items: array:14 [
        *        0 => Punksolid\Wialon\Unit {#614
        *          0
        *          * +nm: "Audi RS8"
        *         * +cls: 2
        *          * +id: +uacl: 551920075299
        */
```



Better documentation later, you could check the methods available
        