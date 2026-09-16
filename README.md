# organiser

This repository has been migrated to a CodeIgniter 4 structure.

Key changes:
- New CI4 app/ folder
- Controllers moved to `app/Controllers`
- Models moved to `app/Models`
- Views moved to `app/Views`
- Routes configured in `app/Config/Routes.php`
- Bootstrap entry point is `public/index.php`

Before running the app, install dependencies:

```bash
composer install
```

Then configure your database connection in `app/Config/Database.php` and run a local PHP server:

```bash
php spark serve
```
