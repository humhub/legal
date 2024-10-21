<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\widgets;

use humhub\widgets\JsWidget;

/**
 * Class Content
 * @package humhub\modules\legal\widgets
 */
class Content extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'legal.Content';

    /**
     * @inheritdoc
     */
    public $init = true;

    /**
     * @var string $content
     */
    public $content;

    /**
     * @var bool True - to prepare content with RichText, False - to print content as passed
     */
    public $richtext = true;

    public function run()
    {
        return $this->render('content', [
            'content' => $this->content,
            'richtext' => $this->richtext,
            'options' => $this->getOptions(),
        ]);
    }
}
