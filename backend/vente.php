<?php include_once('includes/journalisations.php');
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
//includes
    include_once("includes/header.php");
    include_once('includes/navbar.php');
    include_once('includes/journalisations.php'); 
    require('includes/dbpa.php');
    include_once('includes/sidenav.php');
