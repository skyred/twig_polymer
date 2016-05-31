# Twig Polymer Module for Drupal 8
Making Polymer (and Web Components) work more easily with Twig.

## What it does
 * Loads polyfill `webcomponents-lite` on pages that uses Polymer
 * Provides a Twig extension: (Based on https://github.com/headzoo/polymer-bundle)
   * a Twig tag {% polymer element %} for wrapping Polymer element
   * a Twig function polymer_asset() for converting asset URL
 * Provides an endpoint for loading Polymer elements from the browser.

## Installation
 - Download and unzip [polymer-first-elements.zip](https://github.com/googlecodelabs/polymer-first-elements/releases/download/v1.0/polymer-first-elements.zip) and place the `bower_components` folder under `Drupal root directory /libraries/`. Create the folder if it does not exsit.
 - Enable this module

## Usage

### Polymer Extension
In any template, use `{{ polymer element "name" }}` to define a Polymer element.

For example,
```
{% polymer element 'node-element' %}
<template>
    <article>
        <h2>
          <a href="{{ url }}" rel="bookmark"><content select=".label"></content></a>
        </h2>
    </article>
</template>
<script>
  Polymer({
    is: 'node-element',
    properties: {
      url: String,
    }
  });
</script>
{% endpolymer %}
```
will be rendered as:
```
<import src="polymer/polymer.html">
<polymer-element name="node-element" >
<template>
    <article>
        <h2>
          <a href="{{ url }}" rel="bookmark"><content select=".label"></content></a>
        </h2>
    </article>
</template>
<script>
  Polymer({
    is: 'node-element',
    properties: {
      url: String,
    }
  });
</script>
</polymer-element>
```
