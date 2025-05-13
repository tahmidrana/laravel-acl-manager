<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard Route
    |--------------------------------------------------------------------------
    |
    | This is the route name where users will be redirected after accessing the
    | ACL admin panel. Useful for "Back to Dashboard" or breadcrumbs.
    |
    */
    'dashboard_route' => 'dashboard',


    /*
    |--------------------------------------------------------------------------
    | Superuser Column
    |--------------------------------------------------------------------------
    |
    | The column name on the User model that marks a superuser.
    | Change this if your app uses a different field.
    |
    */
    'superuser_column' => 'is_superuser',

    /*
    |--------------------------------------------------------------------------
    | Middleware Settings
    |--------------------------------------------------------------------------
    |
    | You can override the middleware used to protect the ACL admin routes.
    |
    */
    'middleware' => ['web', 'auth', 'is_superuser'],
];
