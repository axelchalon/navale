# Équipe 2 : Bataille navale ![Build Status](https://travis-ci.org/axelchalon/navale.svg?branch=master)

## Composition de l'équipe
 * Amine BOUNEGGAR
 * Axel CHALON
 * Veasnard LE
 * Kris PHIVILAY
 * Pauline TAVENAU

## Installation
 * `git clone https://github.com/axelchalon/navale/ .`
 * `composer install --dev`
 * `./bin/console doctrine:schema:update --force`
 * `./bin/console hautelook_alice:doctrine:fixtures:load --purge-with-truncate`
 * `./bin/console s:r`
 * Vous pouvez tester la collection Postman.
 * Vous pouvez lancer les tests unitaires : `phpunit`
 * Vous pouvez visiter l'interface sur votre navigateur

