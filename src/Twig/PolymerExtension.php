<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Twig\PolymerExtension.
 */

namespace Drupal\twig_polymer\Twig;

use Drupal\Core\TypedData\Plugin\DataType\Uri;
use Drupal\Core\Url;
use Drupal\twig_polymer\PathProcessor\TwigPolymerPathProcessor;
use Drupal\twig_polymer\Twig\Tags\PolymerTokenParser;
use Twig_TokenParserInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

/**
 * The Polymer Twig extension.
 */
class PolymerExtension extends Twig_Extension {

  private $config;
  /**
   * @var Twig_SimpleFunction[]
   */
  private $functions = [];

  /**
   * @var Twig_SimpleFilter[]
   */
  private $filters = [];

  /**
   * @var Twig_TokenParserInterface
   */
  private $token_parsers = [];

  public function __construct() {
    $this->config = \Drupal::config("twig_polymer.settings");
  }

  /**
   * Returns a list of extension functions
   *
   * @return array
   */
  public function getFunctions() {
    return array(
      new Twig_SimpleFunction($this->config->get('twig_tag').'_'.'asset', function ($filename) {
        /*if (strpos($filename, '/') === FALSE) {*/
          // Theme not specified.

        $polymer_url = Url::fromUri('internal:/twig-polymer/')->toString(). $filename;
        /*} else {
          list($theme, $element) = explode('/', $filename);
          $polymer_url = \Drupal::url("twig_polymer.remap_url.theme_specified", ["element" => $element, "theme" => $theme]);
        }*/
        return $polymer_url;
      }),
      new Twig_SimpleFunction($this->config->get('twig_tag').'_'.'encode', function($str) {
        $arr = array(
          "data" => $str,
        );
        return json_encode($str);
      }),
    );
  }

  /**
   * Returns a list of extension filters
   *
   * @return array
   */
  public function getFilters() {
    return $this->filters;
  }

  /**
   * Returns the token parser instances to add to the existing list.
   *
   * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
   */
  public function getTokenParsers() {
    return array(
      new PolymerTokenParser(),
    );
  }

  /**
   * Returns a list of global variables to add to the existing list.
   *
   * @return array
   */
  public function getGlobals() {
    return [
            "polymer" => [
                "configuration" => new PolymerConfig(),
            ]
        ];
  }

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName() {
    return "polymer_extension";
  }
}
