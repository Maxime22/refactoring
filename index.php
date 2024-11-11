<?php

require 'vendor/autoload.php';

use App\Example;

// Charge les fichiers JSON et les décode en objets PHP
$invoices = json_decode(file_get_contents('src/Invoices.json'), true);
$plays = json_decode(file_get_contents('src/Plays.json'), true);

$first = new Example();

// Passe les données du premier élément d'invoices en argument
echo $first->statement($invoices[0], $plays);
