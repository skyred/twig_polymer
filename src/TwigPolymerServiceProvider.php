<?php
/**
 * @file
 * Contains Drupal\twig_polymer\TwigPolymerServiceProvider.
 */

namespace Drupal\twig_polymer;

use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ContainerBuilder;

/**
 * Class TwigPolymerServiceProvider
 *
 * @package Drupal\twig_polymer
 */
class TwigPolymerServiceProvider extends ServiceProviderBase {
  /**
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   */
  public function alter(ContainerBuilder $container) {
    // Override the twig class, to use our own TwigEnvironment.
    $definition = $container->getDefinition('twig');
    $definition->setClass('Drupal\twig_polymer\Twig\Tags\Environment');
  }
}
