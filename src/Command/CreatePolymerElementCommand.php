<?php

namespace Drupal\twig_polymer\Command;

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
      ->setDescription($this->trans('Scaffolding a new Polymer element.'))
      ->addOption(
        'theme',
        '', // Shortcut.
        InputOption::VALUE_REQUIRED,
        $this->trans('Theme name')
      )
      ->addOption(
        'package',
        '',
        InputOption::VALUE_OPTIONAL,
        $this->trans('Package name')
      )
      ->addOption(
        'element',
        '',
        InputOption::VALUE_REQUIRED,
        $this->trans('Element name (without .html)')
      )
      ->addOption(
        'create-style',
        '',
        InputOption::VALUE_NONE,
        $this->trans('Create a separate style module')
      );
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);

    $text = sprintf(
      '[Warning] Continuing generation might overwrite existing elements.'
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
      $theme_list = $this->getThemeList();
      $theme = $io->choiceNoList(
        $this->trans('Enter the theme name'),
        array_keys($theme_list)
      );
      $input->setOption('theme', $theme);
    }
    // --package
    $package = $input->getOption('package');
    if (!$package) {
      $package = $io->ask(
        $this->trans('Enter the package name'),
        'my-element'
      );
      $input->setOption('package', $package);
    }
    // --element option
    $element = $input->getOption('element');
    if (!$element) {
      $element = $io->ask(
        $this->trans('Enter the element name'),
        'my-element',
        function ($elementName) {
          // Custom Element name must have at least one dash.
          if (strpos($elementName, '-') !== FALSE) {
            return $elementName;
          }
          else {
            throw new \InvalidArgumentException(
              sprintf(
                'Element name "%s" is invalid, it mush contain at least one dash (-).',
                $elementName
              )
            );
          }
        }
      );
      $input->setOption('element', $element);
    }
    // --create-style option
    $createStyle = $input->getOption('create-style');
    if (!$createStyle) {
      $createStyle = $io->confirm(
        $this->trans('Do you want to create a separate style module?'),
        true
      );
      $input->setOption('create-style', $createStyle);
    }
  }

  protected function createGenerator() {
    return new PolymerElementGenerator();
  }

  protected function getThemeList() {
    $theme_list = [];
    $themes = $this->getThemeHandler()->rebuildThemeData();
    foreach ($themes as $theme_id => $theme) {
      $theme_list[$theme_id] = $theme->getName();
    }
    return $theme_list;
  }

}
