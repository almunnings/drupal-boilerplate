<?php

/**
 * @file
 * Drupal all environment configuration file.
 *
 * This file should contain all settings.php configurations that are
 * needed by all environments.
 */

// Import sensible Drupal defaults.
include __DIR__ . "/default.settings.php";

// Path config.
$settings['config_sync_directory'] = getenv('DRUPAL_CONFIG_DIR') ?: '../config/sync';
$settings['file_public_path'] = getenv('DRUPAL_PUBLIC_DIR') ?: 'sites/default/files';
$settings['file_private_path'] = getenv('DRUPAL_PRIVATE_DIR') ?: 'sites/default/files/private';
$config['system.file']['path']['temporary'] = getenv('DRUPAL_TMP_DIR') ?: getenv('TMP');

// Database connection.
$databases['default']['default'] = [
  'driver' => 'mysql',
  'database' => getenv('MARIADB_DATABASE') ?: 'drupal10',
  'username' => getenv('MARIADB_USERNAME') ?: 'drupal10',
  'password' => getenv('MARIADB_PASSWORD') ?: 'drupal10',
  'host' => getenv('MARIADB_HOST') ?: 'database',
  'port' => 3306,
  'prefix' => '',
  'init_commands' => [
    'isolation_level' => 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED',
  ],
];

// Enable all reverse proxies.
$settings['reverse_proxy'] = TRUE;

// Trusted Host Patterns.
$settings['trusted_host_patterns'][] = '.*';

// Hash salt.
$settings['hash_salt'] = hash('sha256', getenv('HASH_SALT') ?: getenv('MARIADB_HOST'));

/**
 * Default production config.
 */

// Disable error display.
$config['system.logging']['error_level'] = 'hide';

// Page performance defaults.
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;
$config['system.performance']['cache']['page']['max_age'] = 600;

/**
 * Load services definition file.
 *
 * Services.development.yml is loaded for lando & development environments.
 * settings.development.php is loaded for lando & development environments.
 *
 * services.local.php is loaded last for all envs. Do not commit this file.
 * settings.local.php is loaded last for all envs. Do not commit this file.
 */

$envs = ['local'];

if (getenv('ENVIRONMENT_TYPE') === 'development' || getenv('LANDO')) {
  array_unshift($envs, 'development');
}

foreach ($envs as $env) {
  if (file_exists(__DIR__ . '/settings.' . $env . '.php')) {
    include __DIR__ . '/settings.' . $env . '.php';
  }

  if (file_exists(__DIR__ . '/services.' . $env . '.yml')) {
    $settings['container_yamls'][] = __DIR__ . '/services.' . $env . '.yml';
  }
}
