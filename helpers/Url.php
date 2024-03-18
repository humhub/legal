<?php

namespace humhub\modules\legal\helpers;

use Yii;
use yii\helpers\Url as BaseUrl;

/**
 * Class Url
 *
 * @package humhub\modules\legal\helpers
 */
class Url extends BaseUrl
{
    public const ROUTE_EXPORT = '/legal/export/index';

    /**
     * Get the URL for the admin configuration page.
     *
     * @return string
     */
    public static function getExportUrl(): string
    {
        return static::toRoute(static::ROUTE_EXPORT);
    }
}
