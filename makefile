env=dev
compose=docker-compose -f etc/$(env)/docker-compose.yml

export compose env

.PHONY: start
start: clear build up ## clean current environment, recreate dependencies and spin up again

.PHONY: dev-start
dev-start: clear up

##################
##### START  #####
##################

.PHONY: clear
clear: ## stop and delete containers, clean volumes.
		$(compose) stop
		$(compose) rm -v -f

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		$(compose) build
        sh -lc 'COMPOSER_MEMORY_LIMIT=-1 composer install'; \

.PHONY: up
up: ## spin up environment
		$(compose) up -d

##################
##################
##################

.PHONY: swagger
swagger: ## spin up environment
		./vendor/bin/openapi ./src/UI/Http/Rest -o ./public/swagger-ui/swagger.json --format json --exclude vendor --pattern "*.php"

