#1!/bin/sh

dockerize -wait tcp://database:5432 -timeout 120s

export DATABASE_URL="postgresql://symfony:ChangeMe@database:5432/app?serverVersion=13&charset=utf8"
symfony server:start
