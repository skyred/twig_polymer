<?php

namespace Drupal\twig_polymer\Command;

use Drupal\Component\Uuid\Com;
use Drupal\twig_polymer\Generator\PolymerElementGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Drupal\Console\Command\GeneratorCommand;
use Drupal\Console\Command\Shared\ContainerAwareCommandTrait;
use Drupal\Console\Command\Shared\ModuleTrait;
use Drupal\Console\Command\Shared\ConfirmationTrait;
use Drupal\Console\Style\DrupalStyle;

/**
 * Class CreatePolymerElementCommand.
 *
 * @package Drupal\twig_polymer
 */
class CreatePolymerElementCommand extends GeneratorCommand {

  use ContainerAwareCommandTrait;
  use ModuleTrait;
  use ConfirmationTrait;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('polymer:element')
      ->setDescription($this->trans('polymer.element.description'))
      ->addOption(
        'theme',
        '', // Shortcut.
        InputOption::VALUE_REQUIRED,
        $this->trans('polymer.element.options.theme')
      )
      ->addOption(
        'package',
        '',
        InputOption::VALUE_OPTIONAL,
        $this->trans('polymer.element.option.package')
      )
      ->addOption(
        'element',
        '',
        InputOption::VALUE_REQUIRED,
        $this->trans('polymer.element.option.element')
      )
      ->addOption(
        'create-style',
        '',
        InputOption::VALUE_NONE,
        $this->trans('polymer.element.option.create-style')
      );
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);

    $text = sprintf(
      '[Warning] Continuing generation might overwrite existing elements.',
    );

    $theme = $input->getOption('theme');
    $yes = $input->hasOption('yes')?$input->getOption('yes'):false;
    $package = $input->getOption('package');
    $elementName = $input->getOption('element');
    $createStyle = $input->getOption('create-style');

    if (!$this->confirmGeneration($io, $yes)) {
      return;
    }

    $this
      ->getGenerator()
      ->generate($theme, $package, $elementName, $createStyle);

    $io->info($text);
  }
  protected function interact(InputInterface $input, OutputInterface $output)
  {
    $io = new DrupalStyle($input, $output);
    // --theme option
    $theme = $input->getOption('theme');
    if (!$theme) {
      // @see Drupal\Console\Command\Shared\ModuleTrait::moduleQuestion
      $theme = $io->ask(
        $this->trans('polymer.element.options.theme'),
        'my-element'
      );
      $input->setOption('theme', $theme);
    }
    // --package
    $package = $input->getOption('package');
    if (!$package) {
      $package = $io->ask(
        $this->trans('polymer.element.options.package'),
        'my-element'
      );
      $input->setOption('package', $package);
    }
    // --element option
    $element = $input->getOption('element');
    if (!$element) {
      $element = $io->ask(
        $this->trans('polymer.element.options.element'),
        'my-element'
      );
      $input->setOption('element', $element);
    }
    // --container-aware option
    $createStyle = $input->getOption('create-style');
    if (!$createStyle) {
      $createStyle = $io->confirm(
        $this->trans('polymer.element.options.create-style'),
        true
      );
      $input->setOption('create-style', $createStyle);
    }
  }

  protected function createGenerator() {
    return new PolymerElementGenerator();
  }


}
