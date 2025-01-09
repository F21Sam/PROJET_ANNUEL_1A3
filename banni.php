<?php
session_start(); 

if (!isset($_SESSION['message'])) {
    header("Location: login_page.php"); 
    exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vous etes banni</title>
    <style>
        body {
            background-color: #183425;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
            flex-direction: column;
            background-color: #183425;
            padding: 10px;
            border-radius: 10px;
            
        }
        .header-text {
            margin-bottom: 20px;
        }
        .header-text h1 {
            font-size: 72px;
            margin: 0;
            color: #BC644B;
        }
        h2 {
            font-size: 24px;
            margin: 0;
            color: #f2d8be;
        }
        p {
            margin: 20px 0;
            color: #fff;
        }
        a.btn-home {
            background-color: #BC644B;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        a.btn-home:hover {
            background-color: #a22300;
        }
        .left-box, .right-box {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .right-box img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
            margin-top: 30px;
        }
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
                justify-content: space-between;
            }
            .left-box, .right-box {
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-box">
            <div class="header-text">
                <h1>OUPS!</h1>
            </div>
            <h2>Vous êtes banni d'Amimal.org</h2>
            <p><?php echo $_SESSION['message']; ?></p>
    <p>Si vous souhaitez être débanni, envoyez nous un email <a href="mailto:amimal.refuge@gmail.com">amimal.refuge@gmail.com</a></p>
        </div>
        <div class="right-box">
            <img src="IMAGES/lion-regard-triste-son-visage_720722-3647.png" alt="Lion triste">
        </div>
    </div>
</body>
</html>


<?php
unset($_SESSION['message']); 
?>
