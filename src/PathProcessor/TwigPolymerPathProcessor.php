<?php
namespace Drupal\twig_polymer\PathProcessor;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Symfony\Component\HttpFoundation\Request;

class TwigPolymerPathProcessor implements InboundPathProcessorInterface {

  public static function replaceSlash($str) {
    return str_replace('/',':', $str);
  }

  public static function replaceColon($str) {
    return str_replace(':','/', $str);
  }

  public function processInbound($path, Request $request) {
    if (strpos($path, '/twig-polymer/') === 0) {
      $names = preg_replace('|^\/twig-polymer\/|', '', $path);
      $names = self::replaceSlash($names);
      return "/twig-polymer/$names";
    }
    return $path;
  }

}