<?php

namespace Bmartel\LaravelPackage\Builders;


use Bmartel\LaravelPackage\Exceptions\InvalidBlueprintException;
use Closure;
use Symfony\Component\Filesystem\Filesystem;

class Package {

	protected $blueprint;

	public function __construct() {

		$this->filesystem = new Filesystem();
		$this->blueprint = [];
	}

	/**
	 * Load a package file structure blueprint file
	 *
	 * @param $blueprintPath
	 * @throws InvalidBlueprintException
	 */
	public function loadBlueprint($blueprintPath = '') {

		$blueprintPath = $blueprintPath ?: __DIR__ . '/../blueprint.php';

		if ($this->validBlueprintFile($blueprintPath)) {
			$this->blueprint = include $blueprintPath;
		}

	}

	/**
	 * Get the currently loaded blueprint.
	 *
	 * @return array
	 */
	public function blueprint() {

		return $this->blueprint;
	}

	/**
	 * Determine whether the blue print is valid to load.
	 *
	 * @param $blueprintPath
	 * @return bool
	 * @throws InvalidBlueprintException
	 */
	private function validBlueprintFile($blueprintPath) {

		if($this->filesystem->exists($blueprintPath) && stristr($blueprintPath, '.php')) {
			return true;
		}

		throw new InvalidBlueprintException($blueprintPath);
	}

	/**
	 * Parse blueprint and allow callback to access the data for manipulation
	 *
	 * @param callable $callback
	 * @return array
	 */
	public function parseBlueprint(Closure $callback) {

		foreach ($this->blueprint as $path => $files) {

			$files = is_array($files) ? $files : [$files];

			$callback($path, $files);
		}

	}

}
