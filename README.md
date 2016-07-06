# Twig Polymer Extension for Drupal 8
Making Polymer (and Web Components) work more easily with Twig.

## What it does
 * Loads polyfill `webcomponents-lite` on pages that uses Polymer.
 * Provides an endpoint for serving Polymer elements (equivalent to `poly-serve`).

### Polymer Endpoint

 - [Temporarily disabled] Specify theme to search: `\twig-polymer\{theme}\{element}`. e.g. `http://localhost/twig-polymer/@polymer/node-full.html`
 - Not specifing theme to search: `\twig-polymer\{element}`. e.g. `http://localhost/twig-polymer/node-full.html`. Defaults to current theme, fallback to base themes.

### Theme Fallback
If an element is not found in a theme, its parent themes are searched. If still not found, the global library folder will be searched.

For details of the priority of element discovery, see: https://github.com/ztl8702/twig_polymer/blob/dev/src/Util/ElementDiscovery.php#L58

## Installation
 - Download this module, go to its directory and run `bower install`.
 - Enable this module.
