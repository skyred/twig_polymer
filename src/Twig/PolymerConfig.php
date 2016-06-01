<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Twig\PolymerConfig.
 */

namespace Drupal\twig_polymer\Twig;

/**
 * The object passed into compiled templates.
 */
class PolymerConfig {
  /**
   * @param $filename
   * @return string
   */
  public function getTemplate($filename) {
    // Syntax defined in Drupal\Core\Template\Loader\FilesystemLoader
    return '@twig_polymer/' . $filename;
  }
}
