<?php

/**
 * @file
 * Development environment settings.
 */

// Disable caching locally.
$is_local_dev = getenv('ENVIRONMENT_LOCAL') === 'true';

// Disable page caching.
if ($is_local_dev) {
  $config['system.performance']['cache']['page']['max_age'] = 0;
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
}

// Enable shield for development mode non-local.
if (!$is_local_dev) {
  $config['shield.settings']['shield_enable'] = getenv('SHIELD_USERNAME') && getenv('SHIELD_PASSWORD');
  $config['shield.settings']['credentials']['shield']['user'] = getenv('SHIELD_USERNAME');
  $config['shield.settings']['credentials']['shield']['pass'] = getenv('SHIELD_PASSWORD');
  $config['shield.settings']['print'] = 'Login';
}

// Debugging.
$config['system.logging']['error_level'] = 'verbose';

// Allow self-signed SSL certificates.
$settings['http_client_config']['verify'] = FALSE;

// Don't chmod settings and uploads.
$settings['skip_permissions_hardening'] = TRUE;

// Ignore the simple_oauth key permissions check.
$settings['simple_oauth.key_permissions_check'] = FALSE;
