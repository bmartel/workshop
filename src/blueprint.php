<?php

return [

	/**
	 * Blueprint files should follow the convention:
	 *
	 * 'path' => filenames [array|string]
	 *
	 * Ex.
	 * // root level files such as composer.json, or bower/package.json
	 * '/' => [
	 *      'composer.json',
	 *      'package.json',
	 *      'README.md',
	 * ],
	 *
	 * // General nested folders just follow the familiar path declaration.
	 * 'src/Console' => [
	 *      'MyCommand.php',
	 *      'AnotherCommand.php'
	 * ],
	 *
	 * // A folder nested within the above would go in its own declaration as follows.
	 * 'src/Console/Helpers' => [
	 *      'ArrayHelper.php',
	 *      'InfoParser.php'
	 * ],
	 *
	 * // If there is just one file in the folder, maybe just a gitkeep to commit a folder
	 * // Just use a single string instead of an array
	 * 'config' => '.gitkeep'
	 *
	 * Placeholders are available via provided data. {{vendor}} and {{package}} are
	 * provided by default, so you can make substitutions for filenames {{package}}.php would
	 * render the outputted file as the name of the package all lower case. If the package was
	 * named laravel, that file with the placeholder would output laravel.php.
	 *
	 * You can also provide your own data for replacements, just don't forget to pass them in.
	 */

	'config' => 'config:{{package}}.php',

	'migrations' => '.gitkeep',

	'lang/en' => '.gitkeep',

	'views' => '.gitkeep',

	'src' => 'ServiceProvider.php',

	 '/' => [
		 'routes.php',
		 'composer.json',
		 '.gitignore',
		 'README.md',
		 'LICENSE.txt'
	 ]
];