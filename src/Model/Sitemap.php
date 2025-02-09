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
use Gm\Stdlib\BaseObject;
use Gm\Site\Data\Model\Article;

/**
 * XML-схема протокола Sitemap.
 * 
 * @link https://www.sitemaps.org/ru/protocol.html
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Frontend\Sitemap\Model
 * @since 1.0
 */
class Sitemap extends BaseObject
{
    /**
     * Версия протокола.
     * 
     * @var string
     */
    public string $version = '0.9';

    /**
     * Максимальное количество записей URL-адреса в XML-схеме.
     * 
     * @var int
     */
    public int $limit = 1000;

    /**
     * Дата последнего изменения файла (формат "YYYY-mm-dd").
     * 
     * Дата должна быть установлена на дату последнего изменения связанной страницы, 
     * а не на момент создания карты сайта. 
     * 
     * Если значение установлено, то будет применятся для каждой записи URL-адреса.
     * 
     * @var string
     */
    public string $lastModification = '';

    /**
     * Вероятная частота изменения страницы.
     * 
     * Это значение предоставляет общую информацию для поисковых систем и может не 
     * соответствовать точно частоте сканирования страницы.
     * 
     * Значения: always, hourly, daily, weekly, monthly, yearly, never.
     * 
     * Если значение установлено, то будет применятся для каждой записи URL-адреса.
     * 
     * @var string
     */
    public string $changeFrequency = '';

    /**
     * Приоритетность одного URL-адреса относительно других URL-адресов.
     * 
     * Допустимый диапазон значений — от 0.0 до 1.0.
     * 
     * Если значение установлено, то будет применятся для каждой записи URL-адреса.
     * 
     * @var string
     */
    public string $priority = '';

    /**
     * Начало XML-схемы протокола Sitemap.
     * 
     * @return void
     */
    protected function begin(): void
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/' . $this->version . '">' . PHP_EOL;
    }

    /**
     * Тело XML-схемы протокола Sitemap.
     * 
     * @return void
     */
    protected function body(): void
    {
        $userTZ = Gm::$app->user->getTimeZone();
        $article = new Article();

        /** @var array $rows */
        $rows = $article
            ->getDb()
                ->createCommand(
                    $article->selectJoinCategories(
                        ['id', 'slug_type', 'slug', 'language_id', 'sitemap_priority', 'sitemap_frequency', 'publish_date', '_updated_date'], 
                        ['category_slug_path' => 'slug_path'], 
                        [
                            '(category.id IS NULL OR category.publish = 1)', 
                            'article.publish' => 1, 
                            'article.sitemap_enabled' => 1
                        ]
                    )
                )
                ->queryAll();

        $index = 1;
        foreach ($rows as $row) {
            if ($index++ > $this->limit) exit;

            echo '<url>', PHP_EOL;
            echo '<loc>', Article::makeUrl(
                $row['id'], 
                (int) $row['slug_type'], 
                $row['slug'], 
                $row['category_slug_path'], 
                $row['language_id'] ?? null
            ), '</loc>', PHP_EOL;

            if ($this->lastModification) {
                 echo '<lastmod>', $this->lastModification, '</lastmod>', PHP_EOL;
            } else {
                echo '<lastmod>', date('Y-m-d', strtotime($row['_updated_date'] ?: $row['publish_date'])), '</lastmod>', PHP_EOL;
            }

            if ($this->changeFrequency) {
                 echo '<changefreq>', $this->changeFrequency, '</changefreq>', PHP_EOL;
            } else {
                $changeFrequency = $row['sitemap_frequency'];
                if ($changeFrequency) {
                    echo '<changefreq>', $changeFrequency, '</changefreq>', PHP_EOL;
                }
            }

            if ($this->priority) {
                 echo '<priority>', $this->priority, '</priority>', PHP_EOL;
            } else {
                $priority = (float) $row['sitemap_priority'];
                if ($priority) {
                    echo '<priority>', $priority, '</priority>', PHP_EOL;
                }
            }
            echo '</url>', PHP_EOL;
        }
    }

    /**
     * Конец XML-схемы протокола Sitemap.
     * 
     * @return void
     */
    protected function end(): void
    {
        echo '</urlset>';
    }

    /**
     * Возвращает XML-схему протокола Sitemap.
     * 
     * @return string
     */
    public function run(): string
    {
        ob_start();

        $this->begin();
        $this->body();
        $this->end();

        return ob_get_clean();
    }
}
