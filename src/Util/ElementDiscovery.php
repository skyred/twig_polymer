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
    $thisTheme = $this->themeInitialization->getActiveThemeByName($themeName);
    $baseThemes = array_keys($thisTheme->getBaseThemes());
    return (count($baseThemes) > 0) ? $baseThemes[0] : FALSE;
  }

  protected function findInPath($path, $filename) {
    $matched_files = \file_scan_directory($path, '/' . preg_quote($filename) . '/');
    if (count($matched_files) > 0) {
      return array_keys($matched_files)[0];
    }
    else {
      return FALSE;
    }
  }

  protected function fileExists($path) {
    return is_file($path) ? $path : FALSE;
  }


  public function getElementFilesystemPath($filename, $theme = FALSE) {

    // Order of discovery:
    // 1. Custom element in current theme (/themes/my-theme/my-elements)
    // 2. 3rd Party element in current theme (/themes/my-theme/bower_components)
    // 3. Custom element in parent theme (/themes/base-theme/my-elements)
    // 4. 3rd Party element in parent theme (/themes/base-theme/bower_components)
    // 5. Repeat 3&4 until there is no parent theme
    // 6. Global 3rd party components (/libraries/bower_components)

    if ($theme == FALSE) {
      // Global (6)
      $globalLibrary = $this->root . '/libraries/bower_components/';
      $tmp = $this->fileExists($globalLibrary . $filename);
      return ($tmp) ? $tmp : FALSE;
    }
    else {
      // TODO: needs test when drupal is installed in a non-root directory.
      $themePath = $this->root . '/' . drupal_get_path('theme', $theme);

      $customElementsFolder = $themePath . '/my-elements/';
      $tmp = $this->fileExists($customElementsFolder . $filename);
      if ($tmp) {
        return $tmp;
      }
      else {
        $bowerElementsFolder = $themePath . '/bower_components/';
        $tmp = $this->fileExists($bowerElementsFolder . $filename);
        if ($tmp) {
          return $tmp;
        }
        else {
          return $this->getElementFilesystemPath($filename, $this->getParentTheme($theme));
        }
      }
    }
  }

}
