# Twig Polymer Extension for Drupal 8
Making it easier to use Polymer elements in Drupal's Twig templates.

## What problem does it solve?
When using Polymer elements, we need a static server to serve the files (`polymer.html`, `paper-button.html`, `your-custom-element.html`, etc.) You may put them in a static folder of your website, but that is hard to manage and you need to write awkwardly long URL in your templates to reference. Also, you cannot use relative URLs for your Polymer elements. (`<link rel='import' href="../polymer/polymer.html">` means different files on `/`, `/node/2`, `/taxonomy/10`)

Twig Polymer Extension allows you to keep all the Polymer elements (both downloaded and your custom ones) you use in your theme folder, using Bower to manage dependencies so that they can be managed in Git/SVN. This module also provides a simple Twig helper that you can use to reference your Polymer elements without figuring out what the URL should be.

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
