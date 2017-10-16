# Craftable #

## About ##

It's an administration area. A starting point for developing back-office system, intranet or a CMS. Where you want to manage something like:
- content of your web portal
- products and orders of your e-shop
- or customers of your product

![Craftable administration area example](https://docs.brackets.sk/assets/posts-crud.png "Craftable administration area example")

You could call it CMS, but it's a very slim one, with as little content to manage as possible. It has:
- UI - nice admin template based on CoreUI (http://coreui.io/)
- CRUD generator
- Authorization, My profile & Users CRUD
- Translations manager
- other helpers to quickly bootstrap your new administration area (Media Library, Admin Listing, etc.)

### Demo ###

We have created a demo for you to play around at https://demo.brackets.sk.

Use these credentials to sign-in:
- email: `administrator@brackets.sk`
- password: `demo123`

You can see an administration of:
- [Posts](https://demo.brackets.sk/admin/posts) - this is the standard CRUD generated with `admin-generator` package
- [Translatable Articles](https://demo.brackets.sk/admin/translatable-articles) - this is the showcase for `translatable`eloquent models
- [Manage access](https://demo.brackets.sk/admin/users) - is a extended CRUD for the User (your existing eloquent model) management
- [Translations](https://demo.brackets.sk/admin/translations) - where you can manage the translations stored in the database

### Made of components ###

Our intent was to split all the stuff into several packages with as least dependencies as possible. This is what we're coming with at the moment:
- [Admin UI](https://docs.brackets.sk/#/admin-ui#admin-ui) - admin template (CoreUI assets, blades, Vue)
- [Admin Generator](https://docs.brackets.sk/#/admin-generator#admin-generator) - CRUD generator for Eloquent models
- [Admin Authentication](https://docs.brackets.sk/#/admin-auth#admin-auth) - ability to authenticate into Admin area
- [Translatable](https://docs.brackets.sk/#/translatable#translatable) - ability to have translatable content (extending Laravel's default Localization)
- [Admin Listing](https://docs.brackets.sk/#/admin-listing#admin-listing) - ability to quickly build a query for administration listing for your Eloquent models
- [Media Library](https://docs.brackets.sk/#/media#media) - ability to attach media to eloquent models
- [Admin Translations](https://docs.brackets.sk/#/admin-translations#admin-translations) - translation manager (with UI)

Craftable uses all the packages above. It also uses some other 3rd party packages (like Spatie's `spatie/laravel-permission`) and provides some basic default configuration to speed up a development of a typical administration interface.

## Requirements ##

Craftable requires:
- PHP:
  - PHP 7.1+ in development (for [AdminGenerator](https://docs.brackets.sk/#/admin-generator) package to work)
  - PHP 7.0+ in production
- Supported databases:
  - MySQL 5.7+
  - PostgreSQL 9.5+
- npm 5.3+
- node 8.4+

It uses Laravel 5.5, so it has to meet also all its requirements https://laravel.com/docs/5.5/installation#server-requirements.

## Installation ##

[![Craftable installation](https://docs.brackets.sk/assets/craftable-installation-youtube.png)](https://www.youtube.com/watch?v=DBCzLR5gpnw)

To start using Craftable you need to proceed two steps:
1. Adding Craftable
1. Installation

### Adding Craftable ###

You can use your existing Laravel 5.5 application. Or alternatively, you can create new Craftalbe instance (similarily to `laravel new` command.

#### Existing project ####

Start with requiring these two main packagess:

```bash
composer require brackets/craftable
composer require --dev brackets/admin-generator
```

#### New Craftable project ####

If you want to start on fresh Laravel 5.5, you can use our `brackets/craftable-installer` that do all the tricks for you. Let's install it globally:
```bash
composer global require "brackets/craftable-installer"
```

Now you can create a new Craftable project:
```bash
craftable new my_project
```

### Installation ###

!>Before installation verify, that you have an existing database and database connection environment is correctly set up. Installation wizard is going to generate some code based on your actual database structure (i.e. users table structure) and migrate database so the database connection is obligatory.

To install this package use:
```bash
php artisan craftable:install
```

This is going to install all dependencies, publish all important vendor configs, migrate, setup some configs, webpack config and run migrations.

Once Craftable is installed, don't forget to compile all the assets, so run something like this:
```bash
npm install && npm run dev
```

## Basics ##

Once installed, navigate your browser to `/admin/login`. You should be able to see a login screen.

![Admin login form](https://docs.brackets.sk/assets/login-form.png "Admin login form")

Use these credentials to log in:
- E-mail: `administrator@brackets.sk`
- Password: `best package ever`

After authorization you should be able to see a default homepage and two menu items:
- Manage access
- Translations

![Admin homepage](https://docs.brackets.sk/assets/admin-home.png "Admin homepage")

## Documentation ##

You can find full documentation of this package and other our packages Craftable uses at https://docs.brackets.sk/#/craftable.

## Where to go next? ##

At this point you are ready to start building your administration area. You probably want to start building a typical CRUD interface for your eloquent models. You should definitely check our [Admin Generator](admin-generator) documentation.

In case you rather want to create some atypical custom made administration, then you probably want to head over to [Admin UI](admin-ui) package.

Have fun & craft something awesome!