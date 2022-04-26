#!/bin/sh

dockerize -wait tcp://database:5432 -timeout 120s

./bin/console doctrine:migration:migrate --no-interaction
./vendor/bin/phpunit

chmod 0777 -R ./test
