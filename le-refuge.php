
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projet_Annuel_Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="STYLE/style_global.css" rel="stylesheet" />
  <link href="STYLE/le-refuge.css" rel="stylesheet" />
  <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
  <link href="STYLE/dark_mode.css" rel="stylesheet" />
  
</head>

<body>
  <header>
  <?php 


global $dbh;
require_once('includes/dbpa.php');

$page_id = 2;
$stmt = $dbh->prepare("SELECT section, title, content FROM ARTICLES WHERE page_id = :page_id");
$stmt->execute(['page_id' => $page_id]);

$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);


include "includes/main_navbar.php";
?>
  </header>
  <main>
    
    <section class="headband">
        <div class="container">
          <h1>Le refuge Amimal</h1>
        </div>
    </section>

    
    <section class="section-1">
      <div class="container">
        <div class="row">
          <?php foreach ($contents as $content): ?>
            
            <?php if ($content['section'] == 'section-1'): ?>
              <div class="col-12 col-lg-7">
                <h3 class="titre orange"><?php echo htmlspecialchars($content['title']); ?></h3>
                <p><?php echo htmlspecialchars($content['content']); ?></p>
            <?php elseif ($content['section'] == 'section-2'): ?>
                <h3 class="titre orange"><?php echo htmlspecialchars($content['title']); ?></h3>
                <p><?php echo htmlspecialchars($content['content']); ?></p>
            <?php elseif ($content['section'] == 'section-3'): ?>
                <h3 class="titre orange"><?php echo htmlspecialchars($content['title']); ?></h3>
                <p><?php echo htmlspecialchars($content['content']); ?></p>
              </div>
              <div class="col-12 col-lg-5">
            <img src="IMAGES/panda.png" alt="loutre" class="img-fluid" width="150%" height="150%">
          </div>
          
            <?php elseif ($content['section'] == 'section-4'): ?>
                <h3 class="titre orange"><?php echo htmlspecialchars($content['title']); ?></h3>
                <p><?php echo htmlspecialchars($content['content']); ?></p>
            <?php elseif ($content['section'] == 'section-5'): ?>
                <h3 class="titre orange"><?php echo htmlspecialchars($content['title']); ?></h3>
                <p><?php echo htmlspecialchars($content['content']); ?></p>
            <?php elseif ($content['section'] == 'section-6'): ?>
                <h3 class="titre orange"><?php echo htmlspecialchars($content['title']); ?></h3>
                <p><?php echo htmlspecialchars($content['content']); ?></p>
            <?php endif; ?>
          <?php endforeach; ?>
          
          
        </div>
      </div>
    </section>
    
    <?php include "includes/newsletter_front.php"; ?>
  
  </main>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>

  <?php include "includes/dark_mode.php"; ?>

</body>

</html>