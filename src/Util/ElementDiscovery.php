<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Util\ElementDiscovery.
 */

namespace Drupal\twig_polymer\Util;

/**
 * Element Discovery Service
 */
class ElementDiscovery {
  public function __construct() {

  }

  public function getElementInternalPath($filename, $themename = '') {

    // Order of discovery:
    // 1. Custom element in current theme (/themes/my-theme/my-element)
    // 2. 3rd Party element in current theme (/themes/my-theme/bower_components)
    // 3. Custom element in parent theme (/themes/base-theme/my-element)
    // 4. 3rd Party element in parent theme (/themes/base-theme/bower_components)
    // 5. Repeat 3&4 until there is no parent theme
    // 6. Global 3rd party components (/libraries/bower_components)

    if (substr($filename, -5) === ".html") {
    // if filename ends with ".html" we load the element from vendor library.
      return base_path() . drupal_get_path('module', 'twig_polymer'). '/' . $this->config->get("path_components") . '/'. $filename;
    } else {
      $polymer_url = \Drupal::url("twig_polymer.get_element_current_theme", ["elementname" => $filename]);
      return $polymer_url;
    }
  }


}
