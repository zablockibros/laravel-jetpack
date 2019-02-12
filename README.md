# Laravel Jetpack

A package to make rapid prototyping even easier.

[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](https://opensource.org/licenses/MIT)

* [Background](#background)
* [Installation](#installation)
* [Copyright and License](#copyright-and-license)


## Background

This is background

## Installation

**Requirements**: This package requires the following version of Laravel:
> 
> | Laravel Version |
> |:---------------:|
> |       5.7       |

1. Add the following to the `repositories` section of you `composer.json` file:

    ```sh
    "repositories": [
        {
            "type": "vcs",
            "no-api": true,
            "url": "git@github.com:zablockibros/laravel-jetpack.git"
        }
    ],
    ```
    
2. Install the package via Composer:

    ```sh
    $ composer require zablockibros/laravel-jetpack:dev-master
    ```

    The package will automatically register its service provider.

3. Optionally, publish the configuration file if you want to change any defaults:

    ```sh
    php artisan vendor:publish --provider="ZablockiBros\Jetpack\JetpackServiceProvider"
    ```
    If you are using Roles & Permissions (via Spatie), e.g. `config('jetpack.roles.enable') === true`, then simply run:
    ```sh
    php artisan vendor:publish
    ```
## Setup

In order to start building resources and modules, you need to configure some required Jetpack settings.

#### Roles and Permissions

1. In `config/jetpack.php` set `roles.enable` to `true`.
2. Run 
    ```sh 
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
    ```
3. Run
    ```sh
    php artisan migrate
    ```
4. Modify and configure your desired roles and permissions using the provided sample array.
5. After modifying configued roles and permissions, ALWAYS run
    ```sh
    php artisan db:seed --class=RolesAndPermissionsSeeder
    ```

## Copyright and License

[MIT License](LICENSE.md).

Copyright (c) 2019 Justin Zablocki
