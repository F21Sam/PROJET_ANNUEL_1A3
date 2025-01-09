<?php 
session_start();
require_once('includes/dbpa.php');

if(isset($_GET['id_topic']) AND !empty($_GET['id_topic'])){

    $idTopic = $_GET['id_topic'];
    $checkTopic = $dbh->prepare('SELECT id_topic, author, title, date_publi, content_topic, id_user, id_animal FROM TOPIC WHERE id_topic = ?');
    $checkTopic->execute(array($idTopic));

    if($checkTopic->rowCount() > 0) {

        $topicInfos = $checkTopic->fetch();

        $topicTitle = $topicInfos['title'];
        $topicContent = $topicInfos['content_topic'];
        $topicAuthor = $topicInfos['author'];
        $topicDate = $topicInfos['date_publi'];
        $topicAnimal = $topicInfos['id_animal'];
        

    }else{
        $errorMsg ="Cet article n'existe pas.";
    }

}else{
    $errorMsg = "aucun article n'a été trouvé";
}