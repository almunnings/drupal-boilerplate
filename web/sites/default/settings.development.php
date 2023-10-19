<?php

/**
 * @file
 * Development environment settings.
 */

// Local development mode.
$local = getenv('LANDO') || getenv('IS_DDEV_PROJECT');

// Disable page caching.
if ($local) {
  $config['system.performance']['cache']['page']['max_age'] = 0;
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
}

// Debugging.
$config['system.logging']['error_level'] = 'verbose';

// Allow self-signed SSL certificates.
$settings['http_client_config']['verify'] = FALSE;

// Don't chmod settings and uploads.
$settings['skip_permissions_hardening'] = TRUE;

// Ignore the simple_oauth key permissions check.
$settings['simple_oauth.key_permissions_check'] = FALSE;
