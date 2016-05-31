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
      $render = array (
          "#theme" => "polymer_element"
      );
      $html = \Drupal::service('renderer')->renderRoot($render);
      var_dump( $html->__toString());

      return new Response("OK" . $themename . '->' . $elementname.'\n'.$html, 200, ["Content-Type" => "text/html"]);
  }
}