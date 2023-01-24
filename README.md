# Drupal Boilerplate

## Requirements

- [Lando](https://docs.lando.dev/basics/installation.html#system-requirements)

## Local environment setup

```bash
lando start
```

## Building theme locally

```bash
lando theme install
lando theme run dev
```

And to build for production

```bash
lando theme run build
```

## Project specific documentation

## FAQ

### How do I override Lando locally?

Create `/.lando.local.yml` file for local development.

### How can I optimize my Lando?

Take a look at [Lando performance](https://docs.lando.dev/config/performance.html#configuration)

### What does the template do?

When installing the given `composer.json` some tasks are taken care of:

- Drupal will be installed in the `web` directory.
- Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`,
  instead of the one provided by Drupal (`web/vendor/autoload.php`).
- Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib/`
- Themes (packages of type `drupal-theme`) will be placed in `web/themes/contrib/`
- Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib/`
- Creates the `web/sites/default/files`-directory.
- Latest version of drush is installed locally for use at `vendor/bin/drush`.

### Should I commit the contrib modules I download?

Composer recommends **no**. They provide [argumentation against but also
workarounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### How can I apply patches to downloaded modules?

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
