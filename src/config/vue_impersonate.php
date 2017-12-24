<?php return [

    /*
    |--------------------------------------------------------------------------
    | The name of the auth provider to use from within auth.php
    |--------------------------------------------------------------------------
    */

    'provider' => 'users',

    /*
    |--------------------------------------------------------------------------
    | The field to display in the user dropdown.
    |--------------------------------------------------------------------------
    */

    'display_name_field' => 'email',

    /*
    |--------------------------------------------------------------------------
    | If you want to use a custom route to return the user list, place the url
    | here and we'll use it instead. defult (null)
    |--------------------------------------------------------------------------
    */

    'custom_route' => null,

    /*
    |--------------------------------------------------------------------------
    | By default the view component is called `vue-impersonate` if you want a
    | different directive name then place it here. default (vue-impersonate)
    |--------------------------------------------------------------------------
    */

    'custom_directive' => 'vue-impersonate',

];