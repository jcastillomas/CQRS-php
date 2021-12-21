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
