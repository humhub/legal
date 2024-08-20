# Development вocumentation

## Export user data

Please note that this feature will only work if the [RESTful API](https://github.com/humhub/rest) module is enabled.

To append user data from another module:

1) Add the following line to the `events` section of your module's `config.php` file:

```php
['class' => 'humhub\modules\legal\services\ExportService', 'event' => 'collectUserData', 'callback' => ['humhub\modules\your_module\Events', 'onLegalModuleUserDataExport']],
```

2) Implement the `humhub\modules\your_module\Events::onLegalModuleUserDataExport` method in your `Events` class like this:

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

To test it, go to edit your profile, and run "Export your data". You should find the file `/files/wiki.json` in the ZIP archive, along with all the user's uploaded files in the `/uploads/` folder.
