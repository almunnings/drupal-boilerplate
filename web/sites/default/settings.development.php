<?php

/**
 * @file
 * Development environment settings.
 */

// Debugging.
$config['system.logging']['error_level'] = 'verbose';

// Disable page caching.
$config['system.performance']['cache']['page']['max_age'] = 0;

// Local development.
if (getenv('LANDO')) {
  // Don't chmod settings and uploads.
  $settings['skip_permissions_hardening'] = TRUE;

  // Use the null cache backend enabled in services.development.yml.
  $settings['cache']['bins']['render'] = 'cache.backend.null';
  $settings['cache']['bins']['page'] = 'cache.backend.null';
  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

  // Disable all preprocessing of js/css.
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
}

// Another development environment. Eg staging.
if (!getenv('LANDO')) {
  // Shield credentials.
  $config['shield.settings']['shield_enable'] = getenv('SHIELD_USERNAME') && getenv('SHIELD_PASSWORD');
  $config['shield.settings']['credentials']['shield']['user'] = getenv('SHIELD_USERNAME');
  $config['shield.settings']['credentials']['shield']['pass'] = getenv('SHIELD_PASSWORD');
  $config['shield.settings']['print'] = 'Login';
}
