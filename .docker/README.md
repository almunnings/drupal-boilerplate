# Vanilla Docker setup

There is a `docker-compose` stack here, it's mostly for reference and hosting the demo.

## Setting up

Create a .env `cp .env.example .env` then fill in the database credentials.

```bash
docker-compose up -d
docker-compose exec -it cli drush si --existing-config --account-name=admin -y
```

To expose the site on port 80, create a `docker-compose.override.yml` file with the following:

```yml
services:
  nginx:
    ports:
      - 80:8080
```

## Cleaning up

```bash
docker-compose down --rmi all -v --remove-orphans
```
