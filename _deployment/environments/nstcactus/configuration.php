<?php

/**
 * High priority use case, not super planned out yet.
 * This will be useful in the future as we do a better job organizing our application configuration.
 * We need to distinguish between configuration and application constants.
 */

return [
    'session' => [
        'prefix' => 'directus6_',
    ],

    'default_language' => 'fr',

    'filesystem' => [
        'adapter'        => 'local',
        // By default media directory are located at the same level of directus root
        // To make them a level up outsite the root directory
        // use this instead
        // Ex: 'root' => realpath(BASE_PATH.'/../storage/uploads'),
        // Note: BASE_PATH constant doesn't end with trailing slash
        'root'           => BASE_PATH . '/storage/uploads',
        // This is the url where all the media will be pointing to
        // here all assets will be (yourdomain)/storage/uploads
        // same with thumbnails (yourdomain)/storage/uploads/thumbs
        'root_url'       => '/directus/storage/uploads',
        'root_thumb_url' => '/directus/storage/uploads/thumbs',
        //   'key'    => 's3-key',
        //   'secret' => 's3-key',
        //   'region' => 's3-region',
        //   'version' => 's3-version',
        //   'bucket' => 's3-bucket'
    ],

    'HTTP' => [
        'forceHttps' => false,
        'isHttpsFn'  => function () {
            // Override this check for custom arrangements, e.g. SSL-termination @ load balancer
            return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
        },
    ],

    'mail' => [
        'from'      => array(
            'postmaster@yohann-bianchi.ovh' => 'Le GAG (dev)',
        ),
        'transport' => 'smtp',
        'host'      => 'smtp.mailgun.org',
        'username'  => 'postmaster@yohann-bianchi.ovh',
        'password'  => '27ce3314a6f10ef38ce50e33bbb7c5bc',
        'port'      => '587',
    ],

    'cors' => [
        'enabled' => true,
        'origin'  => '*',
        'headers' => [
            'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, Access-Control-Allow-Origin',
            'Access-Control-Allow-Credentials' => 'false',
        ],
    ],

    'hooks' => [
        'postInsert' => function ($TableGateway, $record, $db) {

        },
        'postUpdate' => function ($TableGateway, $record, $db) {
            $tableName = $TableGateway->getTable();
            switch ($tableName) {
                // ...
            }
        },
    ],

    'filters' => [
        // 'table.insert.products:before' => \Directus\Customs\Hooks\BeforeInsertProducts::class
    ],

    'feedback'       => [
        'token' => '151233563362a8697a600d33717ac845934e2641',
        'login' => true,
    ],

    // These tables will not be loaded in the directus schema
    'tableBlacklist' => [],

    'statusMapping' => [
        0 => [
            'name'               => 'Supprimé',
            'text_color'         => '#FFFFFF',
            'background_color'   => '#F44336',
            'subdued_in_listing' => true,
            'show_listing_badge' => true,
            'hidden_globally'    => true,
            'hard_delete'        => false,
            'published'          => false,
            'sort'               => 3,
        ],
        1 => [
            'name'               => 'Publié',
            'text_color'         => '#FFFFFF',
            'background_color'   => '#3498DB',
            'subdued_in_listing' => false,
            'show_listing_badge' => false,
            'hidden_globally'    => false,
            'hard_delete'        => false,
            'published'          => true,
            'sort'               => 1,
        ],
        2 => [
            'name'               => 'Brouillon',
            'text_color'         => '#999999',
            'background_color'   => '#EEEEEE',
            'subdued_in_listing' => true,
            'show_listing_badge' => true,
            'hidden_globally'    => false,
            'hard_delete'        => false,
            'published'          => false,
            'sort'               => 2,
        ],
    ],

    'thumbnailer' => [
        '404imageLocation'             => __DIR__ . '/../thumbnail/img-not-found.png',
        'supportedThumbnailDimensions' => [
            // width x height
            // '100x100',
            // '300x200',
            // '100x200',
            '480x270',
        ],
        'supportedQualityTags'         => [
            'poor'   => 25,
            'good'   => 50,
            'better' => 75,
            'best'   => 100,
        ],
        'supportedActions'             => [
            'contain' => [
                'options' => [
                    'resizeCanvas'     => false, // http://image.intervention.io/api/resizeCanvas
                    'position'         => 'center',
                    'resizeRelative'   => false,
                    'canvasBackground' => 'ccc', // http://image.intervention.io/getting_started/formats
                ],
            ],
            'crop'    => [
                'options' => [
                    'position' => 'center', // http://image.intervention.io/api/fit
                ],
            ],
        ],
    ],
];
