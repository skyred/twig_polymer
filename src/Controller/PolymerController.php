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

    $twig = \Drupal::service('twig');

    $theme_dir = drupal_get_path("theme", $themename);
    if ($theme_dir === "") {
      return new Response('Theme ' . $themename . 'not found.', 404, ["Content-Type" => "text/html"]);
    }

    try {
      $template = $twig
        ->loadTemplate($theme_dir . '/polymer-elements/' . $elementname .'.html.twig');
    } catch (\Twig_Error_Loader $e) {

      return new Response('Template not found.', 404, ["Content-Type" => "text/html"]);
    }

    $html = $template->render(["hello" => "world"]);

    //var_dump( $html);

    //return $render;
    return new Response($html, 200, ["Content-Type" => "text/html"]);
  }


  public function getElementInCurrentTheme($elementname) {

    $twig = \Drupal::service('twig');

    $theme_dir = \Drupal::theme()->getActiveTheme()->getPath();

    try {
      $template = $twig
        ->loadTemplate($theme_dir . '/polymer-elements/' . $elementname .'.html.twig');
    } catch (\Twig_Error_Loader $e) {

      return new Response('Template not found.', 404, ["Content-Type" => "text/html"]);
    }

    $html = $template->render(["hello" => "world"]);

    //var_dump( $html);

    //return $render;
    return new Response($html, 200, ["Content-Type" => "text/html"]);
  }
}