<?php

require('includes/dbpa.php');

$responsSql = "SELECT id_rep, id_user, pseudo, id_topic, rep_content, rep_date_publi FROM REP_TOPIC WHERE id_topic = ? ORDER BY rep_date_publi DESC";
$getRespons = $dbh->prepare($responsSql);
$getRespons->execute(array($idTopic));

?>