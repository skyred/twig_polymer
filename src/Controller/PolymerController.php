<?php
/**
 *
 * Contains Drupal\twig_polymer\Controller\PolymerController
 */

namespace Drupal\twig_polymer\Controller;


use Symfony\Component\HttpFoundation\Response;

class PolymerController
{
  public function getElement($themename, $elementname) {
    return $this->getElementFromTheme($themename, $elementname);
  }

  public function getElementInCurrentTheme($elementname) {

    $active_theme = \Drupal::theme()->getActiveTheme()->getName();
    return $this->getElementFromTheme($active_theme, $elementname);
  }


  /**
   * Renders a Polymer element Twig template from a certain theme.
   *
   * @param string $theme_name
   *   Name of the theme.
   * @param string $element
   *   Path to the template, excluding ".html.twig".
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response object. 404 if the template is not found.
   */
  protected function getElementFromTheme($theme_name, $element) {
    $twig = \Drupal::service('twig');

    $theme_dir = drupal_get_path("theme", $theme_name);
    if ($theme_dir === "") {
      return new Response('Theme ' . $theme_name . ' is not found.', 404, ["Content-Type" => "text/html"]);
    }

    try {
      $template = $twig
        ->loadTemplate($theme_dir . '/polymer-elements/' . $element .'.html.twig');
    } catch (\Twig_Error_Loader $e) {

      return new Response('Template not found.', 404, ["Content-Type" => "text/html"]);
    }
    $html = $template->render(["hello" => "world"]);
    return new Response($html, 200, ["Content-Type" => "text/html"]);
  }
}