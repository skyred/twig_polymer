<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Controller\PolymerController.
 */

namespace Drupal\twig_polymer\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Remapping URL for Polymer Elements. (poly-serve equivalent)
 */

class PolymerController {
  protected $elementDiscovery;
  protected $themeManager;

  public function __construct() {
    $this->elementDiscovery = \Drupal::service('twig_polymer.element_discovery');
    $this->themeManager = \Drupal::service('theme.manager');
  }

  public function getElement($element) {
    $path = $this->elementDiscovery->getElementFilesystemPath($element, $this->themeManager->getActiveTheme()->getName());
    return $this->loadElementFromFile($path);
  }

  public function getElementThemeSpecified($themename, $element) {
    $path = $this->elementDiscovery->getElementFilesystemPath($element, $themename);
    return $this->loadElementFromFile($path);
  }


  /**
   * Response with a Polymer Element
   */
  protected function loadElementFromFile($path) {
    if (!$path) {
      return new Response('Not found.', 404, ["Content-Type" => "text/html"]);
    }

    $file = file_get_contents($path);

    return new Response($file, 200, ["Content-Type" => "text/html"]);
  }
}
