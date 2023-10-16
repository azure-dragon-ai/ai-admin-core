<?php

// +----------------------------------------------------------------------
// | AIAdmin
// +----------------------------------------------------------------------
// | Copyright (c) 2014~2023 https://luomor.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/azure-dragon-ai/azure-dragon-ai/blob/main/LICENSE )
// +----------------------------------------------------------------------
// | Author: PeterZhang [ zhangchunsheng423@gmail.com ]
// +----------------------------------------------------------------------

return [
    /*
    |--------------------------------------------------------------------------
    | ai-admin default middleware
    |--------------------------------------------------------------------------
    |
    | where you can set default middlewares
    |
    */
    'middleware_group' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | ai-admin ai_auth_middleware_alias
    |--------------------------------------------------------------------------
    |
    | where you can set default middlewares
    |
    */
    'ai_auth_middleware_alias' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | ai-admin super admin id
    |--------------------------------------------------------------------------
    |
    | where you can set super admin id
    |
    */
    'super_admin' => 1,

    'request_allowed' => true,

    /*
    |--------------------------------------------------------------------------
    | ai-admin module setting
    |--------------------------------------------------------------------------
    |
    | the root where module generate
    | the namespace is module root namespace
    | the default dirs is module generate default dirs
    */
    'module' => [
        'root' => 'modules',

        'namespace' => 'Modules',

        'default' => ['develop', 'user', 'common'],

        'default_dirs' => [
            'Http'.DIRECTORY_SEPARATOR,

            'Http'.DIRECTORY_SEPARATOR.'Requests'.DIRECTORY_SEPARATOR,

            'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR,

            'Models'.DIRECTORY_SEPARATOR,

            'views'.DIRECTORY_SEPARATOR,
        ],

        // storage module information
        // which driver should be used?
        'driver' => [
            // currently, aiadmin support file and database
            // the default is driver
            'default' => 'file',

            // use database driver
            'table_name' => 'admin_modules'
        ],

        /**
         * module routes collection
         *
         */
        'routes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | ai-admin response
    |--------------------------------------------------------------------------
    */
    'response' => [
        // it's a controller middleware, it's set in AIController
        // if you not need json response, don't extend AIController
        'always_json' => \AI\Middleware\JsonResponseMiddleware::class,

        // response listener
        // it  listens [RequestHandled] event, if you don't need this
        // you can change this config
        'request_handled_listener' => \AI\Listeners\RequestHandledListener::class
    ],

    /*
   |--------------------------------------------------------------------------
   | database sql log
   |--------------------------------------------------------------------------
   */
    'listen_db_log' => true,

    /*
   |--------------------------------------------------------------------------
   | admin auth model
   |--------------------------------------------------------------------------
   */
    'auth_model' => \Modules\User\Models\User::class,

    /*
   |--------------------------------------------------------------------------
   | route config
   |--------------------------------------------------------------------------
   */
    'route' => [
        'prefix' => 'api',

        'middlewares' => [
            \AI\Middleware\AuthMiddleware::class,
            \AI\Middleware\JsonResponseMiddleware::class
        ],

        // 'cache_path' => base_path('bootstrap/ai') . DIRECTORY_SEPARATOR . 'admin_route_cache.php'
    ],

    'excel' => [
        'export' => [
            'csv_limit' => 20000,

            'path' => 'excel/export/'
        ]
    ]
];
