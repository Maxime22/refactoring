# Context 

Base on https://github.com/Maxime22/php-minimum, this repo is used to recreate Martin's Fowler example in the book Refactoring (but in PHP).

## How to run?
- ```make build```
- ```make up```
- ```make install``` (create vendor/autoload.php)
- Go to http://127.0.0.1:8080/

## Tests
- ```make create-tests-setup``` (first time)
- ```make add-phpunit``` (first time)
- ```make test```