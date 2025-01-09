<?php
require('includes/dbpa.php');

if(isset($_POST['rep_post'])){

    if(!empty($_POST['rep_content'])){

        function genererNumeroUnique($dbh) {
            $numero = mt_rand(10000000, 99999999); // on met la plage de numéro à attribuer
            $sqlverif = "SELECT id_rep FROM REP_TOPIC WHERE id_rep = $numero"; // on sélectionne la table et la colonne à vérifier 
            $result = $dbh->query($sqlverif);
            if ($result->num_rows > 0) {
                // Si le numéro existe déjà, on le régénère
                return genererNumeroUnique($dbh);
            } else {
                // Si le numéro est unique, on le retourne
                return $numero;
            }
        }
        $id_topic = $_GET['id_topic'];
        $id_rep = genererNumeroUnique($dbh);
        $rep_date_publi = date('Y/m/d');
        $id_user = $_SESSION['id_user'];
        $pseudo = $_SESSION['pseudo'];

        $rep_content = nl2br(htmlspecialchars($_POST['rep_content']));
        $preparedAnswer = "INSERT INTO REP_TOPIC (id_rep, id_user, pseudo, id_topic, rep_content, rep_date_publi) 
        VALUES (:id_rep, :id_user, :pseudo, :id_topic, :rep_content, :rep_date_publi)";
        $insertAnswer = $dbh->prepare($preparedAnswer);
        $insertAnswer->execute([
            'id_rep' => $id_rep,
            'id_user' => $id_user,
            'pseudo' => $pseudo,
            'id_topic' => $id_topic,
            'rep_content' => $rep_content,
            'rep_date_publi' => $rep_date_publi
        ]);

    }else {
        echo 'erreu';
    }
}
?>