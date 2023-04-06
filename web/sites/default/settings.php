<?php

/**
 * @file
 * Drupal all environment configuration file.
 *
 * This file contains default configurations for all environments.
 *
 * All environments:
 * - settings.php
 * - services.yml
 *
 * Production (Optional):
 * - settings.production.php
 * - services.production.yml
 *
 * Development & Lando:
 * - settings.development.php
 * - services.development.yml
 */

// Import sensible Drupal defaults.
include __DIR__ . "/default.settings.php";

// Set to development in Lando or production by default.
if (!getenv('ENVIRONMENT_TYPE')) {
  putenv('ENVIRONMENT_TYPE=' . (getenv('LANDO') ? 'development' : 'production'));
}

// Quick debug.
// putenv('ENVIRONMENT_TYPE=production');.
// Path config.
$settings['config_sync_directory'] = getenv('DRUPAL_CONFIG_DIR') ?: '../config/sync';
$settings['file_public_path'] = getenv('DRUPAL_PUBLIC_DIR') ?: 'sites/default/files';
$settings['file_private_path'] = getenv('DRUPAL_PRIVATE_DIR') ?: 'sites/default/files/private';
$config['system.file']['path']['temporary'] = getenv('DRUPAL_TMP_DIR') ?: getenv('TMP');

// Enable all reverse proxies.
$settings['reverse_proxy'] = TRUE;

// Trusted Host Patterns.
$trusted_hosts = getenv('DRUPAL_TRUSTED_HOSTS') ?: '.*';
$settings['trusted_host_patterns'] = array_map('trim', explode(',', $trusted_hosts));

// Hash salt.
$settings['hash_salt'] = hash('sha256', getenv('DRUPAL_HASH_SALT') ?: getenv('MARIADB_HOST'));

// Disable error display.
$config['system.logging']['error_level'] = 'hide';

// Page performance. 10 min page cache.
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;
$config['system.performance']['cache']['page']['max_age'] = 600;

// Database connection.
$databases['default']['default'] = [
  'driver' => 'mysql',
  'database' => getenv('MARIADB_DATABASE') ?: 'drupal10',
  'username' => getenv('MARIADB_USERNAME') ?: 'drupal10',
  'password' => getenv('MARIADB_PASSWORD') ?: 'drupal10',
  'host' => getenv('MARIADB_HOST') ?: 'database',
  'port' => getenv('MARIADB_PORT') ?: 3306,
  'prefix' => '',
  'init_commands' => [
    'isolation_level' => 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED',
  ],
];

// Environment specific config.
// Add a local config in at the end for good measure.
foreach ([getenv('ENVIRONMENT_TYPE'), 'local'] as $env) {
  if (file_exists(__DIR__ . '/settings.' . $env . '.php')) {
    include __DIR__ . '/settings.' . $env . '.php';
  }

  if (file_exists(__DIR__ . '/services.' . $env . '.yml')) {
    $settings['container_yamls'][] = __DIR__ . '/services.' . $env . '.yml';
  }
}
