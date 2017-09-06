# Simpleweb #

## About ##

It's an administration area. A starting point for developing back-office systems, intranet or a CMS. Where you want to manage something like:
- content of your web portal
- products and orders of your e-shop
- or customers of your product

![Simpleweb administration area example](https://docs.brackets.sk/assets/movies-crud-1.png "Simpleweb administration area example")

You could call it CMS, but it's very slim one with as little content to manage as possible. It has:
- UI - nice admin template based on CoreUI (http://coreui.io/)
- User management
- Translations management
- CRUD generator
- other helpers to quickly bootstrap your new administration area (Media Library, Admin Listing, etc.)

## Requirements ##

Simpleweb requires:
- PHP 7.0+
- MySQL 5.7+ or PostgreSQL 9.5+
- npm 5.3+
- node 8.4+

It uses Laravel 5.5, so it has to meet also all its requirements https://laravel.com/docs/5.5/installation#server-requirements.

## Installation ##

You can use your existing Laravel 5.5 application, that is already set up, with database migrated and running. Installation wizard is going to generate some code based on your actual database structure (i.e. users table structure), migrate database, etc.

Alternatively, you can install this package on fresh Laravel installation with existing empty database, no prob.

### Pre-installation ###

If you want to start on fresh Laravel 5.5, start with Laravel installation:
```bash
laravel new my_project
cd my_project
```

Before installation, remmeber to create the database and set up the database connection in your .env.

### We're in BETA ###

Simpleweb is still in BETA. That's why you have to allow not-stable package installation in your project:
```bash
composer config "minimum-stability" "beta"
composer config "prefer-stable" "true"
```

### Simpleweb installation ###

Now you can require two main packagess:

```bash
composer require brackets/simpleweb
composer require --dev brackets/admin-generator
```

Finally, let's install this package using:
```bash
php artisan simpleweb:install
```

This is going to install all dependencies, publish all important vendor configs, migrate, setup some configs, webpack config and run migrations.

Once SimpleWEB is crafted, don't forget to compile all the assets, so run something like this:
```bash
npm install
npm run dev
```

## Basic usage ##

Once installed, navigate your browser to `/admin/login`. You should be able to see login screen. Use these credentials:
- E-mail: `administrator@brackets.sk`
- Password: `best package ever`

After successful auhentication you should be able to see at least:
- Users CRUD
- Translations manager

## Documentation ##

You can find full documentation of this package and other our packages Simpleweb uses at https://docs.brackets.sk/#/simpleweb.

## Where to go next? ##

At this point you are ready to start building your custom administration area. You should definitely check our [Admin Generator](https://github.com/BRACKETS-by-TRIAD/admin-generator) package (docs https://docs.brackets.sk/#/admin-generator).
