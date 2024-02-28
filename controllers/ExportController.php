<?php

namespace humhub\modules\legal\controllers;

use Yii;
use humhub\modules\user\components\BaseAccountController;
use humhub\modules\post\models\Post;
use yii\web\Response;

/**
 * ExportController handles exporting user data as JSON.
 *
 */
class ExportController extends BaseAccountController
{
    /**
     * Displays the exported user data as JSON.
     *
     * @return string The rendered view.
     */
    public function actionIndex()
    {
        $jsonData = $this->getUserDataAsJson();

        // Render the view with the JSON data
        return $this->renderAjax('index', ['jsonData' => $jsonData]);
    }

    /**
     * Downloads the exported user data as a JSON file.
     *
     * @return Response The file response.
     */
    public function actionDownload()
    {
        $jsonData = $this->getUserDataAsJson();

        // Set headers for file download
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/json');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="userdata.json"');

        // Output JSON data to response body
        Yii::$app->response->content = $jsonData;

        // Send the response
        return Yii::$app->response;
    }

    /**
     * Retrieves user data and posts and returns as JSON.
     *
     * @return string The JSON data.
     */
    private function getUserDataAsJson()
    {
        $currentUser = Yii::$app->user->getIdentity();

        // Retrieve user profile information
        $userData = [
            'user' => [
                'id' => $currentUser->id,
                'guid' => $currentUser->guid,
                'display_name' => $currentUser->displayName,
                'url' => $currentUser->getUrl(),
                'account' => [
                    'id' => $currentUser->id,
                    'guid' => $currentUser->guid,
                    'username' => $currentUser->username,
                    'email' => $currentUser->email,
                    'visibility' => $currentUser->visibility,
                    'status' => $currentUser->status,
                ],
                'profile' => [
                    'firstname' => $currentUser->profile->firstname,
                    'lastname' => $currentUser->profile->lastname,
                    'title' => $currentUser->profile->title,
                    'gender' => $currentUser->profile->gender,
                ]
            ]
        ];

        // Retrieve user post data
        $postData = [];
        $userPosts = Post::find()
            ->where(['created_by' => $currentUser->id])
            ->all();

        foreach ($userPosts as $post) {
            $postData[] = [
                'id' => $post->id,
                'message' => $post->message,
                'metadata' => [
                    'id' => $post->id,
                    'guid' => $post->createdBy->guid,
                    'object_model' => get_class($post),
                    'object_id' => $post->id,
                    'created_by' => [
                        'id' => $post->createdBy->id,
                        'guid' => $post->createdBy->guid,
                        'display_name' => $post->createdBy->displayName,
                        'url' => $post->createdBy->getUrl(),
                    ],
                    'created_at' => $post->created_at,
                    'updated_by' => [
                        'id' => $post->updatedBy->id,
                        'guid' => $post->updatedBy->guid,
                        'display_name' => $post->updatedBy->displayName,
                        'url' => $post->updatedBy->getUrl(),
                    ],
                    'updated_at' => $post->updated_at,
                    'url' => $post->getUrl(),
                ],
            ];
        }

        $jsonData = [
            'user' => $userData['user'],
            'posts' => $postData,
        ];

        // Convert data to JSON
        return json_encode($jsonData, JSON_PRETTY_PRINT);
    }
}
