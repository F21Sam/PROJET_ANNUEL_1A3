<?php
session_start();
global $dbh;
require_once('dbpa.php');
if(isset($_GET['num']) AND !empty($_GET['num'])){
    $getnum = $_GET['num'];
    $recupUser = $dbh->prepare('SELECT id_user, nom, prenom, pseudo, password, mail, date_ins, role_type, confirm, newsletter FROM USER WHERE id_user = ?');
    $recupUser->execute(array($getnum));
    if($recupUser->rowCount() > 0){
        $userInfo = $recupUser->fetch();
        echo $userInfo['confirm'];
        if($userInfo['confirm'] != 1){
            $updateConfirm = $dbh->prepare('UPDATE USER SET confirm = ? WHERE id_user = ?');
            $updateConfirm->execute(array(1, $getnum));
            $_SESSION['id_user']=$getnum;
            $_SESSION['pseudo']=$userInfo['pseudo'];
            header('Location: ../index.php');
        } else {
            $_SESSION['id_user']=$getnum;
            $_SESSION['pseudo']=$userInfo['pseudo'];
            header('Location: ../index.php');
        }
    }else{
        echo "votre identifiant est incorrect";
    }

}else{
    echo"Aucun utilisateur trouvé";
}
?>