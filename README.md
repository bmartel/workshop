# Workshop

Workshop is a commandline tool for quickly generating Laravel based composer packages.

## Usage

### Install

    composer global require "bmartel/workshop=~1.0"

### Build something

    workshop build vendor/package

```cd package``` and you will see a skeleton structure for building package.

And thats really all there is to it. Spend more time building something great, and less time
naming, renaming and copying files.

I plan to automate bootstrapping the project with git automatically, for now just
run ```git init```, ```git add --all``` and ```git commit -m"Initial Commit"``` manually.

## Contributing

All contributions whether features or fixes should be backed by a test which outlines
the work being done. Pull requests should be made to the develop branch.

### Development

To begin development, run ```composer install``` in the root of the project. As you
develop you can run the tests via ```vendor/bin/phpspec run -v``` .

## Credits

Developed and maintained bmartel