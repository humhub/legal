<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\events;

use humhub\modules\comment\models\Comment;
use humhub\modules\file\models\File;
use humhub\modules\like\models\Like;
use humhub\modules\post\models\Post;
use humhub\modules\rest\definitions\CommentDefinitions;
use humhub\modules\rest\definitions\FileDefinitions;
use humhub\modules\rest\definitions\LikeDefinitions;
use humhub\modules\rest\definitions\PostDefinitions;
use humhub\modules\rest\definitions\UserDefinitions;
use humhub\modules\user\events\UserEvent;

class UserDataCollectionEvent extends UserEvent
{
    public array $userData = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->addUserData('user', UserDefinitions::getUser($this->user));

        $this->addUserData('post', array_map(function ($post) {
            return PostDefinitions::getPost($post);
        }, Post::findAll(['created_by' => $this->user->id])));

        $this->addUserData('comment', array_map(function ($comment) {
            return CommentDefinitions::getComment($comment);
        }, Comment::findAll(['created_by' => $this->user->id])));

        $this->addUserData('file', array_map(function ($file) {
            return FileDefinitions::getFile($file);
        }, File::findAll(['created_by' => $this->user->id])));

        $this->addUserData('like', array_map(function ($like) {
            return LikeDefinitions::getLike($like);
        }, Like::findAll(['created_by' => $this->user->id])));
    }

    public function addUserData(string $name, array $data)
    {
        $this->userData[$name] = $data;
    }
}
