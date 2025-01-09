<?php
global $dbh;
require_once('../dbpa.php');

    $sqlmail = "SELECT mail FROM USER WHERE newsletter = 1";
    $result = $dbh->query($sqlmail);
    $result = $result->fetchAll();
    $adresses_email = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $adresses_email[] = $row['mail'];
        } 
    } else {
            $adresses_email = array(
                "foo" => "bar",
                "bar" => "foo",
                100   => -100,
                -100  => 100,
            );
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP Test</title>
    </head>
    <body>
        <?php var_dump ($adresses_email) ?>
        <?php var_dump ($result) ?>
    </body>
</html>