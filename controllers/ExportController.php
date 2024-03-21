<?php

namespace humhub\modules\legal\controllers;

use Yii;
use humhub\modules\user\components\BaseAccountController;
use humhub\modules\rest\definitions\UserDefinitions;
use humhub\modules\rest\definitions\PostDefinitions;
use humhub\modules\rest\definitions\CommentDefinitions;
use humhub\modules\rest\definitions\FileDefinitions;
use humhub\modules\rest\definitions\LikeDefinitions;
use humhub\modules\legal\models\ConfigureForm;
use humhub\modules\file\models\File;
use humhub\modules\like\models\Like;
use humhub\modules\post\models\Post;
use humhub\modules\comment\models\Comment;
use humhub\modules\legal\jobs\ExportJob;
use yii\web\Response;

/**
 * ExportController handles exporting user data as JSON.
 */
class ExportController extends BaseAccountController
{
    /**
     * Renders the view displaying the exported user data as JSON.
     *
     * @return string The rendered view.
     */
    public function actionIndex()
    {
        return $this->renderAjax('index');
    }

    /**
     * Downloads the exported user data as a JSON file.
     *
     * @return Response The file download.
     * @throws \yii\base\Exception If the REST module is not enabled.
     */
    public function actionDownload()
    {
        // Check if the REST module is enabled
        if (!Yii::$app->hasModule('rest')) {
            throw new \yii\base\Exception('REST module is not enabled.');
        }

        $userData = $this->getUserData();
        $postData = $this->getPostData();
        $commentData = $this->getCommentData();
        $fileData = $this->getFileData();
        $likeData = $this->getLikeData();

        // Combine user, post, comment, file, and like data
        $data = [
            'user' => $userData,
            'post' => $postData,
            'comment' => $commentData,
            'file' => $fileData,
            'like' => $likeData,
        ];

        // Convert data to JSON
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        // Create a new export job
        $job = Yii::$app->queue->push(new ExportJob());

        // Wait for the job to be executed and completed
        Yii::$app->queue->getWaitingJobCount($job);

        // Set headers for JSON file download
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->headers->add('Content-Type', 'application/json');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="userdata.json"');

        // Output JSON data to response body
        Yii::$app->response->content = $jsonData;

        // Send the response
        Yii::$app->response->send();
        Yii::$app->end();
    }

    /**
     * Retrieves user data.
     *
     * @return array The user data.
     */
    private function getUserData()
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (Yii::$app->hasModule('rest')) {
            return UserDefinitions::getUser($currentUser);
        } else {
            return $currentUser->attributes;
        }
    }

    /**
     * Retrieves current user's posts and returns as JSON.
     *
     * @return array The JSON data.
     */
    private function getPostData()
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (Yii::$app->hasModule('rest')) {
            $userPosts = Post::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function($post) {
                return PostDefinitions::getPost($post);
            }, $userPosts);
        } else {
            return [];
        }
    }

    /**
     * Retrieves current user's files and returns as JSON.
     *
     * @return array The JSON data.
     */
    private function getFileData()
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (Yii::$app->hasModule('rest')) {
            $userFiles = File::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function($file) {
                return FileDefinitions::getFile($file);
            }, $userFiles);
        } else {
            return [];
        }
    }

    /**
     * Retrieves current user's likes and returns as JSON.
     *
     * @return array The JSON data.
     */
    private function getLikeData()
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (Yii::$app->hasModule('rest')) {
            $userLikes = Like::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function($like) {
                return LikeDefinitions::getLike($like);
            }, $userLikes);
        } else {
            return [];
        }
    }

    /**
     * Retrieves current user's comments and returns as JSON.
     *
     * @return array The JSON data.
     */
    private function getCommentData()
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (Yii::$app->hasModule('rest')) {
            $userComments = Comment::find()
                ->where(['created_by' => $currentUser->id])
                ->all();
            return array_map(function($comment) {
                return CommentDefinitions::getComment($comment);
            }, $userComments);
        } else {
            return [];
        }
    }
}
