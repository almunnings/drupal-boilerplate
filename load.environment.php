<?php

/**
 * @file
 * This file is included very early. Load env.
 *
 * @see composer.json (autoload.files)
 * @see https://getcomposer.org/doc/04-schema.md#files
 */

use Dotenv\Dotenv;

$envs = [
  '.env.defaults',
  '.env',
];

foreach ($envs as $env) {
  $dotenv = Dotenv::createUnsafeImmutable(__DIR__, $env);
  $dotenv->safeLoad();
}
