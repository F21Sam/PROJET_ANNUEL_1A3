<?php
global $dbh;
require_once('includes/dbpa.php');

if(isset($_POST['publish'])){
    if(!empty($_POST['title']) AND !empty($_POST['content'])) {

        function genererNumeroUnique($dbh) {
            $numero = mt_rand(10000000, 99999999); // on met la plage de numéro à attribuer
            $sqlverif = "SELECT id_topic, author, date_publi, id_user, id_animal, content_topic, title FROM TOPIC WHERE id_topic = $numero"; // on sélectionne la table et la colonne à vérifier 
            $result = $dbh->query($sqlverif);
            if ($result->num_rows > 0) {
                // Si le numéro existe déjà, on le régénère
                return genererNumeroUnique($dbh);
            } else {
                // Si le numéro est unique, on le retourne
                return $numero;
            }
        }

        $id_topic = genererNumeroUnique($dbh);
        $title = htmlspecialchars($_POST['title']);
        $content = nl2br(htmlspecialchars($_POST['content']));
        // $animal = $_POST['animal'];
        $id_animal = $_POST['id_animal'];
        $date_publi = date('Y/m/d');
        $id_user = $_SESSION['id_user'];
        $author = $_SESSION['pseudo'];

        $preparedTopic = "INSERT INTO TOPIC (id_topic, author, date_publi, id_user, id_animal, content_topic, title)
        VALUES (:id_topic, :author, :date_publi, :id_user, :id_animal, :content_topic, :title)";

        $insertTopic = $dbh->prepare($preparedTopic);
        $insertTopic->execute([
            'id_topic' => $id_topic,
            'author' => $author,
            'date_publi' => $date_publi,
            'content_topic' => $content,
            'id_user' => $id_user,
            'id_animal' => $id_animal,
            'title' => $title
        ]);

        $successMsg = "Votre sujet vient d'être publié";

    }else{
        $errorMsg = "Veuillez compléter tous les champs";
    }
}