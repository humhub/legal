<?php

namespace humhub\modules\legal\filters;

use Yii;

/**
 * Class MetadataFilter
 *
 * Provides methods to filter out unneeded metadata from the provided content.
 */
class MetadataFilter
{
    /**
     * Filter out unneeded metadata from the provided content.
     *
     * @param array $content The array of content items to be filtered.
     * @return array The filtered content array with comments removed.
     */
    public static function filterMetadata($content)
    {
        return array_map(function ($item) {
            // Remove comments completely
            unset($item['content']['comments']);
            return $item;
        }, $content);
    }
}
