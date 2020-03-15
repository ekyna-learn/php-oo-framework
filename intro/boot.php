<?php

require __DIR__ . '/../vendor/autoload.php';

use Form\Field;
use Form\Form;

$host = 'localhost';
$port = 12221;
$database = 'framework';
$user = 'framework';
$password = 'framework';

// Connection à la base de données
$connection = new PDO("mysql:dbname=$database;host=$host;port=$port", $user, $password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userForm = new Form('user');
$userForm
    ->addField(new Field\TextField('email', 'Email'))
    ->addField(new Field\TextField('name', 'Nom'))
    ->addField(new Field\DateTimeField('birthday', 'Date de naissance', [
        'required' => false,
    ]))
    ->addField(new Field\CheckboxField('active', 'Actif'));
