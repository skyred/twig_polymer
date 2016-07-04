<?php
/**
 * @file
 * Contains Drupal\twig_polymer\Util\ElementDiscovery.
 */

namespace Drupal\twig_polymer\Util;

use Drupal\Core\Theme\ThemeInitializationInterface;

/**
 * Element Discovery Service
 */
class ElementDiscovery {
  protected $root;

  /**
   * ElementDiscovery constructor.
   *
   * @param string $root
   *   The app root.
   * @param \Drupal\Core\Theme\ThemeInitializationInterface $theme_initialization
   */
  public function __construct($root, ThemeInitializationInterface $theme_initialization) {
    $this->root = $root;
    $this->themeInitialization = $theme_initialization;
  }

  protected function getThemePath($themeName) {
    return $this->root . drupal_get_path('theme', $themeName);
  }

  /**
   * @param $themeName
   *   The theme name for query.
   *
   * @return string|bool
   *   The parent theme name, or FALSE if not found.
   */
  protected function getParentTheme($themeName) {
    return $this->themeInitialization->getActiveThemeByName($themeName)->getBaseThemes()[0];
  }

  public function getElementInternalPath($filename, $themename = FALSE) {

    // Order of discovery:
    // 1. Custom element in current theme (/themes/my-theme/my-elements)
    // 2. 3rd Party element in current theme (/themes/my-theme/bower_components)
    // 3. Custom element in parent theme (/themes/base-theme/my-elements)
    // 4. 3rd Party element in parent theme (/themes/base-theme/bower_components)
    // 5. Repeat 3&4 until there is no parent theme
    // 6. Global 3rd party components (/libraries/bower_components)

    if ($themename === FALSE) {
      // Global (6)
      $path = $this->root . '/libraries/bower_components/' . $filename;
      return (is_file($path)) ? $path : FALSE;
    }
    else {
      // TODO: needs test when drupal is installed in a non-root directory.
      $themePath = $this->root . drupal_get_path('theme', $themename);

      $customElement = $themePath . '/my-elements/' . $filename);
      if (is_file($customElement)) {
        return $customElement;
      }
      else {
        $bowerElement = $themePath . '/my-elements/' . $filename);
        if (is_file($bowerElement)) {
          return $bowerElement;
        }
        else {
          return $this->getElementInternalPath($filename, $this->getParentTheme($themename));
        }
      }
    }
  }

}
