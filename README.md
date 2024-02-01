# Drupal Boilerplate

[![Build](https://github.com/almunnings/drupal-boilerplate/actions/workflows/build.yml/badge.svg?branch=main)](https://github.com/almunnings/drupal-boilerplate/actions/workflows/build.yml)

## Local environment (Lando)

If you plan to use a HMR theme via the lando proxy, you need to trust your [Lando SSL certificates](https://docs.lando.dev/core/v3/security.html#trusting-the-ca).

```bash
lando start
lando drush uli
```

## Local environment (DDEV)

```bash
ddev start
ddev drush uli
```

## How can I apply patches to downloaded modules?

If you need to apply patches (depending on the project being modified, a pull
request is often a better solution), you can do so with the
[composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module foobar insert the patches section in the extra
section of composer.json:

```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL to patch"
        }
    }
}
```
