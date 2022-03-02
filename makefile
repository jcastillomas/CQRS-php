env=dev
compose=docker-compose -f etc/dev/docker-compose.yml

export compose env

.PHONY: start
start: clear build up database

.PHONY: dev-start
dev-start: clear up

#################
##### START #####
#################

.PHONY: clear
clear:
	$(compose) stop
	$(compose) rm -v -f

.PHONY: build
build:
	$(compose) build
	$(compose) run --rm php sh -lc 'COMPOSER_MEMORY_LIMIT=-1 composer install'


.PHONY: up
up:
	$(compose) up -d

.PHONY: database
database:
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:drop --force --if-exists'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:create --if-not-exists'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:migrations:migrate -n'

####################
##### COMMANDS #####
####################

.PHONY: swagger
swagger:
	$(compose) exec -T php sh -lc 'php ./vendor/bin/openapi ./src/UI/Http/Rest -o ./public/swagger-ui/swagger.json --format json --exclude vendor --pattern "*.php"'

.PHONY: tests
tests: refresh-test-db
	$(compose) exec -T php sh -lc 'php ./vendor/bin/phpunit'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:drop --force --if-exists  --env=test'

.PHONY: utests
utests:
	$(compose) exec -T php sh -lc 'php ./vendor/bin/phpunit tests/Unit'

.PHONY: itests
itests: refresh-test-db
	$(compose) exec -T php sh -lc 'php ./vendor/bin/phpunit tests/Integration'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:drop --force --if-exists  --env=test'

.PHONY: e2etests
e2etests: refresh-test-db
	$(compose) exec -T php sh -lc 'php ./vendor/bin/phpunit tests/EndToEnd'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:drop --force --if-exists  --env=test'

#################
##### UTILS #####
#################

.PHONY: refresh-test-db
refresh-test-db:
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:drop --force --if-exists  --env=test'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:database:create --env=test --if-not-exists'
	$(compose) exec -T php sh -lc 'php ./bin/console doctrine:migrations:migrate --env=test -n'
