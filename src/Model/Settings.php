<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Frontend\Sitemap\Model;

use Gm;
use Gm\Panel\Data\Model\ModuleSettingsModel;

/**
 * Модель настроек модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Frontend\Sitemap\Model
 * @since 1.0
 */
class Settings extends ModuleSettingsModel
{
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg(Gm::t(BACKEND, 'Settings successfully changed'), $this->t('{settings.title}'), 'accept');
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getDirtyAttributes(array $names = null): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'limit' => 'limit' // записей в файле
        ];
    }
}
