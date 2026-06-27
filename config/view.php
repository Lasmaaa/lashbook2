<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    /*
    | Do not use realpath() here — the directory may not exist yet in Docker
    | during the first container boot on Render.
    */
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        storage_path('framework/views')
    ),

];
