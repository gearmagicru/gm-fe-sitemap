<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Frontend\Sitemap\Controller;

use Gm;
use Gm\Http\Response;
use Gm\Mvc\Controller\Controller;

/**
 * Контроллер вывода карты сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Frontend\Sitemap\Controller
 * @since 1.0
 */
class IndexController extends Controller
{
    /**
     * Действие "index" выводит карту сайта.
     * 
     * @return Response
     */
    public function indexAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse(Response::FORMAT_XML);

        /** @var \Gm\Config\Config $settings */
        $settings = $this->module->getSettings();

        /** @var \Gm\Frontend\Sitemap\Model\Sitemap|null $sitemap */
        $sitemap = $this->getModel('Sitemap', $settings ? $settings->getAll() : []);
        return $response->setContent($sitemap->run());
    }
}
