# Development вocumentation

## Export user data

Please note this feature works only with enabled module [RESTful API](https://github.com/humhub/rest).

To append user data from another module:

1) Add event in the file `config.php` of your module the following line:

```php
['class' => 'humhub\modules\legal\services\ExportService', 'event' => 'collectUserData', 'callback' => ['humhub\modules\your_module\Events', 'onLegalModuleUserDataExport']],
```

2) Implement `humhub\modules\your_module\Events::onLegalModuleUserDataExport` like this:

```php
public static function onLegalModuleUserDataExport(\humhub\modules\legal\events\UserDataCollectionEvent $event)
{
    $event->addExportData('wiki', array_map(function ($page) {
        return \humhub\modules\wiki\helpers\RestDefinitions::getWikiPage($page);
    }, \humhub\modules\wiki\models\WikiPage::find()
        ->joinWith('content')
        ->andWhere(['content.created_by' => $event->user->id])
        ->all()));

    $files = File::findAll(['created_by' => $event->user->id]);
    foreach ($files as $file) {
        $event->addExportFile($file->file_name, $file->store->get());
    }
}
```

To test it go to edit your profile and run "Export your data", you should find the file `/files/wiki.json` in ZIP archive and all uploaded files of the User in the folder `/uploads/`.
