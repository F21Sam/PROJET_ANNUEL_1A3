<?php
session_start();
if (!isset($_SESSION['pseudo']) && !isset($_SESSION['id_user'])) {
    header('Location: login_page_forum.php');
    exit();
}

include_once('dbpa.php');

$response = ['success' => false];

if (isset($_GET['id_topic']) && !empty($_GET['id_topic'])) {
    $id_topic = $_GET['id_topic'];
    $id_user = $_SESSION['id_user'];

    $rechercheSujet = $dbh->prepare('SELECT id_topic FROM TOPIC WHERE id_topic = ? AND id_user = ?');
    $rechercheSujet->execute([$id_topic, $id_user]);
    $topic = $rechercheSujet->fetch(PDO::FETCH_ASSOC);

    if ($topic) {
        $suppressionSujet = $dbh->prepare('DELETE FROM TOPIC WHERE id_topic = ?');
        if ($suppressionSujet->execute([$id_topic])) {
            $response['success'] = true;
        }
    }
}

echo json_encode($response);
?>
