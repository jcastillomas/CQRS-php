#!/usr/bin/env bash

set -exo pipefail

while ! mysqladmin ping -h${DATABASE_HOST:-localhost} --silent; do
	echo "MYSQL NOT READY"
    sleep 1
done
echo "MYSQL READY"

if [[ ${XDEBUG_ENABLED:-"false"} == "true" ]] ; then
    echo "WARNING: XDEBUG LOADED!"
    echo "         xdebug being loaded on production even if its not enabled at all degrades performance!!"
    docker-php-ext-enable xdebug
else
    echo "NOTE: You can enable manually xdebug by running 'docker-php-ext-enable xdebug'"
    echo "      and signaling apache with 'kill -SIGUSR1 <apache_pid>' to refresh the process."
    echo "      Also, you can start the container with XDEBUG_ENABLED=true to start it automatically"
fi

if [[ ${SKIP_MIGRATIONS:-0} == 0 ]] ; then
  bin/console doctrine:migrations:migrate --no-interaction
fi
php-fpm
