<?php

namespace humhub\modules\legal\services;

use humhub\modules\rest\definitions\UserDefinitions;
use humhub\modules\rest\definitions\PostDefinitions;
use humhub\modules\rest\definitions\CommentDefinitions;
use humhub\modules\rest\definitions\FileDefinitions;
use humhub\modules\rest\definitions\LikeDefinitions;
use humhub\modules\file\models\File;
use humhub\modules\like\models\Like;
use humhub\modules\post\models\Post;
use humhub\modules\comment\models\Comment;
use yii\base\Exception;

class ExportService
{
    /**
     * Retrieves user data.
     *
     * @param $currentUser
     * @return array
     */
    public function getUserData($currentUser)
    {
        if (\Yii::$app->hasModule('rest')) {
            return UserDefinitions::getUser($currentUser);
        } else {
            return $currentUser->attributes;
        }
    }

    /**
     * Retrieves current user's posts and returns as JSON.
     *
     * @param $currentUser
     * @return array
     */
    public function getPostData($currentUser)
    {
        if (\Yii::$app->hasModule('rest')) {
            $userPosts = Post::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function ($post) {
                return PostDefinitions::getPost($post);
            }, $userPosts);
        } else {
            return [];
        }
    }

    /**
     * Retrieves current user's files and returns as JSON.
     *
     * @param $currentUser
     * @return array
     */
    public function getFileData($currentUser)
    {
        if (\Yii::$app->hasModule('rest')) {
            $userFiles = File::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function ($file) {
                return FileDefinitions::getFile($file);
            }, $userFiles);
        } else {
            return [];
        }
    }

    /**
     * Retrieves current user's likes and returns as JSON.
     *
     * @param $currentUser
     * @return array
     */
    public function getLikeData($currentUser)
    {
        if (\Yii::$app->hasModule('rest')) {
            $userLikes = Like::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function ($like) {
                return LikeDefinitions::getLike($like);
            }, $userLikes);
        } else {
            return [];
        }
    }

    /**
     * Retrieves current user's comments and returns as JSON.
     *
     * @param $currentUser
     * @return array
     */
    public function getCommentData($currentUser)
    {
        if (\Yii::$app->hasModule('rest')) {
            $userComments = Comment::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function ($comment) {
                return CommentDefinitions::getComment($comment);
            }, $userComments);
        } else {
            return [];
        }
    }
}
