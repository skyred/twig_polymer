<?php
/**
 * @file
 * Contains \Drupal\Console\Generator\CommandGenerator.
 */
namespace Drupal\twig_polymer\Generator;

use Drupal\Console\Generator\Generator;

class PolymerElementGenerator extends Generator
{
  /**
   * Generator Plugin Block.
   *
   * @param string  $module         Module name
   * @param string  $name           Command name
   * @param string  $class          Class name
   * @param boolean $containerAware Container Aware command
   */
  public function generate($theme, $package, $elementName, $createStyle)
  {
    $parameters = [
      'theme' => $theme,
      'element' => $elementName,
      'package' => $package,
      'create_style' => $createStyle,
      'style_element_name' => $elementName . '-styles',
    ];
    $messages['description'] = 'Say hello';

    $this->renderFile(
      'console/element.html.twig',
      $this->getSite()->getThemePath($theme).'/my-elements/'.$package.'/'.$elementName.'.html',
      $parameters
    );

    if ($createStyle) {
      $this->renderFile(
        'console/styles.html.twig',
        $this->getSite()->getThemePath($theme).'/my-elements/'.$package.'/'.$elementName.'-styles.html',
        $parameters
      );
    }
  }
}