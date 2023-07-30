# Drupal Boilerplate

[![Lando Start](https://github.com/almunnings/drupal-boilerplate/actions/workflows/lando-start.yml/badge.svg?branch=main)](https://github.com/almunnings/drupal-boilerplate/actions/workflows/lando-start.yml)

## Requirements

- [Lando 3.11+](https://docs.lando.dev/basics/installation.html#system-requirements)

If you plan to use a HMR theme via the lando proxy, you need to trust your [Lando SSL certificates](https://docs.lando.dev/core/v3/security.html#trusting-the-ca).

## Local environment setup

```bash
lando start
```

### Logging in

```bash
lando drush uli
```

### Using NPM

```bash
lando npm <command>
```

### Building theme locally

```bash
lando theme install
lando theme run dev
```

**Clear Drupal cache** to start using Vite HMR.

### Building theme for production

```bash
lando theme run build
```

### Configuring node domain (local dev HMR)

Check your [Lando SSL certificates](https://docs.lando.dev/core/v3/security.html#trusting-the-ca).

```yml
proxy:
  node:
    - node.<< LANDO APP NAME >>.lndo.site:3000
```

## Non-lando setup

There is a `docker-compose` stack here, it's mostly for reference and hosting the demo.

Create a .env `cp .env.example .env` then fill in the database credentials.

```bash
cp docker-compose.override.yml.example docker-compose.override.yml
docker-compose up
docker-compose exec -it cli drush si --existing-config --account-name=admin -y
```

### Cleaning up

```bash
docker-compose down --rmi all -v --remove-orphans
```

## FAQ

### How do I override Lando locally?

Create `/.lando.local.yml` file for local development. See [docs](https://docs.lando.dev/core/v3/#override-file).

### How can I connect to the database?

Get database credentials:

```bash
lando info -s database --path "0.creds" --format table
```

Get database host and port:

```bash
lando info -s database --path "0.external_connection" --format table
```

### How can I test email? Use MailHog.

```bash
lando info -s mailhog --path "0.urls"
```

### What does the template do?

When installing the given `composer.json` some tasks are taken care of:

- Drupal will be installed in the `web` directory.
- Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`, instead of the one provided by Drupal (`web/vendor/autoload.php`).
- Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib/`
- Themes (packages of type `drupal-theme`) will be placed in `web/themes/contrib/`
- Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib/`
- Creates the `web/sites/default/files` directory.
- Latest version of drush is installed locally for use at `vendor/bin/drush`.

### Should I commit the contrib modules or themes I download?

No.

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
