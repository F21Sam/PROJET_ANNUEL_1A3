<?php

$user = 'distant';
$pass = 'secure';

try {
    $dbh = new PDO('mysql:host=54.37.65.182;dbname=projet', $user, $pass);
} catch (PDOException $e) {
    var_dump($e);
} 

?>
