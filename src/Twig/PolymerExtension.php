<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Twig\PolymerExtension.
 */

namespace Drupal\twig_polymer\Twig;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Render\AttachmentsInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\RenderableInterface;
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
  private $renderer;
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
    $this->renderer = \Drupal::service('renderer');
  }

  /**
   * Returns a list of extension functions
   *
   * @return array
   */
  public function getFunctions() {
    return array(
      new Twig_SimpleFunction($this->config->get('twig_tag').'_'.'asset', function ($filename) {
        $polymer_url = Url::fromUri('internal:/twig-polymer/')->toString(). $filename;
        return $polymer_url;
      }),
      new Twig_SimpleFunction($this->config->get('twig_tag').'_'.'encode', function($str) {
        $arr = array(
          "data" => $str,
        );
        return json_encode($str);
      }),
      new Twig_SimpleFunction($this->config->get('twig_tag').'_'.'render_var',  array($this, 'renderVar')),
      new Twig_SimpleFunction($this->config->get('twig_tag').'_'.'rendered_array',  array($this, 'renderedArray')),
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

  /**
   * ------------------------------------
   * Copied from \TwigExtension.
   * ------------------------------------
   */

  /**
   * Bubbles Twig template argument's cacheability & attachment metadata.
   *
   * For example: a generated link or generated URL object is passed as a Twig
   * template argument, and its bubbleable metadata must be bubbled.
   *
   * @see \Drupal\Core\GeneratedLink
   * @see \Drupal\Core\GeneratedUrl
   *
   * @param mixed $arg
   *   A Twig template argument that is about to be printed.
   *
   * @see \Drupal\Core\Theme\ThemeManager::render()
   * @see \Drupal\Core\Render\RendererInterface::render()
   */
  protected function bubbleArgMetadata($arg) {
    // If it's a renderable, then it'll be up to the generated render array it
    // returns to contain the necessary cacheability & attachment metadata. If
    // it doesn't implement CacheableDependencyInterface or AttachmentsInterface
    // then there is nothing to do here.
    if ($arg instanceof RenderableInterface || !($arg instanceof CacheableDependencyInterface || $arg instanceof AttachmentsInterface)) {
      return;
    }

    $arg_bubbleable = [];
    BubbleableMetadata::createFromObject($arg)
      ->applyTo($arg_bubbleable);

    $this->renderer->render($arg_bubbleable);
  }

  /**
   * Wrapper around render() for twig printed output.
   *
   * If an object is passed which does not implement __toString(),
   * RenderableInterface or toString() then an exception is thrown;
   * Other objects are casted to string. However in the case that the
   * object is an instance of a Twig_Markup object it is returned directly
   * to support auto escaping.
   *
   * If an array is passed it is rendered via render() and scalar values are
   * returned directly.
   *
   * @param mixed $arg
   *   String, Object or Render Array.
   *
   * @throws \Exception
   *   When $arg is passed as an object which does not implement __toString(),
   *   RenderableInterface or toString().
   *
   * @return mixed
   *   The rendered output or an Twig_Markup object.
   *
   * @see render
   * @see TwigNodeVisitor
   */
  public function renderVar($arg) {
    // Check for a numeric zero int or float.
    if ($arg === 0 || $arg === 0.0) {
      return 0;
    }

    // Return early for NULL and empty arrays.
    if ($arg == NULL) {
      return NULL;
    }

    // Optimize for scalars as it is likely they come from the escape filter.
    if (is_scalar($arg)) {
      return $arg;
    }

    if (is_object($arg)) {
      $this->bubbleArgMetadata($arg);
      if ($arg instanceof RenderableInterface) {
        $arg = $arg->toRenderable();
      }
      elseif (method_exists($arg, '__toString')) {
        return (string) $arg;
      }
      // You can't throw exceptions in the magic PHP __toString() methods, see
      // http://php.net/manual/language.oop5.magic.php#object.tostring so
      // we also support a toString method.
      elseif (method_exists($arg, 'toString')) {
        return $arg->toString();
      }
      else {
        throw new \Exception('Object of type ' . get_class($arg) . ' cannot be printed.');
      }
    }

    // This is a render array, with special simple cases already handled.
    // Early return if this element was pre-rendered (no need to re-render).
    if (isset($arg['#printed']) && $arg['#printed'] == TRUE && isset($arg['#markup']) && strlen($arg['#markup']) > 0) {
      return $arg['#markup'];
    }
    $arg['#printed'] = FALSE;
    return $this->renderer->renderRoot($arg);
  }

  /**
   * Wrapper around render() for twig printed output.
   *
   * If an object is passed which does not implement __toString(),
   * RenderableInterface or toString() then an exception is thrown;
   * Other objects are casted to string. However in the case that the
   * object is an instance of a Twig_Markup object it is returned directly
   * to support auto escaping.
   *
   * If an array is passed it is rendered via render() and scalar values are
   * returned directly.
   *
   * @param mixed $arg
   *   String, Object or Render Array.
   *
   * @throws \Exception
   *   When $arg is passed as an object which does not implement __toString(),
   *   RenderableInterface or toString().
   *
   * @return mixed
   *   The rendered output or an Twig_Markup object.
   *
   * @see render
   * @see TwigNodeVisitor
   */
  public function renderedArray($arg) {
    // Check for a numeric zero int or float.
    if ($arg === 0 || $arg === 0.0) {
      return 0;
    }

    // Return early for NULL and empty arrays.
    if ($arg == NULL) {
      return NULL;
    }

    // Optimize for scalars as it is likely they come from the escape filter.
    if (is_scalar($arg)) {
      return $arg;
    }

    if (is_object($arg)) {
      $this->bubbleArgMetadata($arg);
      if ($arg instanceof RenderableInterface) {
        $arg = $arg->toRenderable();
      }
      elseif (method_exists($arg, '__toString')) {
        return (string) $arg;
      }
      // You can't throw exceptions in the magic PHP __toString() methods, see
      // http://php.net/manual/language.oop5.magic.php#object.tostring so
      // we also support a toString method.
      elseif (method_exists($arg, 'toString')) {
        return $arg->toString();
      }
      else {
        throw new \Exception('Object of type ' . get_class($arg) . ' cannot be printed.');
      }
    }

    // This is a render array, with special simple cases already handled.
    // Early return if this element was pre-rendered (no need to re-render).
    if (isset($arg['#printed']) && $arg['#printed'] == TRUE && isset($arg['#markup']) && strlen($arg['#markup']) > 0) {
      return $arg['#markup'];
    }
    $arg['#printed'] = FALSE;
    $this->renderer->renderRoot($arg);
    return $arg;
  }

}
