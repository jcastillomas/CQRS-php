env=dev
docker-os=
compose=docker-compose -f docker-compose.yml -f etc/$(env)/docker-compose.yml

export compose env docker-os

.PHONY: start
start: clear build dependencies up ## clean current environment, recreate dependencies and spin up again

##################
##### START  #####
##################

.PHONY: clear
clear: ## stop and delete containers, clean volumes.
		$(compose) stop
		docker-compose rm -v -f

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		$(compose) build
        sh -lc 'COMPOSER_MEMORY_LIMIT=-1 composer install'; \


.PHONY: dependencies
dependencies:  ## Start all dependencies and wait for it
		$(compose) run --rm start_dependencies

.PHONY: up
up: ## spin up environment
		$(compose) up -d


##################
##################
##################

.PHONY: phpunit
phpunit: ## execute project unit tests
		sh -lc "php bin/phpunit"

.PHONY: stop
stop: ## stop environment
		$(compose) stop $(s)

.PHONY: rebuild
rebuild: start ## same as start


.PHONY: build-ci

.PHONY: artifact
artifact: ## build production artifact
		docker-compose -f etc/artifact/docker-compose.yml build

.PHONY: composer-update
composer-update: ## Update project dependencies
		$(compose) run --rm php sh -lc 'xoff;COMPOSER_MEMORY_LIMIT=-1 composer update'


.PHONY: coverage
coverage:
		$(compose) run --rm php sh -lc "wget -q https://github.com/php-coveralls/php-coveralls/releases/download/v2.2.0/php-coveralls.phar; \
			chmod +x php-coveralls.phar; \
			export COVERALLS_RUN_LOCALLY=1; \
			export COVERALLS_EVENT_TYPE='manual'; \
			export CI_NAME='github-actions'; \
			php ./php-coveralls.phar -v; \
		"
.PHONY: phpstan
phpstan: ## executes php analizers
		$(compose) run --rm code sh -lc './vendor/bin/phpstan analyse -l 6 -c phpstan.neon src tests'

.PHONY: psalm
psalm: ## execute psalm analyzer
		$(compose) run --rm code sh -lc './vendor/bin/psalm --show-info=false'

.PHONY: cs
cs: ## executes coding standards
		$(compose) run --rm code sh -lc './vendor/bin/ecs check src tests --fix'

.PHONY: cs-check
cs-check: ## executes coding standards in dry run mode
		$(compose) run --rm code sh -lc './vendor/bin/ecs check src tests'

.PHONY: layer
layer: ## Check issues with layers
		$(compose) run --rm code sh -lc 'php bin/deptrac.phar analyze --formatter-graphviz=0'

.PHONY: sh
sh: ## gets inside a container, use 's' variable to select a service. make s=php sh
		$(compose) exec $(s) sh -l

.PHONY: logs
logs: ## look for 's' service logs, make s=php logs
		$(compose) logs -f $(s)

.PHONY: htemplate
htemplate:
		helm template cqrs etc/artifact/chart4