Place files in here that you want to add to your Drupal site. You will also need to add them to the extra/file-mapping section in composer.json.

As per https://www.drupal.org/docs/develop/using-composer/using-drupals-composer-scaffold
e.g.

```json
"file-mapping": {
    "[web-root]/sites/default/settings.php": "assets/settings.php"
},
```
