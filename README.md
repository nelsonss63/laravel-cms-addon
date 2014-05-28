## !! UNDER DEVELOPMENT !!

# Laravel CMS addon

This package will add a CMS to the background of your project. It will give you a CMS admin area where you add pages using an easy to read tree menu structure. Right click-able.

Coming features:

- Inject any page from CMS to any view location

## Start page untouched

Note that this CMS will not respond to / (start page) of your application.

## CMS login

    Login URL: /cms/login
    Username: admin
    Password: admin

Once logged in you can create pages that gets a slug (URL bit) which will be accessable immediately thanks to a query in the routes.php file for this CMS.
It is purpusly built only to respond to existing page slugs from the DB. This is of course cached so it won't query on each page load.

## Layout usage

### Extending master template

Typically in Laravel you have a master template that you extend from each of your view files, i.e. @extends('some.page').
On the Settings page you can replace the default value for what the CMS extends. Default is: cms::layouts.page
You may instead place your own master template here. For example: layouts.public

### Injecting content to master template

Default section name is "content", this is editable in the Settings. Change to any section name that you use, for example: right_column
Meanning your master template that your view file extends must have a: @yield('right_column') line somewhere in it for the CMS content to be displayed.


More usage info coming soon... Especially surrounding Menu generation in live view templates.

## Install

Add to composer.json:

    "cednet/laravel-cms-addon": "dev-master"


Add the following service provider:

    'Cednet\Cms\CmsServiceProvider'


Necessary migrations for CMS data tables: (re-run this after each update)

    php artisan migrate --package="cednet/laravel-cms-addon"


Publish all CMS assets (re-run this after each update)

    php artisan asset:publish cednet/laravel-cms-addon



