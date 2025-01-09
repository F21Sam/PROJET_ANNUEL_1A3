<?php
session_start();
if (!isset($_SESSION['pseudo']) && !isset($_SESSION['id_user'])) {
    header('Location: login_page_forum.php');
    exit();
}

include_once('dbpa.php');

$response = ['success' => false];

if (isset($_GET['id_rep']) && !empty($_GET['id_rep'])) {
    $id_rep = $_GET['id_rep'];
    $id_user = $_SESSION['id_user'];

    $repUtilisateur = $dbh->prepare('SELECT id_rep FROM REP_TOPIC WHERE id_rep = ? AND id_user = ?');
    $repUtilisateur->execute([$id_rep, $id_user]);
    $reponse = $repUtilisateur->fetch(PDO::FETCH_ASSOC);

    if ($reponse) {
        $suppressionReponse = $dbh->prepare('DELETE FROM REP_TOPIC WHERE id_rep = ?');
        if ($suppressionReponse->execute([$id_rep])) {
            $response['success'] = true;
        }
    }
}

echo json_encode($response);
?>
