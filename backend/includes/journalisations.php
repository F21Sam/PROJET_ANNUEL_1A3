<?php
error_reporting(E_ALL); 
ini_set('display_errors',1);

global $dbh;
require_once ("dbpa.php");


function getIPAddress() {
   
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
   
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


$ip_address = getIPAddress();
$page_url = $_SERVER['REQUEST_URI'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$timestamp = date('Y-m-d H:i:s');

$sql = "INSERT INTO LOGS (ip_address, page_url, user_agent, timestamp) VALUES (:ip_address, :page_url, :user_agent, :timestamp)";
$stmt = $dbh->prepare($sql);
$stmt->execute(array(
    ':ip_address' => $ip_address,
    ':page_url' => $page_url,
    ':user_agent' => $user_agent,
    ':timestamp' => $timestamp
));