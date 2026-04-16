<?php

/*
|--------------------------------------------------------------------------
| JWT Configuration
|--------------------------------------------------------------------------
|
| This file is for storing the configuration related to JWT.
| Make sure to check that the 'secret' key is set to a random
| string when you generate your token.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | Secret key for signing tokens. Generate one by running:
    | php artisan jwt:secret
    |
    */

    'secret' => env('JWT_SECRET', base64_encode(env('APP_KEY'))),

    /*
    |--------------------------------------------------------------------------
    | JWT Algorithm
    |--------------------------------------------------------------------------
    |
    | Algorithm to use for signing: HS256, HS384, HS512, RS256, RS384, RS512
    |
    */

    'algo' => env('JWT_ALGO', 'HS256'),

    /*
    |--------------------------------------------------------------------------
    | JWT Token Expiration Time (in minutes)
    |--------------------------------------------------------------------------
    |
    | How long before a token expires after being issued
    |
    */

    'ttl' => env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | JWT Refresh Token Expiration (in minutes)
    |--------------------------------------------------------------------------
    |
    | How long before a refresh token expires
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),

    /*
    |--------------------------------------------------------------------------
    | JWT Claims
    |--------------------------------------------------------------------------
    |
    | Additional claims to add to the token payload
    |
    */

    'claims' => [
        'iss' => null,
        'iat' => true,
        'exp' => true,
        'nbf' => true,
        'jti' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Blacklist
    |--------------------------------------------------------------------------
    |
    | Enable token blacklisting for revocation. Requires cache driver setup.
    |
    */

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    'blacklist_storage' => env('JWT_BLACKLIST_STORAGE', 'cache'),

];
