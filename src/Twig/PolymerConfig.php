<?php
namespace Drupal\twig_polymer\Twig;

class PolymerConfig {

    public function getTemplate(string $filename) {
        // syntax defined in Drupal\Core\Template\Loader\FilesystemLoader
        return '@twig_polymer/' . $filename;
    }
}
