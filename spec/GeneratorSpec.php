<?php

namespace spec\Bmartel\LaravelPackage;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Filesystem\Filesystem;

class GeneratorSpec extends ObjectBehavior {

	function let() {

		$this->beConstructedWith(__DIR__ . '/../templates');
		$this->setOutputPath(__DIR__ . '/package-test');
	}

	function letGo() {

		$filesystem = new Filesystem();
		$filesystem->remove(__DIR__ . '/package-test');
	}

	function it_is_initializable() {

		$this->shouldHaveType('Bmartel\LaravelPackage\Generator');
	}

	function it_can_create_a_config_directory() {

		$this->createDirectory('config')->shouldReturn(true);
	}

	function it_can_create_directories_recursively() {

		$this->createDirectory('src/Some/Package/Namespace')->shouldReturn(true);
	}

	function it_can_generate_a_file_from_a_template() {

		$this->generateFileFrom('filestubs/config/packagename.php', 'config', [])->shouldReturn(true);
	}

	function it_can_generate_a_file_from_a_template_with_placeholders() {

		$this->generateFileFrom('filestubs/composer.json', 'composer', [
			'Vendor' => 'Acme',
			'Package' => 'Packagename',
			'vendor' => 'acme',
			'package' => 'packagename'
		])->shouldReturn(true);

		$output = file_get_contents(__DIR__. '/package-test/filestubs/composer.json');
		$expected = file_get_contents(__DIR__ . '/stubs/composer.json');

		if($output != $expected) {
			throw new FailureException("The contents of the files are not the same");
		}
	}

	function it_attempts_to_find_a_matching_template_to_generate_a_file_from() {

		$this->getTemplateForFileName('ServiceProvider.php')->shouldReturn('ServiceProvider.mustache');
		$this->getTemplateForFileName('.gitkeep')->shouldReturn('default.mustache');
	}

	function it_can_provide_a_default_template_when_a_match_is_not_found_for_the_file() {

		$this->getTemplateForFileName('MyAwesomeClass.php')->shouldReturn('default.mustache');
	}

	function it_can_perform_placeholder_data_replacements_in_filenames() {

		$this->replacePlaceholderInline('{{package}}.php', ['package' => 'acme'])->shouldReturn('acme.php');
	}
}
