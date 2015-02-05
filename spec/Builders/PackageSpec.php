<?php

namespace spec\Bmartel\LaravelPackage\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PackageSpec extends ObjectBehavior {

	function let() {

		$this->loadBlueprint(__DIR__ . '/../stubs/blueprint.php');
	}

	function it_is_initializable() {

		$this->shouldHaveType('Bmartel\LaravelPackage\Builders\Package');
	}

	function it_throws_an_exception_for_invalid_blueprint_files() {

		$this
			->shouldThrow('Bmartel\LaravelPackage\Exceptions\InvalidBlueprintException')
			->duringLoadBlueprint(__DIR__ . '/blueprint.php');
	}

	function it_can_load_package_blueprint_files() {

		$this->blueprint()->shouldReturn([
			'config' => '{{package}}.php',
			'migrations' => '.gitkeep',
			'lang/en' => '.gitkeep',
			'views' => '.gitkeep',
			'src' => 'ServiceProvider.php',
			'/' => [
				0 => 'composer.json',
				1 => 'README.md',
				2 => 'LICENSE'
			]
		]);
	}

	function it_can_traverse_and_parse_blueprint() {

		$output = [];

		$this->parseBlueprint(function ($path, $files) use(&$output) {

			if(count($files) === 1){
				$files = $files[0];
			}

			$output[$path] = $files;
		});

		$this->blueprint()->shouldBe($output);
	}


}
