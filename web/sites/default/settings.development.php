<?php

/**
 * @file
 * Development environment settings.
 */

// Disable caching locally.
$disable_cache = getenv('ENVIRONMENT_LOCAL') === 'true';

// Enable shield for development mode non-local.
$enable_shield = getenv('ENVIRONMENT_LOCAL') !== 'true';

// Debugging.
$config['system.logging']['error_level'] = 'verbose';

// Allow self-signed SSL certificates.
$settings['http_client_config']['verify'] = FALSE;

// Don't chmod settings and uploads.
$settings['skip_permissions_hardening'] = TRUE;

// Disable page caching.
if ($disable_cache) {
  $config['system.performance']['cache']['page']['max_age'] = 0;

  // Use the null cache backend enabled in services.development.yml.
  $settings['cache']['bins']['render'] = 'cache.backend.null';
  $settings['cache']['bins']['page'] = 'cache.backend.null';
  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

  // Disable all preprocessing of js/css.
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
}

// Shield credentials.
if ($enable_shield) {
  $config['shield.settings']['shield_enable'] = getenv('SHIELD_USERNAME') && getenv('SHIELD_PASSWORD');
  $config['shield.settings']['credentials']['shield']['user'] = getenv('SHIELD_USERNAME');
  $config['shield.settings']['credentials']['shield']['pass'] = getenv('SHIELD_PASSWORD');
  $config['shield.settings']['print'] = 'Login';
}
