<?php
require_once('dbpa.php');

if (isset($_POST['verify'])) {
    $id_captcha = $_POST['id_captcha'];
    $user_answer = $_POST['answer'];
    $correct_answer = false;

    
    $query = $dbh->prepare("SELECT answer FROM CAPTCHA WHERE id_captcha = ?");
    $query->execute([$id_captcha]);
    $captchaInfos = $query->fetch();
    
    if ($captchaInfos) {
        $correct_answer = $captchaInfos['answer'];

        if ($user_answer == $correct_answer) {
            echo "Correct !";
        } else {
            echo "La réponses est fausse, veuillez réessayer.";
        }
    } else {
        echo "Invalid CAPTCHA ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAPTCHA</title>
</head>
<body>
    <?php
    $result = $dbh->prepare("SELECT id_captcha, question, answer, image_path FROM CAPTCHA ORDER BY RAND() LIMIT 1");
    $result->execute();
    if ($result->rowCount() > 0) {
        $captcha = $result->fetch();
        $question = $captcha['question'];
        $image_path = $captcha['image_path'];
    } else {
        die("Aucun captcha trouvé.");
    }
    ?>

    <form action="" method="post">
        <span style="color: red; font-size: 30px;"><?php echo htmlspecialchars($question); ?><br></span>
        <img src="<?php echo 'backend/' . htmlspecialchars($captcha['image_path']); ?>" alt="CAPTCHA Image" style="width:12rem; height:12rem"><br>
        <input type="text" id="answer" name="answer" required>
        <input type="hidden" id="id_captcha" name="id_captcha" value="<?php echo htmlspecialchars($captcha['id_captcha']); ?>">
        <!--<button type="submit" name="verify">Vérifier</button>-->
    </form>
</body>
</html>
