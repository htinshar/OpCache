# Laravel OPcache

This package is to handle the OPcache in projects, currently there are only two command is obtain (`opcache-reset` and `opcache-status`). If require, other useful command will be implement later.


## Requirements
This package requires Laravel 5 or newer.

## Installation

You can install the package via Composer:

composer.json

```json
"repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/htinshar/OpCache.git"
      }
    ],
    
    "require": {
        "opcache": "dev-master"
    },

    ...
```
And then, update your composer package by `composer update`.

### Configuration

After you have installed the package, open your Laravel config file `config/app.php` and add the following lines.

In the `$providers` array add the service providers for this package.

```
  OpCache\OpCacheServiceProvider::class,
```

Then the package classes will be auto-loaded by Laravel.

And you need to pull a configuration file into your application by running on of the following artisan command.

```bash
$ php artisan vendor:publish
```

Then the configuration file is copied to `config/opcache.php`, finally configure it along your application settings.

## Usage

Clear OPcache:
``` bash
php artisan Opcache:opcache-reset
```

Show OPcache status:
``` bash
php artisan Opcache:opcache-status
```
# Contribution
I was use `curl` to send the request to load the `opCache` command. For security I use `sha-1` to generate the `signature` with `header`, `timestamp` and `secrect` key. Which is implemnt in `src\Middleware`

If you want to add another `OP-Cache` command, implement command object in `src/Command` folder, create the `controller` function in `src\Controller` and assign this controller function to route in `src\Http\routes.php` and register this command object in `src/OpCacheServiceProvider.php`,