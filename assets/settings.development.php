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
  $settings['skip_permissions_hardening'] = TRUE;
  $settings['cache']['bins']['render'] = 'cache.backend.null';
  $settings['cache']['bins']['page'] = 'cache.backend.null';
  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
}
else {
  // Shield credentials.
  $config['shield.settings']['shield_enable'] = TRUE;
  $config['shield.settings']['credentials']['shield']['user'] = getenv('SHIELD_USERNAME');
  $config['shield.settings']['credentials']['shield']['pass'] = getenv('SHIELD_PASSWORD');
  $config['shield.settings']['print'] = 'Shield.';
}
