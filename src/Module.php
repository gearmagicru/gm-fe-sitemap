<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Frontend\Sitemap;

/**
 * Модуль карты сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Frontend\Sitemap
 */
class Module extends \Gm\Mvc\Module\FrontendModule
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.fe.sitemap';

    /**
     * {@inheritdoc}
     */
    public function controllerMap(): array
    {
        return [
            'info'     => 'Info',
            'settings' => 'Settings',
            '*'        => 'IndexController'
        ];
    }
}
