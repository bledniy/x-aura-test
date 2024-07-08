# X-Aura-Test-Task

A project in the following configuration:
1. Latest stable Symfony framework (6.2 at the moment)
2. PHP 8.1
3. PostgreSQL 14.2
4. Separate Docker containers for Nginx, FPM, CLI and a database
5. CS-Fixer and Psalm on board

# The concept

1. The application and docker files are located on the same level: in the `/app` and `/docker` folders, respectively. 
   This allows you to separate the symphony-application and docker environment variables, and to implement the mono 
   repository pattern by adding new folders if necessary: `/centrifugo`, `s3-storage`, etc.
2. The `docker-compose.override.yaml` is ignored by default, so you can add your own settings without worrying about 
   overwriting the original ones.

# Quick Start

1. `make configs-setup` - create .env files for docker containers
2. `make init` - very important! Run it before making any commits to your repo. 
3. `make up` - start docker containers

Default ports are random (47001-47999) for every created project, so click the link generated in CLI with the output of `make up` command and enjoy!

You also can set desired ports for Nginx and PostgreSQL manually in generated /.env file (don't forget to run `make restart` afterwards).

# Useful makefile commands

1. `make console` - default shell is zsh with preinstalled set of [plugins](https://github.com/alyamovsky/symfony-docker-website-skeleton/blob/main/docker/dev/php-cli/.zshrc)
2. `make db-seed` - upload default fixtures

