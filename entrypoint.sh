#!/bin/sh sh

composer update

phpunit

phpstan analyse Controller/ DependencyInjection/ Event/ EventListener/ Exception/ Filter/ Grid/ Service/ Tests/ --level=4

#tail -f /dev/null