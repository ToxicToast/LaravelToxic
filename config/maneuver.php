<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Ignored Files
    |--------------------------------------------------------------------------
    |
    | Maneuver will check .gitignore for ignore files, but you can conveniently
    | add here additional files to be ignored.
    |
    */
    'ignored' => array(),

    /*
    |--------------------------------------------------------------------------
    | Default server
    |--------------------------------------------------------------------------
    |
    | Default server to deploy to when running 'deploy' without any arguments.
    | If this options isn't set, deployment will be run to all servers.
    |
    */
    'default' => 'production',

    /*
    |--------------------------------------------------------------------------
    | Connections List
    |--------------------------------------------------------------------------
    |
    | Servers available for deployment. Specify one or more connections, such
    | as: 'deployment', 'production', 'stating'; each with its own credentials.
    |
    */

    'connections' => array(

        'production' => array(
            'scheme'    => 'ftp',
            'host'      => 'ftp.strato.de',
            'user'      => 'travis@toxictoast.de',
            'pass'      => 'L1zzar_1990',
            'path'      => '/',
            'port'      => 21,
            'passive'   => true
        ),

    ),

);