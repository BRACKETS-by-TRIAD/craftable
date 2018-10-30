# Craftable #

- [About](#about)
  - [Demo](#demo)
  - [Packages used](#made-of-components)
- [Requirements](#requirements)
- [Installation](#installation)
  - [New project](#new-craftable-project)
  - [Add to existing project](#add-craftable-to-existing-project)
- [Basics](#basics)
- [Documentation](#documentation)
- [Where to go next?](#where-to-go-next)

## About ##

Hi Crafter, welcome to the official documentation for Craftable v2 (v1 docs can be found here) - a Laravel-based open-source toolkit for building administration interfaces. It's an administration area minimalistic template. A starting point for developing back-office systems, intranets or a CMS systems.

![Craftable administration area example](https://docs.getcraftable.com/assets/posts-crud.png "Craftable administration area example")

You could call it CMS, but it's a very slim one, with as little content to manage as possible. It has:
- UI - nice admin template based on CoreUI (http://coreui.io/)
- CRUD generator
- Authorization, My profile & Users CRUD
- Translations manager
- other helpers to quickly bootstrap your new administration area (Media Library, Admin Listing, etc.)

### Demo ###

We have created a demo for you to play around at https://demo.getcraftable.com.

Use these credentials to sign-in:
- email: `demo@getcraftable.com`
- password: `demo123`

You can see an administration of:
- [Posts](https://demo.getcraftable.com/admin/posts) - this is the standard CRUD generated with `admin-generator` package
- [Translatable Articles](https://demo.getcraftable.com/admin/translatable-articles) - this is the showcase for `translatable`eloquent models
- [Manage access](https://demo.getcraftable.com/admin/users) - is a extended CRUD for the User (your existing eloquent model) management
- [Translations](https://demo.getcraftable.com/admin/translations) - where you can manage the translations stored in the database

### Made of components ###

Our intent was to split all the stuff into several packages with as least dependencies as possible. This is what we're coming with at the moment:
- [Admin UI](https://docs.getcraftable.com/#/admin-ui#admin-ui) - admin template (CoreUI assets, blades, Vue)
- [Admin Generator](https://docs.getcraftable.com/#/admin-generator#admin-generator) - CRUD generator for Eloquent models
- [Admin Authentication](https://docs.getcraftable.com/#/admin-auth#admin-auth) - ability to authenticate into Admin area
- [Translatable](https://docs.getcraftable.com/#/translatable#translatable) - ability to have translatable content (extending Laravel's default Localization)
- [Admin Listing](https://docs.getcraftable.com/#/admin-listing#admin-listing) - ability to quickly build a query for administration listing for your Eloquent models
- [Media Library](https://docs.getcraftable.com/#/media#media) - ability to attach media to eloquent models
- [Admin Translations](https://docs.getcraftable.com/#/admin-translations#admin-translations) - translation manager (with UI)

Craftable uses all the packages above. It also uses some other 3rd party packages (like Spatie's `spatie/laravel-permission`) and provides some basic default configuration to speed up a development of a typical administration interface.

## Requirements ##

Craftable requires:
- PHP 7.1+
- Supported databases:
  - MySQL 5.7+
  - PostgreSQL 9.5+
- npm 5.3+
- node 8.4+

Craftable uses Laravel so you should check out its requirements too. It is compatible with Laravel 5.5 and 5.6:
- https://laravel.com/docs/5.5/installation#server-requirements
- https://laravel.com/docs/5.6/installation#server-requirements

## Installation ##

### New Craftable project ###

If you want to start on fresh Laravel, you can use our `brackets/craftable-installer` that do all the tricks for you. Let's install it globally:
```bash
composer global require "brackets/craftable-installer"
```

Create an empty database of your choice (PostgreSQL or MySQL).

Now you can create a new Craftable project:
```bash
craftable new my_project
```

This will install Craftable using latest supported Laravel version (currently 5.6). If you prefer tu use latest LTS Laravel version (currently 5.5), use `--lts` flag:
```bash
craftable new --lts my_project
```

The commands is going to ask for a database settings and then it will setup everything (install all dependencies, publish all important vendor configs, migrate, setup some configs, webpack config and run migrations).


Command is going to generate and **print the password for the default administrator** account. Save this password to your clipboard, we are going to need it soon.

### Add Craftable to existing project ###

Or alternatively, you can use your existing Laravel 5.5 application. Start with requiring these two main packages:

```bash
composer require brackets/craftable
composer require --dev brackets/admin-generator
```

To install this package use:
```bash
php artisan craftable:install
```

This is going to install all dependencies, publish all important vendor configs, migrate, setup some configs, webpack config and run migrations.

Command is going to generate and **print the password for the default administrator** account. Save this password to your clipboard, we are going to need it soon.

## Basics ##

Once installed, navigate your browser to `/admin/login`. You should be able to see a login screen.

![Admin login form](https://docs.getcraftable.com/assets/login-form.png "Admin login form")

Use these credentials to log in:
- E-mail: `administrator@brackets.sk`
- Password: use password from you clipboard (it was printed in the end of the `craftable:install` command)

After authorization you should be able to see a default homepage and two menu items:
- Manage access
- Translations

![Admin homepage](https://docs.getcraftable.com/assets/admin-home.png "Admin homepage")

## Documentation ##

You can find full documentation of this package and other our packages Craftable uses at https://docs.getcraftable.com/#/craftable.

## Where to go next? ##

At this point you are ready to start building your administration area. You probably want to start building a typical CRUD interface for your eloquent models. You should definitely check our [Admin Generator](admin-generator) documentation.

In case you rather want to create some atypical custom made administration, then you probably want to head over to [Admin UI](admin-ui) package.

Have fun & craft something awesome!
