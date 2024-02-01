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

// Use mailhog/mailpit lando/ddev for SMTP service.
$mail_addr = getenv('MH_SENDMAIL_SMTP_ADDR') ?: getenv('MH_SMTP_BIND_ADDR');
if ($mail_addr) {
  [$host, $port] = explode(':', $mail_addr);
  $config['symfony_mailer.mailer_transport.smtp']['configuration']['host'] = $host;
  $config['symfony_mailer.mailer_transport.smtp']['configuration']['port'] = $port;
  $config['symfony_mailer.mailer_transport.smtp']['configuration']['query']['verify_peer'] = FALSE;
}
