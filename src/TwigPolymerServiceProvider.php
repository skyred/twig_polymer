<?php
namespace Drupal\twig_polymer;

use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ContainerBuilder;

class TwigPolymerServiceProvider extends ServiceProviderBase {
  public function alter(ContainerBuilder $container) {
    //override the twig class, to use our own TwigEnvironment
    $definition = $container->getDefinition('twig');
    $definition->setClass('Drupal\twig_polymer\Twig\Tags\Environment');
  }
}
