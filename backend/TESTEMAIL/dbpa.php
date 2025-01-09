<?php

$user = 'distant';
$pass = 'secure';
$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $dbh = new PDO('mysql:host=54.37.65.182;dbname=projet', $user, $pass, $options);
} catch (PDOException $e) {
    var_dump($e);
}

//$query = $dbh->query('SELECT * FROM users');
//
//$result = $query->fetchAll();
//
//var_dump($result);











