# Manual

## Modules that support user data export

- [Calendar](https://github.com/humhub/calendar)
- [Files](https://github.com/humhub/cfiles)
- [Messenger](https://github.com/humhub/mail)
- [Polls](https://github.com/humhub/polls)
- [Tasks](https://github.com/humhub/tasks)
- [Wiki](https://github.com/humhub/wiki)

Please note that the "Export your data" feature only works if the [RESTful API](https://github.com/humhub/rest) module is enabled. By default, it exports the following data from the database:
- User data
- Posts
- Comments
- Likes
- Files (physical files are also attached to the ZIP archive in the `/uploads/` folder)
