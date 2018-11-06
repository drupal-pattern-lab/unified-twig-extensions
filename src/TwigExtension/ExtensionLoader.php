<?php

namespace Drupal\unified_twig_ext\TwigExtension;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Loads twig customizations from a dist directory.
 */
class ExtensionLoader {

  /**
   * Plugin objects.
   *
   * @var array
   */
  protected static $objects = [];

  /**
   * Loads a singleton registry of plugin objects.
   */
  public static function init() {
    if (!self::$objects) {
      static::loadAll('filters');
      static::loadAll('functions');
      static::loadAll('tags');
    }
  }

  /**
   * Gets all plugin objects of a given type.
   *
   * @param string $type
   *   The plugin type to load.
   *
   * @return array
   *   An array of loaded objects to be provided by the twig extension for a
   *   given type.
   */
  public static function get($type) {
    return !empty(self::$objects[$type]) ? self::$objects[$type] : [];
  }

  /**
   * Loads all plugins of a given type.
   *
   * This should be called once per $type.
   *
   * @param string $type
   *   The type to load all plugins for.
   */
  protected static function loadAll($type) {
    $theme = \Drupal::config('system.theme')->get('default');
    $themeLocation = drupal_get_path('theme', $theme);
    $themePath = DRUPAL_ROOT . '/' . $themeLocation . '/';
    // Iterates recursively through theme to find Twig extensions.
    $dir = new RecursiveDirectoryIterator($themePath);
    $ite = new RecursiveIteratorIterator($dir);
    // Searches for Twig extensions of $type.
    // Excludes plugin names starting with: ".", "_", and "pl_".
    $pattern = '/.*\/_twig-components\/' . $type . '\/(?!(?:\.|_|pl_)).*\.php/';
    $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
    $files = array_keys(iterator_to_array($files));
    // Loads each matching Twig extension that was found.
    foreach ($files as $file) {
      static::load($type, $file);
    }
  }

  /**
   * Loads a specific plugin instance.
   *
   * @param string $type
   *   The type of the plugin to be loaded.
   * @param string $file
   *   The fully qualified path of the plugin to be loaded.
   */
  protected static function load($type, $file) {
    include $file;
    switch ($type) {
      case 'filters':
        self::$objects['filters'][] = $filter;
        break;

      case 'functions':
        self::$objects['functions'][] = $function;
        break;

      case 'tags':
        if (preg_match('/^([^\.]+)\.tag\.php$/', basename($file), $matches)) {
          $class = "Project_{$matches[1]}_TokenParser";
          if (class_exists($class)) {
            self::$objects['parsers'][] = new $class();
          }
        }
        break;
    }
  }

}
