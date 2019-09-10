<?php

return [

    //WIALON_SECRET in .env
    //  php artisan vendor:publish --tag=wialon to publish
    'token' => env('WIALON_SECRET',null)
];
