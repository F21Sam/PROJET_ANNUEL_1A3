<?php
global $dbh;
require_once('dbpa.php');
// verify_captcha.php

if (isset ($_POST['id_captcha']) && isset($_POST['user_answer'])) {
    $id_captcha = $_POST['id_captcha'];
    $user_answer = $_POST['user_answer'];

    // Récupérer la réponse correcte depuis la base de données
    $query = $dbh->prepare("SELECT answer FROM CAPTCHA WHERE id_captcha = ?");
    $query->execute(array($id_captcha));
    $captchaInfos = $query->fetch();
    $correct_answer = $captchaInfos['answer'];

    // Vérifier la réponse de l'utilisateur
    if ($user_answer == $correct_answer) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
