<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path('documents'),
            'url' => env('APP_URL') . '/documents',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // Dành cho lưu tài liệu
        'documents' => [
            'driver' => 'local',
            'root' => public_path('documents'),
            'url' => env('APP_URL') . '/documents',
            'visibility' => 'public',
        ],

        // Dành cho lưu chứng thực văn bản từ hệ thống
        'certificates' => [
            'driver' => 'local',
            'root' => public_path('documents/certificates'),
            'url' => env('APP_URL') . '/documents/certificates',
            'visibility' => 'public',
        ],        

        // Dành cho lưu hình ảnh
        'images' => [
            'driver' => 'local',
            'root' => public_path('images'),
            'url' => env('APP_URL') . '/images',
            'visibility' => 'public',
        ],

        // Dành cho lưu hình ảnh đại diện
        'avatar' => [
            'driver' => 'local',
            'root' => public_path('images/avatars'),
            'url' => env('APP_URL') . '/images/avatars',
            'visibility' => 'public',
        ],

        // Dành cho lưu ảnh đại diện đơn vị
        'departments' => [
            'driver' => 'local',
            'root' => public_path('images/departments'),
            'url' => env('APP_URL') . '/images/departments',
            'visibility' => 'public',
        ],

        // Dành cho lưu ảnh chữ ký của người dùng
        'signatures' => [
            'driver' => 'local',
            'root' => public_path('images/signatures'),
            'url' => env('APP_URL') . '/images/signatures',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
