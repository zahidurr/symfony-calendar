# Parameters
SHELL         = zsh
PROJECT       = api_calender
HTTP_PORT     = 8986

# Executables
EXEC_PHP      = php
COMPOSER      = composer
REDIS         = redis-cli
GIT           = git
YARN          = yarn
NPX           = npx

# Alias
SYMFONY       = $(EXEC_PHP) bin/console
# if you use Docker you can replace "with: docker-composer exec my_php_container $(EXEC_PHP) bin/console"

# Executables: vendors
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer
CODESNIFFER   = ./vendor/squizlabs/php_codesniffer/bin/phpcs

# Executables: local only
SYMFONY_BIN   = symfony
BREW          = brew
DOCKER        = docker
DOCKER_COMP   = docker compose

# Executables: prod only
CERTBOT       = certbot

# Misc
.DEFAULT_GOAL = help
.PHONY       =  # Not needed here, but you can put your all your targets to be sure
                # there is no name conflict between your files and your targets.

## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install --no-progress --prefer-dist --optimize-autoloader

## —— PHP 🐘 (macOS with brew) —————————————————————————————————————————————————
php-upgrade: ## Upgrade PHP to the last version
	$(BREW) upgrade php

php-set-7-3: ## Set php 7.3 as the current PHP version
	$(BREW) unlink php
	$(BREW) link --overwrite php@7.3

php-set-7-4: ## Set php 7.4 as the current PHP version
	$(BREW) unlink php
	$(BREW) link --overwrite php@7.4

php-set-8-0: ## Set php 8.0 as the current PHP version
	$(BREW) unlink php
	$(BREW) link --overwrite php@8.0

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands
	$(SYMFONY)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

warmup: ## Warmup the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY) assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

## —— Symfony binary 💻 ————————————————————————————————————————————————————————
cert-install: ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

serve: ## Serve the application with HTTPS support (add "--no-tls" to disable https)
	$(SYMFONY_BIN) serve --daemon --port=$(HTTP_PORT)

unserve: ## Stop the webserver
	$(SYMFONY_BIN) server:stop

## —— elasticsearch 🔎 —————————————————————————————————————————————————————————
populate: ## Reset and populate the Elasticsearch index
	$(SYMFONY) fos:elastica:reset
	$(SYMFONY) fos:elastica:populate --index=app
	$(SYMFONY) strangebuzz:populate

list-index: ## List all indexes on the cluster
	curl http://localhost:9209/_cat/indices?v

delete-index: ## Delete a given index (parameters: index=app_2021-01-05-075600")
	curl -X DELETE "localhost:9209/$(index)?pretty"

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: ## Start the docker hub (MySQL,redis,adminer,elasticsearch,head,Kibana)
	$(DOCKER_COMP) up --build -d

ps: ## Start the docker hub (MySQL,redis,adminer,elasticsearch,head,Kibana)
	$(DOCKER_COMP) ps

docker-build: ## Builds the PHP image
	$(DOCKER_COMP) build php

down: ## Stop the docker hub
	$(DOCKER_COMP) down --remove-orphans

check: ## Docker check
	@$(DOCKER) info > /dev/null 2>&1                                                        # Docker is up
	@test '"healthy"' = `$(DOCKER) inspect --format "{{json .State.Health.Status }}" sb-db` # Db container is up and healthy

wait-for-elasticsearch: ## Wait for Elasticsearch to be ready
	bin/wait-for-elasticsearch.sh

bash: ## Connect to the application container
	$(DOCKER) container exec -it sb-app bash

## —— Project 🐝 ———————————————————————————————————————————————————————————————
start: up wait-for-mysql load-fixtures wait-for-elasticsearch populate serve ## Start docker, load fixtures, populate the Elasticsearch index and start the webserver

reload: load-fixtures populate ## Load fixtures and repopulate the Elasticserch index

stop: down unserve ## Stop docker and the Symfony binary server

cc-redis: ## Flush all Redis cache
	$(REDIS) -p 6389 flushall

commands: ## Display all commands in the project namespace
	$(SYMFONY) list $(PROJECT)

load-fixtures: ## Build the DB, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:schema:drop --force
	$(SYMFONY) doctrine:schema:create
	$(SYMFONY) doctrine:schema:validate
	$(SYMFONY) hautelook:fixtures:load --no-interaction

init-snippet: ## Initialize a new snippet
	$(SYMFONY) $(PROJECT):init-snippet

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: phpunit.xml check ## Run main functional and unit tests
	$(eval testsuite ?= 'main') # or "external"
	$(eval filter ?= '.')
	$(PHPUNIT) --testsuite=$(testsuite) --filter=$(filter) --stop-on-failure

test-all: phpunit.xml ## Run all tests
	$(PHPUNIT) --stop-on-failure

## —— Coding standards ✨ ——————————————————————————————————————————————————————
cs: lint-php lint-js ## Run all coding standards checks

static-analysis: stan ## Run the static analysis (PHPStan)

stan: ## Run PHPStan
	$(PHPSTAN) analyse -c configuration/phpstan.neon --memory-limit 1G

lint-php: ## Lint files with php-cs-fixer
	$(PHP_CS_FIXER) fix --dry-run

fix-php: ## Fix files with php-cs-fixer
	$(PHP_CS_FIXER) fix

env-check: ## Check the main ENV variables of the project
	printenv | grep -i app_

## —— Yarn 🐱 / JavaScript —————————————————————————————————————————————————————
dev: ## Rebuild assets for the dev env
	$(YARN) install --check-files
	$(YARN) run encore dev

watch: ## Watch files and build assets when needed for the dev env
	$(YARN) run encore dev --watch

build: ## Build assets for production
	$(YARN) run encore production

lint-js: ## Lints JS coding standarts
	$(NPX) eslint assets/js

fix-js: ## Fixes JS files
	$(NPX) eslint assets/js --fix
