# Workshop

Workshop is a commandline tool for quickly generating Laravel based composer packages.

[![Build Status](https://travis-ci.org/bmartel/workshop.svg?branch=master)](https://travis-ci.org/bmartel/workshop) [![Latest Stable Version](https://poser.pugx.org/bmartel/workshop/v/stable.svg)](https://packagist.org/packages/bmartel/workshop) [![Total Downloads](https://poser.pugx.org/bmartel/workshop/downloads.svg)](https://packagist.org/packages/bmartel/workshop) [![Latest Unstable Version](https://poser.pugx.org/bmartel/workshop/v/unstable.svg)](https://packagist.org/packages/bmartel/workshop) [![License](https://poser.pugx.org/bmartel/workshop/license.svg)](https://packagist.org/packages/bmartel/workshop)

## Usage

### Install

    composer global require "bmartel/workshop=~1.0"

### Build something

    workshop build vendor/package

```cd package``` and you will see a skeleton structure for building a package.

And thats really all there is to it. Spend more time building something great, and less time
naming, renaming and copying files.

I plan to automate bootstrapping the project with git automatically, for now just
run ```git init```, ```git add --all``` and ```git commit -m"Initial Commit"``` manually.

## Contributing

All contributions whether features or fixes should be backed by a test which outlines
the work being done. Pull requests should be made to the develop branch.

### Development

To begin development, run ```composer install``` in the root of the project and start making changes. As you
develop you can run the tests via ```vendor/bin/phpspec run -v``` .

## Credits

Developed and maintained by Brandon Martel