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

// Set to development in Lando or production by default.
if (!getenv('ENVIRONMENT_TYPE')) {
  putenv('ENVIRONMENT_TYPE=' . (getenv('LANDO') ? 'development' : 'production'));
}

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

// Page performance defaults. 10 mins.
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

/**
 * Load services definition file.
 *
 * Services.development.yml is loaded for lando & development environments.
 * settings.development.php is loaded for lando & development environments.
 *
 * services.local.php is loaded last for all envs. Do not commit this file.
 * settings.local.php is loaded last for all envs. Do not commit this file.
 */

foreach ([getenv('ENVIRONMENT_TYPE'), 'local'] as $env) {
  if (file_exists(__DIR__ . '/settings.' . $env . '.php')) {
    include __DIR__ . '/settings.' . $env . '.php';
  }

  if (file_exists(__DIR__ . '/services.' . $env . '.yml')) {
    $settings['container_yamls'][] = __DIR__ . '/services.' . $env . '.yml';
  }
}
