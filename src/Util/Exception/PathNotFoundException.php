<?php
namespace Drupal\twig_polymer\Util\Exception;

use Drupal\twig_polymer\Exception\PolymerException;

/**
 * Thrown when trying to resolve a path that does not exist.
 */
class PathNotFoundException
    extends PolymerException {}
