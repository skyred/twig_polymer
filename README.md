# Twig Polymer Extension for Drupal 8
Making it easier to use Polymer elements in Drupal's Twig templates.

## What problem does it solve?
When using Polymer elements, we need a static server to serve the files (`polymer.html`, `paper-button.html`, `your-custom-element.html`, etc.) You may put them in a static folder of your website, but that is hard to manage and you need to write awkwardly long URL in your templates to reference. Also, you cannot use relative URLs for your Polymer elements. (`<link rel='import' href="../polymer/polymer.html">` means different files on `/`, `/node/2`, `/taxonomy/10`)

Twig Polymer Extension allows you to keep all the Polymer elements (both downloaded and your custom ones) you use in your theme folder, using Bower to manage dependencies so that they can be tracked in Git/SVN. It adheres to Polymer's [element package layout](https://www.polymer-project.org/1.0/docs/tools/polymer-cli#element-project-layout) so all dependencies between Polymer elements will not break. This module also provides a simple Twig helper that you can use to reference your Polymer elements without figuring out what the URL should be.

## Technical details
### What it does
 * Loads polyfill `webcomponents-lite` on pages that use Polymer.
 * Provides an endpoint for serving Polymer elements (equivalent to `poly-serve`).
   - Access your elements : `\twig-polymer\{element-relative-path}`. e.g. `http://localhost/twig-polymer/paper-button/paper-button.html`. Defaults to current theme, fallback to base themes.
   - Theme Fallback
     - If an element is not found in a theme, its parent themes are searched. If still not found, the global library folder will be searched. For details of the priority of element discovery, see: https://github.com/ztl8702/twig_polymer/blob/dev/src/Util/ElementDiscovery.php#L58
     - This allows common elements to be shared among your themes.
 * Twig helper: `{% polymer import "paper-button/paper-button" %}`

## Usage
 - Download and enable this module, and run `bower install` in the module folder. 
 - In your theme folder, first run `bower init`, then run `bower install --save your-desired-element` to install 3rd party Polymer elements you like.
 - In your theme folder, place your custom elements in `/my-elements` folder. `/bower_components` and `/my-elements` folder will be "virtually combined" to allow seamless access to both custom and 3rd party elements. 
 - Optional: add `bower_components` to your `.gitignore` file.


## Drupal Console command `polymer:element`
