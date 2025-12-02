<?php
// config.php

$host = 'localhost';
$db   = 'louis_notes';   // nom de ta base
$user = 'root';          // ton user MySQL
$pass = '';              // ton mot de passe MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // erreurs en exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch assoc
    PDO::ATTR_EMULATE_PREPARES   => false,                  // vrais prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Ne jamais afficher ça en production, mais utile pour tester
    die('Erreur de connexion : ' . $e->getMessage());
}
