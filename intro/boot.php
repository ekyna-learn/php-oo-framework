<?php

require __DIR__ . '/../vendor/autoload.php';

$host = 'localhost';
$port = 3306;
$database = 'framework';
$user = 'framework';
$password = 'framework';

// Connection à la base de données
$connection = new PDO("mysql:dbname=$database;host=$host;port=$port", $user, $password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
