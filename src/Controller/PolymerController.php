<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Controller\PolymerController.
 */

namespace Drupal\twig_polymer\Controller;

use Drupal\twig_polymer\PathProcessor\TwigPolymerPathProcessor;
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
    $this->config = \Drupal::config("twig_polymer.settings");
  }

  /**
   * @param $element
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function getElement($element) {
    $filePath = TwigPolymerPathProcessor::replaceColon($element);
    $realPath = $this->elementDiscovery->getElementFilesystemPath($filePath, $this->themeManager->getActiveTheme()->getName());
    return $this->loadElementFromFile($realPath);
  }

  public function getElementThemeSpecified($themename, $element) {
    //$path = $this->elementDiscovery->getElementFilesystemPath($element, $themename);
    //return $this->loadElementFromFile($path);
  }


  /**
   * Response with a Polymer Element
   */
  protected function loadElementFromFile($path) {
    if (!$path) {
      return new Response('Not found.', 404, ["Content-Type" => "text/html"]);
    }

    $file = file_get_contents($path);
    $path_parts = pathinfo($path);
    $extension = $path_parts['extension'];
    if ($extension == 'js') {
      $contentType = 'application/javascript';
    }
    elseif ($extension == 'css') {
      $contentType = 'text/css';
    }
    else {
      $contentType = 'text/html';
    }

    $response = new Response($file, 200, ["Content-Type" => $contentType]);
    if (!$this->config->get('debug_mode')){
      $response->setPublic();
      $response->setMaxAge($this->config->get('max_age'));
      $response->setSharedMaxAge($this->config->get('max_age'));
    }

    return $response;
  }
}
