<?php

/**
 * @file
 * Memcache settings.
 */

use Drupal\Core\Installer\InstallerKernel;

$memcache_exists = class_exists('Memcache', FALSE);
$memcached_exists = class_exists('Memcached', FALSE);
$attempting_install = InstallerKernel::installationAttempted();

$memcache_server = getenv('MEMCACHE_SERVER') ?: 'memcached';
$memcache_port = getenv('MEMCACHE_PORT') ?: 11211;

if (!$attempting_install && ($memcache_exists || $memcached_exists)) {
  $class_loader->addPsr4('Drupal\\memcache\\', 'modules/contrib/memcache/src');

  // Add extra services.
  $settings['container_yamls'][] = __DIR__ . '/memcache.services.yml';

  // Set default cache to memcached.
  $settings['cache']['default'] = 'cache.backend.memcache';

  // Generic memcache settings.
  $settings['memcache']['bins'] = ['default' => 'default'];
  $settings['memcache']['key_prefix'] = '';
  $settings['memcache']['servers'] = [
    $memcache_server . ':' . $memcache_port => 'default',
  ];

  // Define custom bootstrap container definition
  // to use Memcache for cache.container.
  $settings['bootstrap_container_definition'] = [
    'parameters' => [],
    'services' => [
      // Dependencies.
      'settings' => [
        'class' => 'Drupal\Core\Site\Settings',
        'factory' => 'Drupal\Core\Site\Settings::getInstance',
      ],
      'memcache.settings' => [
        'class' => 'Drupal\memcache\MemcacheSettings',
        'arguments' => ['@settings'],
      ],
      'memcache.factory' => [
        'class' => 'Drupal\memcache\Driver\MemcacheDriverFactory',
        'arguments' => ['@memcache.settings'],
      ],
      'memcache.timestamp.invalidator.bin' => [
        'class' => 'Drupal\memcache\Invalidator\MemcacheTimestampInvalidator',
        // Adjust tolerance factor as appropriate if not localhost.
        'arguments' => ['@memcache.factory', 'memcache_bin_timestamps', 0.001],
      ],
      'memcache.timestamp.invalidator.tag' => [
        'class' => 'Drupal\memcache\Invalidator\MemcacheTimestampInvalidator',
        // Remember to update your main service definition in sync with this!
        // Adjust tolerance factor as appropriate if not localhost.
        'arguments' => ['@memcache.factory', 'memcache_tag_timestamps', 0.001],
      ],
      'memcache.backend.cache.container' => [
        'class' => 'Drupal\memcache\DrupalMemcacheInterface',
        'factory' => ['@memcache.factory', 'get'],
        // Actual cache bin to use for the container cache.
        'arguments' => ['container'],
      ],
      // Define a custom cache tags invalidator for the bootstrap container.
      'cache_tags_provider.container' => [
        'class' => 'Drupal\memcache\Cache\TimestampCacheTagsChecksum',
        'arguments' => ['@memcache.timestamp.invalidator.tag'],
      ],
      'cache.container' => [
        'class' => 'Drupal\memcache\MemcacheBackend',
        'arguments' => [
          'container',
          '@memcache.backend.cache.container',
          '@cache_tags_provider.container',
          '@memcache.timestamp.invalidator.bin',
        ],
      ],
    ],
  ];
}
