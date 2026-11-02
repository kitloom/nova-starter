<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | Passwords are WordPress's: `wordpress` (kitloom/wp-schema) writes its
    | `$wp` hashes and checks every kind WordPress has written.
    |
    */

    'driver' => env('HASH_DRIVER', 'wordpress'),

];
