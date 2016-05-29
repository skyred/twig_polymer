# Polymer Twig Extension for Drupal 8

Based on https://github.com/headzoo/polymer-bundle

## What it does
 - Loads polyfill webcomponents-lite on pages that uses it
 - Provides a Twig tag {% polymer element %} for wrapping Polymer element
 - Provides a Twig function polymer_asset() for converting asset URL

## Installation
 - Download [polymer-first-elements.zip](https://github.com/googlecodelabs/polymer-first-elements/releases/download/v1.0/polymer-first-elements.zip) and place the `bower_components` folder it under `Drupal root directory /libraries/`. Create the folder if it does not exsit.
 - Enable this module
