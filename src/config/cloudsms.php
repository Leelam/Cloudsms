<?php
/**
 * Created by Leelam Consultancy Services Pvt. Ltd.
 * User: Gopal Krishna Mosali
 * Email : krishna@leelam.com
 * Date: 28/11/15
 * Time: 12:51 PM
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Enable Boilerplate
    |--------------------------------------------------------------------------
    |
    | This is part will the people about functionality of the given by package
    | Settings and it can be override by local copy.
    |
    |
    */

    'enable' => env('CLOUDSMS_ENABLE', false),

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    | IT has been the industry's standard dummy text ever since the 1500s.
    | A galley of type and scrambled it to make a type specimen book.
    |
    */

    'route' => 'cloudsms',

    /*
    |--------------------------------------------------------------------------
    | Singular
    |--------------------------------------------------------------------------
    |
    | Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    | IT has been the industry's standard dummy text ever since the 1500s.
    | A galley of type and scrambled it to make a type specimen book.
    |
    */

    'singular' => 'cloudsms',

    /*
    |--------------------------------------------------------------------------
    | Title or Model
    |--------------------------------------------------------------------------
    |
    | Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    | IT has been the industry's standard dummy text ever since the 1500s.
    | A galley of type and scrambled it to make a type specimen book.
    |
    */

    'title' => 'Cloudsms',

    /*
    |--------------------------------------------------------------------------
    | Database Table
    |--------------------------------------------------------------------------
    |
    | Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    | IT has been the industry's standard dummy text ever since the 1500s.
    | A galley of type and scrambled it to make a type specimen book.
    |
    */

    'table' => 'cloudsmses',

    /*
    |--------------------------------------------------------------------------
    | Default SMSing Services POST or GET
    |--------------------------------------------------------------------------
    |
    | Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    | IT has been the industry's standard dummy text ever since the 1500s.
    | A galley of type and scrambled it to make a type specimen book.
    |
    */

    'connection' => 'cloudsms',

    /*
    |--------------------------------------------------------------------------
    | Arrays of connection
    |--------------------------------------------------------------------------
    |
    | Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    | IT has been the industry's standard dummy text ever since the 1500s.
    | A galley of type and scrambled it to make a type specimen book.
    |
    */

    'connections' => [

        'cloudsms' => [
            'route' => env('CLOUDSMS_ROUTE', 'FallBackRoute'),
            'sender' => env('CLOUDSMS_SENDER', 'FallBackSenderId'),
            'authkey' => env('CLOUDSMS_AUTHKEY', 'SomeHashStringGetItFromCloudsms'),
            'send_url' => env('CLOUDSMS_SEND_URL', 'Post URL from Cloudsms')

        ],

        'racksms' => [
            'username' => 'username',
            'password' => 'password',
        ],
    ]

];