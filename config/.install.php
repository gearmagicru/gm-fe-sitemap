<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => FRONTEND,
    'id'          => 'gm.fe.sitemap',
    'name'        => 'Sitemap',
    'description' => 'Information for search engines about website pages',
    'namespace'   => 'Gm\Frontend\Sitemap',
    'path'        => '/gm/gm.fe.sitemap',
    'route'       => 'sitemap', // использует BACKEND
    'routes'      => [
        [
            'use'     => FRONTEND,
            'type'    => 'literal',
            'options' => [
                'module'  => 'gm.fe.sitemap',
                'compare' => 'uri',
                'route'   => 'sitemap.xml',
            ]
        ],
        [
            'use'     => BACKEND,
            'type'    => 'crudSegments',
            'options' => [
                'module' => 'gm.fe.sitemap',
                'route'  => 'sitemap',
                'prefix' => BACKEND
            ]
        ]
    ],
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['settings', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM CMS']
    ]
];
