<?php

namespace spec\Bmartel\Workshop;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Filesystem\Filesystem;

class GeneratorSpec extends ObjectBehavior
{

    function let()
    {

        $this->beConstructedWith(__DIR__ . '/../templates');
        $this->setOutputPath(__DIR__ . '/package-test');
    }

    function letGo()
    {

        $filesystem = new Filesystem();
        $filesystem->remove(__DIR__ . '/package-test');
    }

    function it_is_initializable()
    {

        $this->shouldHaveType('Bmartel\Workshop\Generator');
    }

    function it_can_create_a_config_directory()
    {

        $this->createDirectory('config')->shouldReturn(true);
    }

    function it_can_create_directories_recursively()
    {

        $this->createDirectory('src/Some/Package/Namespace')->shouldReturn(true);
    }

    function it_can_generate_a_file_from_a_template()
    {

        $this->generateFileFrom('filestubs/config/packagename.php', 'config', [])->shouldReturn(true);
    }

    function it_can_generate_a_file_from_a_template_with_placeholders()
    {

        $this->generateFileFrom('filestubs/composer.json', 'composer', [
            'Vendor' => 'Acme',
            'Package' => 'Packagename',
            'vendor' => 'acme',
            'package' => 'packagename'
        ])->shouldReturn(true);

        $output = file_get_contents(__DIR__ . '/package-test/filestubs/composer.json');
        $expected = file_get_contents(__DIR__ . '/stubs/composer.json');

        if ($output != $expected) {
            throw new FailureException("The contents of the files are not the same");
        }
    }

    function it_attempts_to_find_a_matching_template_to_generate_a_file_from()
    {

        $this->getTemplateForFileName('ServiceProvider.php')->shouldReturn(['ServiceProvider.php', 'ServiceProvider.mustache']);
        $this->getTemplateForFileName('.gitkeep')->shouldReturn(['.gitkeep', 'default.mustache']);
        $this->getTemplateForFileName('.gitignore')->shouldReturn(['.gitignore', 'gitignore.mustache']);

    }

    function it_allows_a_template_to_be_specified_for_file_generation()
    {

        $this->getTemplateForFileName('config:package.php')->shouldReturn(['package.php', 'config.mustache']);
    }

    function it_can_provide_a_default_template_when_a_match_is_not_found_for_the_file()
    {

        $this->getTemplateForFileName('MyAwesomeClass.php')->shouldReturn(['MyAwesomeClass.php', 'default.mustache']);
    }

    function it_can_perform_placeholder_data_replacements_in_filenames()
    {

        $this->replacePlaceholderInline('{{package}}.php', ['package' => 'acme'])->shouldReturn('acme.php');
    }

    function it_can_perform_placeholder_data_replacements_in_filenames_for_files_specifying_a_template()
    {

        $this->replacePlaceholderInline('config:{{package}}.php', ['package' => 'acme'])->shouldReturn('config:acme.php');
    }

    function it_can_initialize_package_as_a_git_repository()
    {

        $this->generateFileFrom('filestubs/test.txt', 'default', []);

        $this->newGitRepository();

        if (!file_exists(__DIR__ . '/package-test/.git')) {

            throw new FailureException('Unable to create the git repository.');
        }
    }
}
