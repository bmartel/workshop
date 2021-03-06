# Workshop

Workshop is a commandline tool for quickly generating Laravel based composer packages.

[![Build Status](https://travis-ci.org/bmartel/workshop.svg?branch=master)](https://travis-ci.org/bmartel/workshop) [![Latest Stable Version](https://poser.pugx.org/bmartel/workshop/v/stable.svg)](https://packagist.org/packages/bmartel/workshop) [![Total Downloads](https://poser.pugx.org/bmartel/workshop/downloads.svg)](https://packagist.org/packages/bmartel/workshop) [![Latest Unstable Version](https://poser.pugx.org/bmartel/workshop/v/unstable.svg)](https://packagist.org/packages/bmartel/workshop) [![License](https://poser.pugx.org/bmartel/workshop/license.svg)](https://packagist.org/packages/bmartel/workshop)

## Usage

### Install

    composer global require "bmartel/workshop=~1.0"

### Build something

    workshop build vendor/package

```cd package``` and you will see a skeleton structure for building a package.

### Laravel Generators

For convenience, most of the Laravel generators have been included so you can quickly build up Laravel based packages.

Any `make:` generator command you are used to in a Laravel project, `php artisan make:{something}`, is available through `workshop build:{something}`.
All commands are also available through the `make` alias `workshop make:{something}`, so you only have to remember the Laravel API for the commands.

By default all generators will place the generated class files in a best guess location based on your namespacing and folder structure. If the defaults aren't working for you, you can always explicitly state where you want your files to be generated by providing the full class namespace as the name argument to the generator commands.

*Migrations will always be generated into a root level `migrations` folder of the package. This is due to the structure and default handling in the ServiceProvider included with all workshop scaffolded packages.*

    workshop build:console <name> [--command]
    workshop build:controller <name> [--plain]
    workshop build:event <name> 
    workshop build:job <name> [--queued]
    workshop build:listener <name> (--event) [--queued]
    workshop build:middleware <name>
    workshop build:migration <name> [--create] [--table]
    workshop build:model <name> [--migration]
    workshop build:request <name>
    
For more information regarding a specific command, see the Laravel official documentation.

#### Laravel packages

You can make use of this package and develop it within a local laravel application you may have. To pull this package
into your local development app, add the vendor/package you provided as argument to the workshop build command to the app's ```composer.json``` (lets assume the package created was acme/sprockets):

    require: {
        "acme/sprockets": "dev-master"
    },
    "repositories": [
		{
			"type": "vcs",
			"url": "/path/to/where/acme/sprockets/was/created/locally"
		}
	],

Run ```composer update acme/sprockets``` from your laravel app's root directory, and you are setup for easy local package development. 

The development workflow simply becomes: make a change in the package on your local filesystem, go to your local development laravel app you are including the package and run ```composer update vendor/package```. Rinse and repeat until you are ready to push it up to github or bitbucket and add the package to packagist, upon which you can drop the repostories entry and just include the composer require entry for the package.

And thats really all there is to it. Spend more time building something great, and less time
naming, renaming and copying files.

## Contributing

All contributions whether features or fixes should be backed by a test which outlines
the work being done. Pull requests should be made to the develop branch.

### Development

To begin development, run ```composer install``` in the root of the project and start making changes. As you
develop you can run the tests via ```vendor/bin/phpspec run -v``` .

## Credits

Developed and maintained by Brandon Martel
