# README BUILD

## 01 - info general

## 02 - introduction

## 03 - structure dossier project application laravel

- create  a new Laravel v10 application

```bash
composer create-project laravel/laravel=10 _03_structure_dossier_appli_laravel
```

- `app/` contains most of the application core logic
- `composer.json` for Namespaces registry
- `boostrap/` for bootstrapping a Laravel app
- `config/` which have the main application settings
- `database/` where to handle all databases task
- `public/` as root folder for publicly accessible items in the application
- `resources/` for JS and CSS files
- `routes/`
- `storage/` as stock zone
- `test/`
- `vendor/` which contains all the packages to run the application
- `.env` file, CAUTION do not version this file, add to .gitignore
- `.artisan` file useful to execute commands, like start the application server `more traditionally`

```bash
php -S localhost:8000 -t public
```

or using `artisan`

```bash
php artisan # to see all available command from `artisan`    
# then
php artisan serve
```

## 04 - routing
