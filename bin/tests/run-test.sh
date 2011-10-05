#! /bin/sh

files=.
#`find . -iname "*Test.php"`

phpunit --stop-on-failure --stop-on-error --colors --verbose --strict --syntax-check --bootstrap ../../config.php --coverage-html PHPUnitTest-coverage $files

